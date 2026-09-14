<?php

namespace Modules\Siparsi\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Modules\Siparsi\Models\DocumentGroup;
use Modules\Siparsi\Models\DocumentCategory;
use Modules\Siparsi\Models\AssessmentElement;
use Illuminate\Http\UploadedFile;
use App\Livewire\Siparsi\Documents;

class SiparsiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_document_group()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(\App\Livewire\Siparsi\Groups::class)
            ->set('name', 'Bab 1')
            ->call('store');

        $this->assertDatabaseHas('siparsi_document_groups', [
            'name' => 'Bab 1'
        ]);
    }

    public function test_it_can_upload_document_securely()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Storage::fake('local');

        $group = DocumentGroup::create(['name' => 'Bab 2', 'slug' => 'bab-2']);
        $category = DocumentCategory::create(['name' => 'Standar 1', 'slug' => 'standar-1', 'document_group_id' => $group->id]);
        $ep = AssessmentElement::create(['name' => 'EP 1', 'document_category_id' => $category->id]);

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        Livewire::test(Documents::class)
            ->set('title', 'Dokumen Bukti')
            ->set('assessment_element_id', $ep->id)
            ->set('point', 10)
            ->set('file', $file)
            ->call('store');

        $this->assertDatabaseHas('siparsi_documents', [
            'title' => 'Dokumen Bukti',
            'point' => 10,
            'assessment_element_id' => $ep->id,
            'uploaded_by' => $user->id
        ]);

        $document = \Modules\Siparsi\Models\Document::first();
        $this->assertTrue(Storage::disk('local')->exists($document->file_path));

        // Test secure download
        $response = $this->get(route('siparsi.documents.download', $document->id));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
