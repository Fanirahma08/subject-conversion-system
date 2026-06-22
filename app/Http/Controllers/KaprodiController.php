<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Conversion;
use App\Models\ConversionResult;
use App\Models\GradeConversion;
use App\Models\Subject;
use App\Models\SubjectMapping;
use App\Models\University;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KaprodiController extends Controller
{
    /**
     * Kaprodi Dashboard.
     */
    public function index()
    {
        return view('kaprodi.dashboard');
    }

    /**
     * List all subjects.
     */
    public function subjectsIndex(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $universities = University::orderBy('name')->get();

        $subjects = Subject::with('university')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhereHas('university', function ($u) use ($search) {
                            $u->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($type, function ($query, $type) {
                if ($type === 'internal') {
                    return $query->whereNull('university_id');
                } elseif (str_starts_with($type, 'external_')) {
                    $universityId = substr($type, 9);

                    return $query->where('university_id', $universityId);
                }
            })
            ->orderByRaw('university_id IS NULL DESC') // Internal subjects first
            ->orderBy('code', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('kaprodi.subjects.index', compact('subjects', 'search', 'type', 'universities'));
    }

    /**
     * Show form to create a subject.
     */
    public function subjectsCreate()
    {
        $universities = University::orderBy('name')->get();

        return view('kaprodi.subjects.create', compact('universities'));
    }

    /**
     * Store a new subject.
     */
    public function subjectsStore(Request $request)
    {
        $validated = $request->validate([
            'university_id' => ['nullable', 'exists:universities,id'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects')->where(function ($query) use ($request) {
                    return $query->where('university_id', $request->university_id)
                        ->where('code', $request->code);
                }),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sks' => ['required', 'integer', 'min:1'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:10'],
            'prodi' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        Subject::create($validated);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Mata kuliah berhasil disimpan dan tersedia untuk proses pemetaan.');
        }

        return redirect()->route('kaprodi.subjects.index')->with('success', 'Mata kuliah berhasil disimpan.');
    }

    /**
     * Show form to edit a subject.
     */
    public function subjectsEdit(Subject $subject)
    {
        $universities = University::orderBy('name')->get();

        return view('kaprodi.subjects.edit', compact('subject', 'universities'));
    }

    /**
     * Update a subject.
     */
    public function subjectsUpdate(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'university_id' => ['nullable', 'exists:universities,id'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects')->where(function ($query) use ($request) {
                    return $query->where('university_id', $request->university_id)
                        ->where('code', $request->code);
                })->ignore($subject->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sks' => ['required', 'integer', 'min:1'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:10'],
            'prodi' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $subject->update($validated);

        return redirect()->route('kaprodi.subjects.index')->with('success', 'Mata kuliah berhasil diperbarui dan disimpan.');
    }

    /**
     * Delete a subject.
     */
    public function subjectsDestroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('kaprodi.subjects.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }

    /**
     * List all subject mappings.
     */
    public function mappingsIndex(Request $request)
    {
        $search = $request->input('search');
        $universityId = $request->input('university_id');

        $universities = University::orderBy('name')->get();

        $mappings = SubjectMapping::with(['source_subject.university', 'target_subject', 'university'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('source_subject', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })->orWhereHas('target_subject', function ($tq) use ($search) {
                        $tq->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
                });
            })
            ->when($universityId, function ($query, $universityId) {
                return $query->where('university_id', $universityId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kaprodi.mappings.index', compact('mappings', 'universities', 'search', 'universityId'));
    }

    /**
     * Show form to edit mappings for a specific source subject.
     */
    public function mappingsEdit(Subject $source)
    {
        $targets = Subject::active()->whereNull('university_id')->get();
        $currentMappingIds = $source->source_mappings()->pluck('target_subject_id')->toArray();

        return view('kaprodi.mappings.edit', compact('source', 'targets', 'currentMappingIds'));
    }

    /**
     * Update mappings for a specific source subject.
     */
    public function mappingsUpdate(Request $request, Subject $source)
    {
        $request->validate([
            'target_subject_ids' => ['required', 'array'],
            'target_subject_ids.*' => ['exists:subjects,id'],
        ]);

        // Remove existing and re-create.
        $source->source_mappings()->delete();

        foreach ($request->target_subject_ids as $targetId) {
            SubjectMapping::create([
                'university_id' => $source->university_id,
                'source_subject_id' => $source->id,
                'target_subject_id' => $targetId,
            ]);
        }

        return redirect()->route('kaprodi.mappings.index')->with('success', 'Data mapping berhasil diperbarui dan disimpan.');
    }

    /**
     * Remove a subject mapping.
     */

    /**
     * Show form to create a mapping.
     */
    public function mappingsCreate()
    {
        $sources = Subject::active()->whereNotNull('university_id')->with('university')->get();
        $targets = Subject::active()->whereNull('university_id')->get();

        return view('kaprodi.mappings.create', compact('sources', 'targets'));
    }

    /**
     * Store mappings (supports one-to-many).
     */
    public function mappingsStore(Request $request)
    {
        $request->validate([
            'source_subject_id' => ['required', 'exists:subjects,id'],
            'target_subject_ids' => ['required', 'array'],
            'target_subject_ids.*' => ['exists:subjects,id'],
        ]);

        $sourceId = $request->source_subject_id;
        $targetIds = $request->target_subject_ids;
        $source = Subject::findOrFail($sourceId);

        foreach ($targetIds as $targetId) {
            SubjectMapping::firstOrCreate([
                'university_id' => $source->university_id,
                'source_subject_id' => $sourceId,
                'target_subject_id' => $targetId,
            ]);
        }

        return redirect()->route('kaprodi.mappings.index')->with('success', 'Data mapping berhasil dibuat.');
    }

    /**
     * Remove a subject mapping.
     */
    public function mappingsDestroy(SubjectMapping $mapping)
    {
        $mapping->delete();

        return redirect()->route('kaprodi.mappings.index')->with('success', 'Data mapping berhasil dihapus.');
    }

    /**
     * Remove all mappings for a specific source subject.
     */
    public function mappingsDestroyBySource(Subject $source)
    {
        SubjectMapping::where('source_subject_id', $source->id)->delete();

        return redirect()->route('kaprodi.mappings.index')->with('success', 'Data mapping berhasil dihapus.');
    }

    /**
     * List conversion requests for their prodi.
     */
    public function conversionsIndex()
    {
        $prodi = auth()->user()->prodi;
        $conversions = Conversion::whereHas('user', function ($q) use ($prodi) {
            $q->where('prodi', $prodi);
        })->with('user')->orderBy('created_at', 'desc')->get();

        return view('kaprodi.conversions.index', compact('conversions'));
    }

    /**
     * Show conversion workbench.
     */
    public function conversionsShow(Conversion $conversion)
    {
        $conversion->load(['user.studentDetail.university', 'results.source_subject', 'results.target_subject']);

        // --- SMART SYNC DETECTION ---
        // Find existing global mappings explicitly for this student's university AND prodi
        $studentUniId = $conversion->user->studentDetail->university_id;
        $studentProdi = $conversion->user->studentDetail->prodi_origin;

        $eligibleMappingsCount = SubjectMapping::where('university_id', $studentUniId)
            // ->where('prodi', $studentProdi)
            ->whereNotIn('source_subject_id', $conversion->results->pluck('source_subject_id'))
            ->count();

        // Get subjects from the same university as the student
        $sourceSubjects = Subject::where('university_id', $conversion->user->studentDetail->university_id)
            ->active()
            ->orderBy('code')
            ->get();

        // Get internal subjects
        $targetSubjects = Subject::whereNull('university_id')
            ->active()
            ->orderBy('code')
            ->get();

        // Get all global grade conversions for JS auto-lookup
        $gradeConversions = GradeConversion::all();

        return view('kaprodi.conversions.show', compact('conversion', 'sourceSubjects', 'targetSubjects', 'eligibleMappingsCount', 'gradeConversions'));
    }

    /**
     * Sync conversion results with global institutional standards.
     */
    public function conversionsResultSync(Conversion $conversion)
    {
        $studentUniId = $conversion->user->studentDetail->university_id;
        $studentProdi = $conversion->user->studentDetail->prodi_origin;

        $globalMappings = SubjectMapping::where('university_id', $studentUniId)
            // ->where('prodi', $studentProdi)
            ->get();

        $count = 0;
        foreach ($globalMappings as $map) {
            $result = $conversion->results()->firstOrCreate([
                'source_subject_id' => $map->source_subject_id,
                'target_subject_id' => $map->target_subject_id,
            ]);

            if ($result->wasRecentlyCreated) {
                $count++;
            }
        }

        return back()->with('success', "Sukses! Menerapkan {$count} standar institusi pada proses konversi ini.");
    }

    /**
     * Store a mapping result for a student.
     */
    public function conversionsResultStore(Request $request, Conversion $conversion)
    {
        $validated = $request->validate([
            'source_subject_id' => ['required', 'exists:subjects,id'],
            'target_subject_id' => ['required', 'exists:subjects,id'],
            'origin_grade' => ['nullable', 'string', 'max:10'],
            'grade' => ['nullable', 'string', 'max:10'],
        ]);

        // Auto-convert origin grade to internal grade if not manually set
        $originGrade = $validated['origin_grade'] ?? null;
        $internalGrade = $validated['grade'] ?? null;

        if ($originGrade && ! $internalGrade) {
            $internalGrade = GradeConversion::where('origin_grade', $originGrade)->value('internal_grade');
        }

        $conversion->results()->firstOrCreate([
            'source_subject_id' => $validated['source_subject_id'],
            'target_subject_id' => $validated['target_subject_id'],
        ], [
            'origin_grade' => $originGrade,
            'grade' => $internalGrade,
        ]);

        // --- GLOBAL LEARNING LOGIC ---
        // Save this mapping globally for the university
        SubjectMapping::firstOrCreate([
            'source_subject_id' => $validated['source_subject_id'],
            'target_subject_id' => $validated['target_subject_id'],
        ]);

        return back()->with('success', 'Mapping added to conversion results and saved globally.');
    }

    /**
     * Bulk store mapping results.
     */
    public function conversionsResultBulkStore(Request $request, Conversion $conversion)
    {
        $validated = $request->validate([
            'results' => ['required', 'array', 'min:1'],
            'results.*.source_subject_id' => ['required', 'exists:subjects,id'],
            'results.*.target_subject_id' => ['required', 'exists:subjects,id'],
            'results.*.origin_grade' => ['nullable', 'string', 'max:10'],
            'results.*.grade' => ['nullable', 'string', 'max:10'],
        ]);

        foreach ($validated['results'] as $result) {
            $originGrade = $result['origin_grade'] ?? null;
            $internalGrade = $result['grade'] ?? null;

            if ($originGrade && ! $internalGrade) {
                $internalGrade = GradeConversion::where('origin_grade', $originGrade)->value('internal_grade');
            }

            $conversion->results()->firstOrCreate([
                'source_subject_id' => $result['source_subject_id'],
                'target_subject_id' => $result['target_subject_id'],
            ], [
                'origin_grade' => $originGrade,
                'grade' => $internalGrade,
            ]);

            // Global Learning (Institutional Standard - Scoped by Uni & Prodi)
            SubjectMapping::firstOrCreate([
                'university_id' => $conversion->user->studentDetail->university_id,
                'prodi' => $conversion->user->prodi,
                'source_subject_id' => $result['source_subject_id'],
                'target_subject_id' => $result['target_subject_id'],
            ]);
        }

        return redirect()->back()->with('success', count($validated['results']) . ' mapping berhasil disimpan.');
    }

    /**
     * Remove a mapping result.
     */
    public function conversionsResultDestroy(ConversionResult $result)
    {
        $result->delete();

        return back()->with('success', 'Mapping removed.');
    }

    /**
     * Update a conversion result (e.g. edit grade).
     */
    public function conversionsResultUpdate(Request $request, ConversionResult $result)
    {
        $validated = $request->validate([
            'origin_grade' => ['nullable', 'string', 'max:10'],
            'grade' => ['nullable', 'string', 'max:10'],
        ]);

        $originGrade = $validated['origin_grade'] ?? null;
        $internalGrade = $validated['grade'] ?? null;

        if ($originGrade && ! $internalGrade) {
            $internalGrade = GradeConversion::where('origin_grade', $originGrade)->value('internal_grade');
        }

        $result->update([
            'origin_grade' => $originGrade,
            'grade' => $internalGrade,
        ]);

        return back()->with('success', 'Nilai berhasil diperbarui.');
    }

    public function conversionsResultBulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'results' => ['required', 'array'],
            'results.*.origin_grade' => ['nullable', 'string', 'max:10'],
            'results.*.grade' => ['nullable', 'string', 'max:10'],
        ]);

        \DB::transaction(function () use ($validated) {
            foreach ($validated['results'] as $id => $data) {
                $originGrade = $data['origin_grade'] ?? null;
                $internalGrade = $data['grade'] ?? null;

                if ($originGrade && ! $internalGrade) {
                    $internalGrade = GradeConversion::where('origin_grade', $originGrade)->value('internal_grade');
                }

                ConversionResult::where('id', $id)->update([
                    'origin_grade' => $originGrade,
                    'grade' => $internalGrade,
                ]);
            }
        });

        return back()->with('success', 'Pemetaan berhasil diperbarui.');
    }

    /**
     * Bulk delete mapping results.
     */
    public function conversionsResultBulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:conversion_results,id'],
        ]);

        ConversionResult::whereIn('id', $validated['ids'])->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus mapping yang dipilih.');
    }

    /**
     * Update conversion status.
     */
    public function conversionsUpdate(Request $request, Conversion $conversion)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:waiting_dekan,rejected,waiting'],
            'notes' => ['nullable', 'string'],
        ]);

        $hasNoGrade = $conversion->results()->whereNull('origin_grade')->exists();

        if ($validated['status'] == 'waiting_dekan' && $hasNoGrade) {
            return back()->with('error', 'Tidak dapat meneruskan ke Dekan tanpa nilai asal.');
        }

        $conversion->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->route('kaprodi.conversions.index')->with('success', 'Status konversi berhasil diperbarui.');
    }

    /**
     * Download the conversion result as PDF.
     */
    public function downloadPdf(Conversion $conversion)
    {
        // Authorization: Admin/Kaprodi/PMB/Dekan/Rektor OR the student who owns it
        if (! auth()->user()->isKaprodi() && ! auth()->user()->isPMB() && ! auth()->user()->isDekan() && ! auth()->user()->isRektor() && $conversion->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $pdf = Pdf::loadView('pdf.conversion', compact('conversion'));

        return $pdf->stream("Conversion-Certificate-{$conversion->user->name}.pdf");
    }

    /**
     * List all global grade conversions.
     */
    public function gradeConversionsIndex()
    {
        $gradeConversions = GradeConversion::orderBy('origin_grade')->get();

        return view('kaprodi.grade-conversions.index', compact('gradeConversions'));
    }

    /**
     * Store a new grade conversion mapping.
     */
    public function gradeConversionsStore(Request $request)
    {
        $validated = $request->validate([
            'origin_grade' => ['required', 'string', 'max:10', 'unique:grade_conversions,origin_grade'],
            'internal_grade' => ['required', 'string', 'max:10'],
        ]);

        GradeConversion::create($validated);

        return back()->with('success', 'Konversi nilai berhasil ditambahkan.');
    }

    /**
     * Update a grade conversion mapping.
     */
    public function gradeConversionsUpdate(Request $request, GradeConversion $gradeConversion)
    {
        $validated = $request->validate([
            'origin_grade' => ['required', 'string', 'max:10', Rule::unique('grade_conversions', 'origin_grade')->ignore($gradeConversion->id)],
            'internal_grade' => ['required', 'string', 'max:10'],
        ]);

        $gradeConversion->update($validated);

        return back()->with('success', 'Konversi nilai berhasil diperbarui.');
    }

    /**
     * Delete a grade conversion mapping.
     */
    public function gradeConversionsDestroy(GradeConversion $gradeConversion)
    {
        $gradeConversion->delete();

        return back()->with('success', 'Konversi nilai berhasil dihapus.');
    }

    /**
     * Display a listing of staff (PMB, Kaprodi, Dekan, Rektor).
     */
    public function usersIndex()
    {
        $users = User::whereIn('role', [
            UserRole::PMB,
            UserRole::Kaprodi,
            UserRole::Dekan,
            UserRole::Rektor,
        ])->get();

        return view('kaprodi.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new staff user.
     */
    public function usersCreate()
    {
        return view('kaprodi.users.create');
    }

    /**
     * Store a newly created staff user in storage.
     */
    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in($this->staffRoleValues())],
            'prodi' => ['nullable', 'string', 'max:255'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'prodi' => $validated['prodi'],
        ]);

        return redirect()->route('kaprodi.users.index')->with('success', 'Data Pengguna berhasil disimpan.');
    }

    /**
     * Show the form for editing a staff user.
     */
    public function usersEdit(User $user)
    {
        $this->abortIfNotStaffUser($user);

        return view('kaprodi.users.edit', compact('user'));
    }

    /**
     * Update a staff user in storage.
     */
    public function usersUpdate(Request $request, User $user)
    {
        $this->abortIfNotStaffUser($user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in($this->staffRoleValues())],
            'prodi' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'prodi' => $validated['prodi'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('kaprodi.users.index')->with('success', 'Data Pengguna berhasil diperbarui dan disimpan.');
    }

    /**
     * Delete a staff user.
     */
    public function usersDestroy(Request $request, User $user)
    {
        $this->abortIfNotStaffUser($user);

        if ($request->user()->is($user)) {
            return back()->with('error', 'Data pengguna berhasil dihapus.');
        }

        $user->delete();

        return redirect()->route('kaprodi.users.index')->with('success', 'Data pengguna berhasil dihapus.');
    }

    /**
     * @return array<int, string>
     */
    private function staffRoleValues(): array
    {
        return [
            UserRole::PMB->value,
            UserRole::Kaprodi->value,
            UserRole::Dekan->value,
            UserRole::Rektor->value,
        ];
    }

    private function abortIfNotStaffUser(User $user): void
    {
        abort_unless(in_array($user->role->value, $this->staffRoleValues(), true), 404);
    }
}
