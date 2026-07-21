<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use Illuminate\Http\Request;

class WR1Controller extends Controller
{
    /**
     * WR1 Dashboard.
     */
    public function index()
    {
        return view('wr1.dashboard');
    }

    /**
     * List conversion requests waiting for WR1's approval.
     */
    public function conversionsIndex()
    {
        $conversions = Conversion::with('user')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('wr1.conversions.index', compact('conversions'));
    }

    /**
     * Show conversion details.
     */
    public function conversionsShow(Conversion $conversion)
    {
        $conversion->load(['user.studentDetail.university', 'results.source_subject', 'results.target_subject']);

        return view('wr1.conversions.show', compact('conversion'));
    }

    /**
     * Update conversion status (Approve to waiting_rektor or Reject).
     */
    public function conversionsUpdate(Request $request, Conversion $conversion)
    {
        if ($conversion->status !== 'waiting_wr1') {
            return redirect()->route('wr1.conversions.index')->with('error', 'Tindakan tidak valid.');
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
                ->route('wr1.conversions.index')
                ->with('error', 'Permohonan konversi ditolak oleh WR1.');
        }

        return redirect()->route('wr1.conversions.index')->with('success', 'Status konversi berhasil diperbarui dan diteruskan ke Rektor.');
    }
}
