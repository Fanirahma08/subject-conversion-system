<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use Illuminate\Http\Request;

class DekanController extends Controller
{
    /**
     * Dekan Dashboard.
     */
    public function index()
    {
        return view('dekan.dashboard');
    }

    /**
     * List conversion requests waiting for Dekan's approval.
     */
    public function conversionsIndex()
    {
        $conversions = Conversion::with('user')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dekan.conversions.index', compact('conversions'));
    }

    /**
     * Show conversion details.
     */
    public function conversionsShow(Conversion $conversion)
    {
        $conversion->load(['user.studentDetail.university', 'results.source_subject', 'results.target_subject']);

        return view('dekan.conversions.show', compact('conversion'));
    }

    /**
     * Update conversion status (Approve to waiting_rektor or Reject).
     */
    public function conversionsUpdate(Request $request, Conversion $conversion)
    {
        if ($conversion->status !== 'waiting_dekan') {
            return redirect()->route('dekan.conversions.index')->with('error', 'Tindakan tidak valid.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:waiting_rektor,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $conversion->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);
        if ($validated['status'] === 'rejected') {
            return redirect()
                ->route('dekan.conversions.index')
                ->with('error', 'Permohonan konversi ditolak oleh Dekan.');
        }


        return redirect()->route('dekan.conversions.index')->with('success', 'Status konversi berhasil diperbarui dan diteruskan ke rektor.');
    }
}
