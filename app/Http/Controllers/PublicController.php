<?php

namespace App\Http\Controllers;

use App\Models\CMS\CmsItem;
use App\Models\CMS\CmsPage;
use App\Models\CMS\CmsPartner;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function index(): View
    {
        $page = CmsPage::published()->where("slug", "about-us")->first()
            ?? CmsPage::published()->orderBy("page_id")->first();

        $fields = $page ? $page->fields()->whereNull("deleted_at")->pluck("field_value", "field_key") : collect();

        $items = $page ? $page->items()->active()->orderBy("sort_order")->with("media")->get()->groupBy("section_key") : collect();

        $partners = CmsPartner::active()->with("media")->get();

        return view("public.home", [
            "page" => $page,
            "fields" => $fields,
            "items" => $items,
            "partners" => $partners,
        ]);
    }

    public function show(string $slug): View
    {
        $page = CmsPage::published()->where("slug", $slug)->firstOrFail();

        $fields = $page->fields()->whereNull("deleted_at")->pluck("field_value", "field_key");
        $items = $page->items()->active()->orderBy("sort_order")->with("media")->get()->groupBy("section_key");

        return view("public.page", [
            "page" => $page,
            "fields" => $fields,
            "items" => $items,
        ]);
    }
}
