<?php

namespace Modules\Siparsi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentElement extends Model
{
    use HasFactory;

    protected $table = 'siparsi_assessment_elements';
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'assessment_element_id');
    }
}
