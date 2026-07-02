<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Conversion;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PMBController extends Controller
{
    /**
     * Display a listing of students (registered by PMB).
     */
    public function index()
    {
        $students = User::where('role', UserRole::Mahasiswa)
            ->with(['studentDetail.university', 'conversion'])
            ->get();

        return view('pmb.students.index', compact('students'));
    }

    /**
     * Show the form for registering a new student.
     */
    public function createStudent()
    {
        $universities = University::orderBy('name')->get();

        return view('pmb.students.create', compact('universities'));
    }

    /**
     * Store a newly registered student in storage.
     */
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'prodi_origin' => ['required', 'string', 'max:255'],
            'university_id' => ['required', 'exists:universities,id'],
            'graduation_date' => ['nullable', 'date'],
            'prodi' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('123456'), // Default password
            'role' => UserRole::Mahasiswa,
            'prodi' => $validated['prodi'] ?? 'Sistem Informasi',
        ]);

        $user->studentDetail()->create([
            'prodi_origin' => $validated['prodi_origin'],
            'university_id' => $validated['university_id'],
            'graduation_date' => $validated['graduation_date'],
        ]);

        return redirect()->route('pmb.students.index')->with('success', 'Data siswa berhasil ditambahkan. Kata sandi default 123456.');
    }

    /**
     * Show the form for editing a student.
     */
    public function editStudent(User $student)
    {
        $universities = University::orderBy('name')->get();
        $student->load('studentDetail');

        return view('pmb.students.edit', compact('student', 'universities'));
    }

    /**
     * Update student details.
     */
    public function updateStudent(Request $request, User $student)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $student->id],
            'prodi_origin' => ['required', 'string', 'max:255'],
            'university_id' => ['required', 'exists:universities,id'],
            'graduation_date' => ['nullable', 'date'],
            'prodi' => ['nullable', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:50'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:Laki-laki,Perempuan'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
        ]);

        $student->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'prodi' => $validated['prodi'],
        ]);

        $student->studentDetail()->updateOrCreate(
            ['user_id' => $student->id],
            [
                'university_id' => $validated['university_id'],
                'prodi_origin' => $validated['prodi_origin'],
                'graduation_date' => $validated['graduation_date'],
                'nim' => $validated['nim'],
                'place_of_birth' => $validated['place_of_birth'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'father_name' => $validated['father_name'],
                'mother_name' => $validated['mother_name'],
            ]
        );

        return redirect()->route('pmb.students.index')->with('success', 'Data siswa berhasil diperbarui dan disimpan.');
    }

    /**
     * Delete student.
     */
    public function destroyStudent(User $student)
    {
        $student->load('conversion');

        if ($student->conversion && $student->conversion->status !== 'rejected') {
            return redirect()->route('pmb.students.index')->with('error', 'Hanya mahasiswa yang ditolak yang dapat dihapus.');
        }

        // Deleting the student will cascade if foreign keys are set up, but let's be safe or just delete the user.
        // Usually, conversions and results should have ON DELETE CASCADE.
        $student->delete();

        return redirect()->route('pmb.students.index')->with('success', 'Berhasil menghapus mahasiswa.');
    }

    /**
     * List universities.
     */
    public function universitiesIndex()
    {
        $universities = University::all();

        return view('pmb.universities.index', compact('universities'));
    }

    /**
     * Show form to create university.
     */
    public function universitiesCreate()
    {
        return view('pmb.universities.create');
    }

    /**
     * Store new university.
     */
    public function universitiesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:universities'],
            'code' => ['nullable', 'string', 'max:50'],
        ]);

        University::create($validated);

        return redirect()->route('pmb.universities.index')->with('success', 'Data universitas berhasil disimpan.');
    }

    /**
     * Show edit form for university.
     */
    public function universitiesEdit(University $university)
    {
        return view('pmb.universities.edit', compact('university'));
    }

    /**
     * Update university.
     */
    public function universitiesUpdate(Request $request, University $university)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:universities,name,' . $university->id],
            'code' => ['nullable', 'string', 'max:50'],
        ]);

        $university->update($validated);

        return redirect()->route('pmb.universities.index')->with('success', 'Data universitas berhasil diperbarui dan disimpan.');
    }

    /**
     * Delete university.
     */
    public function universitiesDestroy(University $university)
    {
        $university->delete();

        return redirect()->route('pmb.universities.index')->with('success', 'Data universitas berhasil dihapus.');
    }

    /**
     * List all conversion requests for review.
     */
    public function conversionsIndex()
    {
        $conversions = Conversion::with('user')->orderBy('created_at', 'desc')->get();

        return view('pmb.conversions.index', compact('conversions'));
    }

    /**
     * Update conversion status.
     */
    public function conversionsUpdate(Request $request, Conversion $conversion)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:waiting,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $conversion->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->route('pmb.conversions.index')->with('success', 'Data tinjauan berhasil diperbarui dan disimpan.');
    }
}
