<?php

namespace Modules\Sigap\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class Document extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'document_date' => 'date',
        ];
    }

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    protected static function booted()
    {
        static::creating(function ($document) {
            if (empty($document->reference_code)) {
                // Fetch the category to get its code
                $category = DocumentCategory::find($document->document_category_id);
                if ($category) {
                    $catCode = $category->code;
                    $count = static::where('document_category_id', $document->document_category_id)->count() + 1;
                    $document->reference_code = $catCode . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
                }
            }
        });

        static::deleted(function ($document) {
            if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
                Storage::disk('local')->delete($document->file_path);
            }
        });
    }
}
