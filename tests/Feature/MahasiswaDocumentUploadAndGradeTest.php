<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Conversion;
use App\Models\GradeConversion;
use App\Models\User;
use Database\Seeders\GradeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MahasiswaDocumentUploadAndGradeTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_can_upload_all_required_and_optional_documents(): void
    {
        Storage::fake('public');

        $student = User::factory()->create([
            'role' => UserRole::Mahasiswa,
        ]);

        $transcript = UploadedFile::fake()->create('transkrip_nilai.pdf', 500, 'application/pdf');
        $transferLetter = UploadedFile::fake()->create('surat_keterangan_pindah.pdf', 500, 'application/pdf');
        $accreditation = UploadedFile::fake()->create('akreditasi_prodi.pdf', 500, 'application/pdf');
        $registrationLetter = UploadedFile::fake()->create('surat_pendaftaran.pdf', 500, 'application/pdf');
        $ktp = UploadedFile::fake()->create('ktp_mahasiswa.jpg', 300, 'image/jpeg');

        $response = $this->actingAs($student)->post(route('mahasiswa.conversions.store'), [
            'transcript' => $transcript,
            'transfer_letter' => $transferLetter,
            'accreditation' => $accreditation,
            'registration_letter' => $registrationLetter,
            'ktp' => $ktp,
        ]);

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $response->assertSessionHas('success');

        $conversion = Conversion::where('user_id', $student->id)->first();
        $this->assertNotNull($conversion);
        $this->assertNotNull($conversion->transcript_path);
        $this->assertNotNull($conversion->transfer_letter_path);
        $this->assertNotNull($conversion->accreditation_path);
        $this->assertNotNull($conversion->registration_letter_path);
        $this->assertNotNull($conversion->ktp_path);

        Storage::disk('public')->assertExists($conversion->transcript_path);
        Storage::disk('public')->assertExists($conversion->transfer_letter_path);
        Storage::disk('public')->assertExists($conversion->accreditation_path);
        Storage::disk('public')->assertExists($conversion->registration_letter_path);
        Storage::disk('public')->assertExists($conversion->ktp_path);
    }

    public function test_grade_seeder_has_no_cd_and_maps_c_minus_to_c(): void
    {
        $this->seed(GradeSeeder::class);

        // Assert C- converts to C
        $cMinus = GradeConversion::where('origin_grade', 'C-')->first();
        $this->assertNotNull($cMinus);
        $this->assertSame('C', $cMinus->internal_grade);

        // Assert no CD grade exists in grade_conversions
        $hasCd = GradeConversion::where('internal_grade', 'CD')->orWhere('origin_grade', 'CD')->exists();
        $this->assertFalse($hasCd);
    }
}
