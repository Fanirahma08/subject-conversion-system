<?php

namespace Tests\Feature;

use App\Http\Controllers\MahasiswaController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use ReflectionMethod;
use Tests\TestCase;

class MahasiswaConversionUploadTest extends TestCase
{
    public function test_uploaded_file_is_stored_with_original_file_name_inside_user_directory(): void
    {
        Storage::fake('public');

        $controller = new MahasiswaController;
        $storeFile = new ReflectionMethod($controller, 'storeFileUsingOriginalName');

        $path = $storeFile->invoke(
            $controller,
            UploadedFile::fake()->create('My Real Transcript.pdf', 100, 'application/pdf'),
            'transcripts',
            '123'
        );

        $this->assertSame('transcripts/123/My Real Transcript.pdf', $path);
        Storage::disk('public')->assertExists($path);
    }
}
