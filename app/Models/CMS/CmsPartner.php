<?php

namespace App\Models\CMS;

use App\Models\Inventory\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPartner extends Model
{
    protected $table = "cms_partners";
    protected $primaryKey = "partner_id";
    public $timestamps = false;

    protected $fillable = ["media_id", "category_id", "partner_name", "website_url", "display_order", "is_active", "deleted_at", "deleted_by", "deletion_reason"];

    protected function casts(): array
    {
        return ["display_order" => "integer", "is_active" => "boolean", "deleted_at" => "datetime"];
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(CmsMedia::class, "media_id", "media_id");
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, "category_id", "category_id");
    }

    public function scopeActive($query)
    {
        return $query->where("is_active", true)->whereNull("deleted_at")->orderBy("display_order");
    }
}