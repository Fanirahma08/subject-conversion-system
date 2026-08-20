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

    public function test_baak_and_wr1_can_access_conversion_pdf(): void
    {
        $baakUser = User::factory()->create([
            'role' => UserRole::BAAK,
        ]);

        $wr1User = User::factory()->create([
            'role' => UserRole::WR1,
        ]);

        $student = User::factory()->create([
            'role' => UserRole::Mahasiswa,
        ]);

        $conversion = Conversion::create([
            'user_id' => $student->id,
            'transcript_path' => 'transcripts/test.pdf',
            'status' => 'waiting_baak',
        ]);

        // BAAK can access PDF
        $responseBaak = $this->actingAs($baakUser)->get(route('conversions.pdf', $conversion));
        $responseBaak->assertOk();

        // WR1 can access PDF
        $responseWr1 = $this->actingAs($wr1User)->get(route('conversions.pdf', $conversion));
        $responseWr1->assertOk();
    }
}
