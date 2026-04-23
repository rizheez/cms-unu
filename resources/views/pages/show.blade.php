@extends('layouts.app')

@section('content')
    @php
        $publishedDate = $page->published_at?->translatedFormat('d F Y');
        $isEditorJsContent = is_array($page->content);
        $legacyContentClasses = 'max-w-none text-[#3a4a4d] [&_a]:font-semibold [&_a]:text-[#00a9b7] [&_blockquote]:my-9 [&_blockquote]:rounded-r-2xl [&_blockquote]:border-l-4 [&_blockquote]:border-[#00a9b7] [&_blockquote]:bg-[linear-gradient(135deg,#d8f7f2,#eefcfa)] [&_blockquote]:px-7 [&_blockquote]:py-6 [&_blockquote]:text-[#123136] [&_blockquote_cite]:mt-4 [&_blockquote_cite]:block [&_blockquote_cite]:text-sm [&_blockquote_cite]:font-bold [&_blockquote_cite]:not-italic [&_blockquote_cite]:text-[#00a9b7] [&_h2]:mb-5 [&_h2]:mt-12 [&_h2]:border-l-4 [&_h2]:border-[#ffc928] [&_h2]:pl-5 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-[#123136] [&_h3]:mb-4 [&_h3]:mt-9 [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-[#123136] [&_img]:my-9 [&_img]:rounded-2xl [&_li]:mb-2 [&_li]:leading-7 [&_li]:text-[#3a4a4d] [&_li::marker]:text-[#ffc928] [&_ol]:mb-7 [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:mb-5 [&_p]:text-[15px] [&_p]:leading-7 [&_p]:text-[#3a4a4d] [&_p:last-child]:mb-0 [&_strong]:font-bold [&_strong]:text-[#123136] [&_ul]:mb-7 [&_ul]:list-disc [&_ul]:pl-6';
    @endphp

    <section class="relative overflow-hidden bg-[linear-gradient(160deg,#09c3cd_0%,#005f69_100%)] px-4 py-12 text-white sm:px-6 lg:px-12 lg:py-14">
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:72px_72px]"></div>
        <div class="absolute -left-32 -top-32 h-[500px] w-[500px] rounded-full bg-[radial-gradient(circle,rgba(255,201,40,0.55),transparent_70%)]"></div>

        <div class="relative z-10 mx-auto max-w-4xl">
            <nav class="flex flex-wrap items-center gap-2 text-xs font-semibold text-white/60">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span>/</span>
                <span class="text-white/90">{{ $page->title }}</span>
            </nav>

            <div class="mt-7 inline-flex items-center rounded-full bg-[#ffc928] px-4 py-2 text-xs font-extrabold uppercase tracking-[0.06em] text-[#005f69]">
                Halaman
            </div>

            <h1 class="mt-6 font-display text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-[52px]">
                {{ $page->title }}
            </h1>

            @if ($publishedDate)
                <p class="mt-5 text-sm font-semibold text-white/70">
                    Diperbarui {{ $publishedDate }}
                </p>
            @endif
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-12 lg:py-12">
        <article @class(['editorjs-content' => $isEditorJsContent, $legacyContentClasses => ! $isEditorJsContent])>
            {!! app(\App\Services\EditorJsContentRenderer::class)->render($page->content) !!}
        </article>
    </section>
@endsection
