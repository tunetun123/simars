<?php

namespace Modules\Sigap\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\User;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->code)) {
                // Generate a base from the first 6 alphanumeric chars of the name
                $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $category->name), 0, 6));
                
                // If name is too short, pad it
                if (strlen($base) < 3) {
                    $base = str_pad($base, 3, 'X');
                }
                
                $count = static::where('code', 'like', $base . '-%')->count() + 1;
                $category->code = $base . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
