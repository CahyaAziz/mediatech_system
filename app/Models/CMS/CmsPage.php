<?php

namespace App\Models\CMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends Model
{
    protected $table = "cms_pages";
    protected $primaryKey = "page_id";
    public $timestamps = false;

    protected $fillable = ["slug", "title", "status", "updated_by", "updated_at", "deleted_at", "deleted_by", "deletion_reason"];

    protected function casts(): array
    {
        return ["updated_at" => "datetime", "deleted_at" => "datetime"];
    }

    public function fields(): HasMany
    {
        return $this->hasMany(CmsField::class, "page_id", "page_id");
    }

    public function items(): HasMany
    {
        return $this->hasMany(CmsItem::class, "page_id", "page_id");
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "updated_by", "user_id");
    }

    public function scopePublished($query)
    {
        return $query->where("status", "Published")->whereNull("deleted_at");
    }
}