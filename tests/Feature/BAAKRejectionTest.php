<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Conversion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BAAKRejectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_baak_rejection_changes_status_to_waiting_kaprodi(): void
    {
        $baakUser = User::factory()->create([
            'role' => UserRole::BAAK,
        ]);

        $student = User::factory()->create([
            'role' => UserRole::Mahasiswa,
        ]);

        $conversion = Conversion::create([
            'user_id' => $student->id,
            'transcript_path' => 'transcripts/test.pdf',
            'status' => 'waiting_baak',
        ]);

        $response = $this->actingAs($baakUser)->put(route('baak.conversions.update', $conversion), [
            'status' => 'rejected',
            'notes' => 'Perlu revisi pada pemetaan matakuliah.',
        ]);

        $response->assertRedirect(route('baak.conversions.index'));
        $response->assertSessionHas('error', 'Permohonan konversi ditolak dan dikembalikan ke Kaprodi.');

        $this->assertDatabaseHas('conversions', [
            'id' => $conversion->id,
            'status' => 'waiting',
            'notes' => 'Perlu revisi pada pemetaan matakuliah.',
        ]);
    }
}
