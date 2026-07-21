<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use Illuminate\Http\Request;

class BAAKController extends Controller
{
    /**
     * BAAK Dashboard.
     */
    public function index()
    {
        return view('baak.dashboard');
    }

    /**
     * List conversion requests waiting for BAAK's approval.
     */
    public function conversionsIndex()
    {
        $conversions = Conversion::with('user')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('baak.conversions.index', compact('conversions'));
    }

    /**
     * Show conversion details.
     */
    public function conversionsShow(Conversion $conversion)
    {
        $conversion->load(['user.studentDetail.university', 'results.source_subject', 'results.target_subject']);

        return view('baak.conversions.show', compact('conversion'));
    }

    /**
     * Update conversion status (Approve to waiting_dekan or Reject).
     */
    public function conversionsUpdate(Request $request, Conversion $conversion)
    {
        if ($conversion->status !== 'waiting_baak') {
            return redirect()->route('baak.conversions.index')->with('error', 'Tindakan tidak valid.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:waiting_dekan,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $conversion->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        if ($validated['status'] === 'rejected') {
            return redirect()
                ->route('baak.conversions.index')
                ->with('error', 'Permohonan konversi ditolak oleh BAAK.');
        }

        return redirect()->route('baak.conversions.index')->with('success', 'Status konversi berhasil diperbarui dan diteruskan ke Dekan.');
    }
}
