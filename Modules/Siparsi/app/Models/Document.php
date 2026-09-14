<?php

namespace Modules\Siparsi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class Document extends Model
{
    use HasFactory;

    protected $table = 'siparsi_documents';
    protected $guarded = ['id'];

    public function assessmentElement()
    {
        return $this->belongsTo(AssessmentElement::class, 'assessment_element_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    protected static function booted()
    {
        static::deleting(function ($document) {
            if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
                Storage::disk('local')->delete($document->file_path);
            }
        });
    }
}
