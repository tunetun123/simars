<?php

namespace Modules\Siparsi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class DocumentGroup extends Model
{
    use HasFactory;

    protected $table = 'siparsi_document_groups';
    protected $guarded = ['id'];

    public function categories()
    {
        return $this->hasMany(DocumentCategory::class, 'document_group_id');
    }

    protected static function booted()
    {
        static::creating(function ($group) {
            if (empty($group->slug)) {
                $group->slug = Str::slug($group->name);
            }
        });
    }
}
