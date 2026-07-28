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
            'status' => ['required', 'in:waiting_dekan,rejected,waiting'],
            'notes' => ['nullable', 'string'],
        ]);

        $statusToSave = $validated['status'];
        if (in_array($statusToSave, ['rejected', 'waiting'])) {
            $statusToSave = 'waiting';
        }

        $conversion->update([
            'status' => $statusToSave,
            'notes' => $validated['notes'],
        ]);

        if ($statusToSave === 'waiting') {
            return redirect()
                ->route('baak.conversions.index')
                ->with('error', 'Permohonan konversi ditolak dan dikembalikan ke Kaprodi.');
        }

        return redirect()->route('baak.conversions.index')->with('success', 'Status konversi berhasil diperbarui dan diteruskan ke Dekan.');
    }
}
