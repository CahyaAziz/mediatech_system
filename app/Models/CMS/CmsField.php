<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsField extends Model
{
    protected $table = "cms_fields";
    protected $primaryKey = "field_id";
    public $timestamps = false;

    protected $fillable = ["page_id", "field_key", "field_value", "media_id", "media_aspect_ratio", "media_fit_mode", "deleted_at", "deleted_by", "deletion_reason"];

    protected function casts(): array
    {
        return ["deleted_at" => "datetime"];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, "page_id", "page_id");
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(CmsMedia::class, "media_id", "media_id");
    }
}