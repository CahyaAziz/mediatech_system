@extends("layouts.public")

@section("title", $page->title ?? "Meditech")

@section("content")
    <div class="mx-auto max-w-[1180px] px-5 py-[120px]">
        <h1 class="font-heading text-4xl font-semibold text-primary-500 mb-6">{{ $page->title }}</h1>
        @foreach($fields as $key => $value)
            @if(Str::startsWith($key, "page_intro"))
                <p class="text-neutral-600 leading-relaxed mb-6">{{ $value }}</p>
            @endif
        @endforeach
        @foreach($items as $section => $sectionItems)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
                @foreach($sectionItems as $item)
                    <article class="rounded-[20px] border border-neutral-200 bg-white p-6">
                        @if($item->media)
                            <img src="{{ $item->media->file_path }}" alt="{{ $item->title }}" class="aspect-[16/10] w-full rounded-[12px] object-cover mb-4">
                        @endif
                        <span class="mb-2 inline-block rounded-full bg-secondary-500 px-2.5 py-0.5 text-xs font-semibold text-neutral-900">{{ $item->section_key }}</span>
                        <h3 class="font-heading text-xl font-semibold text-primary-500 mb-2">{{ $item->title }}</h3>
                        @if($item->subtitle)
                            <p class="text-sm font-medium text-neutral-700 mb-1">{{ $item->subtitle }}</p>
                        @endif
                        <p class="text-sm text-neutral-600">{{ $item->description }}</p>
                    </article>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
