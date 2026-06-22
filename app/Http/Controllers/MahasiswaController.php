<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Models\StudentDetail;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * Mahasiswa Dashboard - Show conversion status.
     */
    public function index()
    {
        $user = Auth::user();
        $conversions = Conversion::where('user_id', $user->id)
            ->with(['reviewer', 'results.source_subject', 'results.target_subject'])
            ->orderBy('created_at', 'desc')
            ->get();

        $university = University::where('id', $user->studentDetail?->university_id)->first();

        return view('mahasiswa.dashboard', compact('conversions', 'university'));
    }

    /**
     * Show form to upload transcript.
     */
    public function conversionsCreate()
    {
        $conversion = Conversion::where('user_id', Auth::id())->first();

        return view('mahasiswa.conversions.create', compact('conversion'));
    }

    /**
     * Handle transcript upload.
     */
    public function conversionsStore(Request $request)
    {
        $request->validate([
            'transcript' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'registration_letter' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'ktp' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $user = Auth::user();
        $conversion = Conversion::where('user_id', $user->id)->first();

        // Prepare update data
        $data = [
            'status' => 'waiting',
            'notes' => null,
            'reviewed_by' => null,
        ];

        // Handle Transcript
        if ($request->hasFile('transcript')) {
            if ($conversion && $conversion->transcript_path) {
                Storage::disk('public')->delete($conversion->transcript_path);
            }
            $data['transcript_path'] = $this->storeFileUsingOriginalName(
                $request->file('transcript'),
                'transcripts',
                (string) $user->getKey()
            );
        }

        // Handle Registration Letter
        if ($request->hasFile('registration_letter')) {
            if ($conversion && $conversion->registration_letter_path) {
                Storage::disk('public')->delete($conversion->registration_letter_path);
            }
            $data['registration_letter_path'] = $this->storeFileUsingOriginalName(
                $request->file('registration_letter'),
                'registration_letters',
                (string) $user->getKey()
            );
        }

        // Handle KTP
        if ($request->hasFile('ktp')) {
            if ($conversion && $conversion->ktp_path) {
                Storage::disk('public')->delete($conversion->ktp_path);
            }
            $data['ktp_path'] = $this->storeFileUsingOriginalName(
                $request->file('ktp'),
                'ktp_files',
                (string) $user->getKey()
            );
        }

        Conversion::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Berkas pendaftaran berhasil diajukan. Mohon tunggu peninjauan.');
    }

    private function storeFileUsingOriginalName(UploadedFile $file, string $directory, string $userDirectory): string
    {
        $fileName = trim(str_replace(['/', '\\'], '-', $file->getClientOriginalName()));

        if ($fileName === '') {
            $fileName = $file->hashName();
        }

        return $file->storeAs("{$directory}/{$userDirectory}", $fileName, 'public');
    }

    /**
     * Show form to edit student profile.
     */
    public function profileEdit()
    {
        $user = Auth::user();
        $detail = $user->studentDetail ?? new StudentDetail;

        return view('mahasiswa.profile.edit', compact('user', 'detail'));
    }

    /**
     * Update student profile.
     */
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'nim' => ['nullable', 'string', 'max:20'],
            'place_of_birth' => ['nullable', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'father_name' => ['nullable', 'string', 'max:100'],
            'mother_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = Auth::user();

        $user->studentDetail()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'nim',
                'place_of_birth',
                'date_of_birth',
                'gender',
                'address',
                'phone',
                'father_name',
                'mother_name',
            ])
        );

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Profil berhasil diperbarui.');
    }
}
