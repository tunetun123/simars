<?php

namespace Modules\Siparsi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $table = 'siparsi_document_categories';
    protected $guarded = ['id'];

    public function group()
    {
        return $this->belongsTo(DocumentGroup::class, 'document_group_id');
    }

    public function assessmentElements()
    {
        return $this->hasMany(AssessmentElement::class, 'document_category_id');
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
