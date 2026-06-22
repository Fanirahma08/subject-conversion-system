<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Models\Subject;
use Illuminate\Http\Request;

class RektorController extends Controller
{
    /**
     * Rektor Dashboard.
     */
    public function index()
    {
        return view('rektor.dashboard');
    }

    /**
     * List conversion requests waiting for Rektor's approval.
     */
    public function conversionsIndex()
    {
        $conversions = Conversion::with('user')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('rektor.conversions.index', compact('conversions'));
    }

    /**
     * Show conversion details.
     */
    public function conversionsShow(Conversion $conversion)
    {
        $conversion->load(['user.studentDetail.university', 'results.source_subject', 'results.target_subject']);

        return view('rektor.conversions.show', compact('conversion'));
    }

    /**
     * Update conversion status (Approve/Issue SK or Reject).
     */
    public function conversionsUpdate(Request $request, Conversion $conversion)
    {
        if ($conversion->status !== 'waiting_rektor') {
            return redirect()->route('rektor.conversions.index')->with('error', 'Tindakan tidak valid.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'notes' => ['nullable', 'string'],
            'decree_number' => ['required_if:status,approved', 'nullable', 'string', 'max:255'],
            'rector_name' => ['required_if:status,approved', 'nullable', 'string', 'max:255'],
            'rector_nidn' => ['required_if:status,approved', 'nullable', 'string', 'max:50'],
        ]);

        if ($validated['status'] === 'approved') {
            $now = now();
            $year = $now->year;
            $month = $now->month;
            // Academic year: starts in Sept (9)
            $academicYear = $month >= 9 ? "$year/" . ($year + 1) : ($year - 1) . "/$year";

            // Count total internal SKS (target subjects)
            $totalTargetSks = Subject::where('university_id', null)->sum('sks');

            $conversion->update([
                'status' => 'approved',
                'notes' => $validated['notes'],
                'decree_number' => $validated['decree_number'],
                'decree_date' => $now->toDateString(),
                'academic_year' => $academicYear,
                'rector_name' => $validated['rector_name'] ?? 'Prof. Dr. Ing. Ir. H. Hairul Abral',
                'rector_nidn' => $validated['rector_nidn'] ?? '0017086612',
                'graduation_total_sks' => $totalTargetSks,
            ]);
        } else {
            $conversion->update([
                'status' => 'rejected',
                'notes' => $validated['notes'],
            ]);
        }

        if ($validated['status'] === 'approved') {
            return redirect()->route('rektor.conversions.index')
                ->with('success', 'Status konversi berhasil diperbarui (SK Terbit).');
        }

        return redirect()->route('rektor.conversions.index')
            ->with('error', 'Status konversi berhasil diperbarui (SK Tidak Terbit).');
    }
}
