<?php

namespace App\Models\CMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsMedia extends Model
{
    protected $table = "cms_media";
    protected $primaryKey = "media_id";
    public $timestamps = false;

    protected $fillable = ["file_name", "file_path", "media_type", "alt_text", "file_size_kb", "width_px", "height_px", "uploaded_by", "uploaded_at", "deleted_at", "deleted_by", "deletion_reason"];

    protected function casts(): array
    {
        return ["file_size_kb" => "integer", "width_px" => "integer", "height_px" => "integer", "uploaded_at" => "datetime", "deleted_at" => "datetime"];
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "uploaded_by", "user_id");
    }

    public function fields(): HasMany
    {
        return $this->hasMany(CmsField::class, "media_id", "media_id");
    }

    public function items(): HasMany
    {
        return $this->hasMany(CmsItem::class, "media_id", "media_id");
    }
}