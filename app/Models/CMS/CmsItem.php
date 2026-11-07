<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsItem extends Model
{
    protected $table = "cms_items";
    protected $primaryKey = "item_id";
    public $timestamps = false;

    protected $fillable = ["page_id", "media_id", "section_key", "title", "subtitle", "item_value", "meta", "description", "sort_order", "is_active", "media_aspect_ratio", "media_fit_mode", "deleted_at", "deleted_by", "deletion_reason"];

    protected function casts(): array
    {
        return ["sort_order" => "integer", "is_active" => "boolean", "deleted_at" => "datetime"];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, "page_id", "page_id");
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(CmsMedia::class, "media_id", "media_id");
    }

    public function scopeActive($query)
    {
        return $query->where("is_active", true)->whereNull("deleted_at");
    }
}