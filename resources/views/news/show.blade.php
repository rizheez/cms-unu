@extends('layouts.app')

@section('content')
    @php
        $authorName = $post->author?->name ?? 'Redaksi UNU';
        $authorInitials = collect(explode(' ', $authorName))
            ->filter()
            ->map(
                fn(string $name): string => \Illuminate\Support\Str::upper(
                    \Illuminate\Support\Str::substr($name, 0, 1),
                ),
            )
            ->take(2)
            ->implode('');
        $authorInitials = $authorInitials !== '' ? $authorInitials : 'UN';
        $publishedDate = $post->published_at?->translatedFormat('d F Y') ?? 'Terbaru';
        $shareUrl = route('news.show', $post);
        $tagItems = collect(explode(',', (string) $post->meta_keywords))
            ->map(fn(string $keyword): string => trim($keyword))
            ->filter()
            ->take(6);
        $isEditorJsContent = is_array($post->content);
        $legacyContentClasses = 'max-w-none text-[#3a4a4d] [&_a]:font-semibold [&_a]:text-[#00a9b7] [&_blockquote]:my-9 [&_blockquote]:rounded-r-2xl [&_blockquote]:border-l-4 [&_blockquote]:border-[#00a9b7] [&_blockquote]:bg-[linear-gradient(135deg,#d8f7f2,#eefcfa)] [&_blockquote]:px-7 [&_blockquote]:py-6 [&_h2]:mb-5 [&_h2]:mt-12 [&_h2]:border-l-4 [&_h2]:border-[#ffc928] [&_h2]:pl-5 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-[#123136] [&_h3]:mb-4 [&_h3]:mt-9 [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-[#123136] [&_li]:mb-2 [&_li]:leading-7 [&_li]:text-[#3a4a4d] [&_li::marker]:text-[#ffc928] [&_p]:mb-5 [&_p]:text-[15px] [&_p]:leading-7 [&_p]:text-[#3a4a4d] [&_p:last-child]:mb-0 [&_strong]:font-bold [&_strong]:text-[#123136] [&_ul]:mb-7 [&_ul]:list-disc [&_ul]:pl-6';
    @endphp

    <section
        class="relative overflow-hidden bg-[linear-gradient(160deg,#09c3cd_0%,#005f69_100%)] px-4 pt-16 text-white sm:px-6 lg:px-12 lg:pt-20">
        <div
            class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:72px_72px]">
        </div>
        <div
            class="absolute -left-32 -top-32 h-[500px] w-[500px] rounded-full bg-[radial-gradient(circle,rgba(255,201,40,0.55),transparent_70%)]">
        </div>

        <div class="relative z-10 mx-auto max-w-4xl">
            <nav class="flex flex-wrap items-center gap-2 text-xs font-semibold text-white/60">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span>/</span>
                <a href="{{ route('news.index') }}" class="transition hover:text-white">Berita</a>
                <span>/</span>
                <span class="text-white/90">{{ $post->category?->name ?? 'Artikel' }}</span>
            </nav>

            <div
                class="mt-7 inline-flex items-center rounded-full bg-[#ffc928] px-4 py-2 text-xs font-extrabold uppercase tracking-[0.06em] text-[#005f69]">
                {{ $post->category?->name ?? 'Berita' }}
            </div>

            <h1 class="mt-6 font-display text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-[52px]">
                {{ $post->title }}
            </h1>

            @if ($post->excerpt)
                <p class="mt-5 max-w-3xl text-base leading-8 text-white/75">
                    {{ $post->excerpt }}
                </p>
            @endif

            <div class="mt-8 flex flex-wrap items-center gap-3 pb-9">
                <div class="flex items-center gap-3 rounded-full bg-white/12 py-2 pl-2 pr-4 backdrop-blur">
                    <span
                        class="grid h-8 w-8 place-items-center rounded-full bg-[#ffc928] font-display text-xs font-extrabold text-[#005f69]">
                        {{ $authorInitials }}
                    </span>
                    <span class="text-sm font-semibold text-white/85">{{ $authorName }}</span>
                </div>
                <span class="rounded-full bg-white/10 px-4 py-2 text-sm text-white/70">{{ $publishedDate }}</span>
                <span class="rounded-full bg-white/10 px-4 py-2 text-sm text-white/70">{{ $post->reading_time }} menit
                    baca</span>
                <span
                    class="rounded-full bg-white/10 px-4 py-2 text-sm text-white/70">{{ number_format((int) $post->views) }}
                    dilihat</span>
            </div>

            <figure class="relative z-10 overflow-hidden rounded-t-[20px] border border-white/15 bg-white/10">
                @if ($post->featured_image_url)
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                        class="h-full w-full object-cover ">
                @else
                    <div
                        class="grid h-[260px] place-items-center bg-[linear-gradient(135deg,rgba(255,255,255,0.12),rgba(255,255,255,0.04))] text-center sm:h-[340px] lg:h-[420px]">
                        <div>
                            <div class="font-display text-7xl font-extrabold text-white/20">UNU</div>
                            <p class="mt-3 text-sm text-white/40">Gambar berita belum tersedia</p>
                        </div>
                    </div>
                @endif
            </figure>
        </div>
    </section>

    <section
        class="mx-auto grid max-w-7xl gap-12 px-4 pb-16 pt-14 sm:px-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:px-12 lg:pb-20">
        <article class="min-w-0">
            <div @class([$legacyContentClasses => ! $isEditorJsContent])>
                {!! app(\App\Services\EditorJsContentRenderer::class)->render($post->content) !!}
            </div>

            <div class="mt-12 flex flex-wrap gap-2 border-t border-black/10 pt-8">
                @forelse ($tagItems as $tag)
                    <span class="rounded-full border border-black/10 px-4 py-2 text-xs font-semibold text-[#123136]/60">
                        #{{ $tag }}
                    </span>
                @empty
                    <span class="rounded-full border border-black/10 px-4 py-2 text-xs font-semibold text-[#123136]/60">
                        #{{ \Illuminate\Support\Str::slug($post->category?->name ?? 'berita') }}
                    </span>
                @endforelse
            </div>

            <div
                class="mt-8 flex flex-col gap-4 rounded-2xl bg-[linear-gradient(135deg,#d8f7f2,#eefcfa)] p-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="font-display text-lg font-bold text-[#123136]">Bagikan artikel ini</p>
                <div class="flex flex-wrap gap-3">
                    <a href="https://twitter.com/intent/tweet?url={{ rawurlencode($shareUrl) }}&text={{ rawurlencode($post->title) }}"
                        target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke X"
                        class="grid h-5 w-5 place-items-center rounded-full bg-white transition hover:-translate-y-0.5">
                        <img src="https://cdn.simpleicons.org/x/111111" alt="" class="h-4 w-4" loading="lazy">
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($shareUrl) }}"
                        rel="noopener noreferrer" target="_blank" aria-label="Bagikan ke Facebook"
                        class="grid h-5 w-5 place-items-center rounded-full bg-white transition hover:-translate-y-0.5">
                        <img src="https://cdn.simpleicons.org/facebook/1877F2" alt="" class="h-4 w-4"
                            loading="lazy">
                    </a>
                    <a href="https://wa.me/?text={{ rawurlencode($post->title . ' ' . $shareUrl) }}" target="_blank"
                        rel="noopener noreferrer" aria-label="Bagikan ke WhatsApp"
                        class="grid h-5 w-5 place-items-center rounded-full bg-white transition hover:-translate-y-0.5">
                        <img src="https://cdn.simpleicons.org/whatsapp/25D366" alt="" class="h-4 w-4"
                            loading="lazy">
                    </a>
                </div>
            </div>

            <div class="mt-10 flex gap-5 rounded-[20px] border border-black/10 bg-white p-7 shadow-sm">
                <div
                    class="grid h-16 w-16 shrink-0 place-items-center rounded-full bg-[#ffc928] font-display text-xl font-extrabold text-[#005f69]">
                    {{ $authorInitials }}
                </div>
                <div>
                    <h2 class="font-display text-lg font-bold text-[#123136]">{{ $authorName }}</h2>
                    <p class="mt-1 text-sm font-semibold text-[#00a9b7]">Redaksi Universitas Nahdlatul Ulama</p>
                    <p class="mt-3 text-sm leading-7 text-[#123136]/65">
                        Menyajikan informasi resmi seputar kegiatan akademik, kemahasiswaan, riset, dan kolaborasi kampus.
                    </p>
                </div>
            </div>

            <section id="comments" class="mt-10 rounded-[20px] border border-black/10 bg-white p-6 shadow-sm sm:p-7">
                <div class="flex flex-col gap-2 border-b border-black/10 pb-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.08em] text-[#00a9b7]">Diskusi</p>
                        <h2 class="mt-2 font-display text-2xl font-bold text-[#123136]">
                            Komentar
                        </h2>
                    </div>
                    <p class="text-sm font-semibold text-[#123136]/50">
                        {{ $postComments->count() }} komentar
                    </p>
                </div>

                @if (session('comment_success'))
                    <div
                        class="mt-5 rounded-2xl border border-[#00a9b7]/20 bg-[#00a9b7]/10 px-4 py-3 text-sm font-semibold text-[#005f69]">
                        {{ session('comment_success') }}
                    </div>
                @endif

                <div class="mt-6 space-y-4">
                    @forelse ($postComments as $comment)
                        <article class="rounded-2xl border border-black/10 bg-[#f4fffc] p-5">
                            <div class="flex items-center gap-3">
                                <span
                                    class="grid h-10 w-10 place-items-center rounded-full bg-[#ffc928] font-display text-sm font-extrabold text-[#005f69]">
                                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($comment->name ?? 'P', 0, 1)) }}
                                </span>
                                <div>
                                    <h3 class="font-display text-base font-bold text-[#123136]">
                                        {{ $comment->name ?? 'Pengunjung' }}
                                    </h3>
                                    <p class="text-xs font-semibold text-[#123136]/45">
                                        {{ $comment->created_at?->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <p class="mt-4 whitespace-pre-line break-words text-sm leading-7 text-[#3a4a4d]">
                                {{ $comment->body }}
                            </p>
                        </article>
                    @empty
                        <div
                            class="rounded-2xl border border-dashed border-black/10 bg-[#f4fffc] p-5 text-sm text-[#123136]/60">
                            Belum ada komentar. Jadilah yang pertama berdiskusi.
                        </div>
                    @endforelse
                </div>

                <form method="POST" action="{{ route('news.comments.store', $post) }}"
                    class="mt-7 rounded-2xl bg-[linear-gradient(135deg,#d8f7f2,#eefcfa)] p-5">
                    @csrf
                    <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off">

                    <div class="grid gap-4">
                        <label class="grid gap-2">
                            <span class="text-sm font-bold text-[#123136]">Nama <span
                                    class="font-medium text-[#123136]/45">(opsional)</span></span>
                            <input type="text" name="name" value="{{ old('name') }}" maxlength="80"
                                class="min-h-12 rounded-xl border border-black/10 bg-white px-4 text-sm text-[#123136] outline-none transition focus:border-[#00a9b7] focus:ring-2 focus:ring-[#00a9b7]/15"
                                placeholder="Nama kamu">
                            @error('name')
                                <span class="text-xs font-semibold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="grid gap-2">
                            <span class="text-sm font-bold text-[#123136]">Komentar</span>
                            <textarea name="body" rows="5" maxlength="1200" required
                                class="rounded-xl border border-black/10 bg-white px-4 py-3 text-sm leading-7 text-[#123136] outline-none transition focus:border-[#00a9b7] focus:ring-2 focus:ring-[#00a9b7]/15"
                                placeholder="Tulis komentar dengan bahasa yang santun...">{{ old('body') }}</textarea>
                            @error('body')
                                <span class="text-xs font-semibold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>

                        @error('website')
                            <span class="text-xs font-semibold text-red-600">{{ $message }}</span>
                        @enderror

                        <button type="submit"
                            class="inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-[#ffc928] px-5 text-sm font-extrabold text-[#005f69] shadow-[5px_5px_0_#005f69] transition hover:-translate-y-0.5 sm:w-fit">
                            Kirim Komentar
                        </button>
                    </div>
                </form>
            </section>
        </article>

        <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
            <div class="rounded-[20px] border border-black/10 bg-white p-6 shadow-sm">
                <h2 class="border-b-2 border-[#ffc928] pb-4 font-display text-base font-bold text-[#123136]">Info Artikel
                </h2>
                <div class="mt-4 divide-y divide-black/10">
                    <div class="flex items-center gap-3 py-3">
                        <span
                            class="grid h-9 w-9 place-items-center rounded-xl bg-[#ffc928]/20 text-sm font-bold text-[#005f69]">V</span>
                        <div>
                            <p class="font-display text-lg font-bold leading-none text-[#123136]">
                                {{ number_format((int) $post->views) }}</p>
                            <p class="mt-1 text-xs text-[#123136]/45">Total pembaca</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 py-3">
                        <span
                            class="grid h-9 w-9 place-items-center rounded-xl bg-[#00a9b7]/10 text-sm font-bold text-[#00a9b7]">T</span>
                        <div>
                            <p class="font-display text-lg font-bold leading-none text-[#123136]">
                                {{ $post->reading_time }}
                                mnt</p>
                            <p class="mt-1 text-xs text-[#123136]/45">Estimasi baca</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 py-3">
                        <span
                            class="grid h-9 w-9 place-items-center rounded-xl bg-[#ff9f1c]/10 text-sm font-bold text-[#ff9f1c]">K</span>
                        <div>
                            <p class="font-display text-lg font-bold leading-none text-[#123136]">
                                {{ $post->category?->name ?? 'Berita' }}</p>
                            <p class="mt-1 text-xs text-[#123136]/45">Kategori</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 py-3">
                        <span
                            class="grid h-9 w-9 place-items-center rounded-xl bg-[#005f69]/10 text-sm font-bold text-[#005f69]">D</span>
                        <div>
                            <p class="font-display text-lg font-bold leading-none text-[#123136]">{{ $publishedDate }}</p>
                            <p class="mt-1 text-xs text-[#123136]/45">Tanggal terbit</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="rounded-[20px] border border-black/10 bg-white p-6 shadow-sm">
                <h2 class="border-b-2 border-[#ffc928] pb-4 font-display text-base font-bold text-[#123136]">Berita Terbaru
                </h2>
                <div class="mt-4 divide-y divide-black/10">
                    @forelse ($latestPosts as $latestPost)
                        <a href="{{ route('news.show', $latestPost) }}" class="block py-4 transition hover:opacity-70">
                            <span
                                class="inline-flex rounded-full bg-[#005f69]/10 px-2 py-1 text-[9px] font-extrabold uppercase text-[#005f69]">
                                {{ $latestPost->category?->name ?? 'Berita' }}
                            </span>
                            <span class="mt-2 block font-display text-sm font-bold leading-5 text-[#123136]">
                                {{ $latestPost->title }}
                            </span>
                            <span class="mt-1 block text-xs text-[#123136]/40">
                                {{ $latestPost->published_at?->translatedFormat('d M Y') ?? 'Terbaru' }}
                            </span>
                        </a>
                    @empty
                        <p class="py-4 text-sm text-[#123136]/60">Belum ada berita terbaru.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </section>

    @if ($relatedPosts->isNotEmpty())
        <section class="bg-[#d8f7f2] px-4 py-16 sm:px-6 lg:px-12">
            <div class="mx-auto max-w-7xl">
                <h2 class="font-display text-3xl font-bold text-[#123136]">Baca Juga</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach ($relatedPosts as $relatedPost)
                        <a href="{{ route('news.show', $relatedPost) }}"
                            class="group overflow-hidden rounded-[20px] border border-black/10 bg-white transition hover:-translate-y-1 hover:shadow-xl">
                            <div
                                class="grid h-48 place-items-center bg-[linear-gradient(135deg,#f4fffc,#b2ede6)] font-display text-4xl font-extrabold text-[#005f69]/25">
                                @if ($relatedPost->featured_image_url)
                                    <img src="{{ $relatedPost->featured_image_url }}" alt="{{ $relatedPost->title }}"
                                        class="h-full w-full object-cover">
                                @else
                                    UNU
                                @endif
                            </div>
                            <div class="p-6">
                                <span
                                    class="inline-flex rounded-full bg-[#00a9b7]/10 px-3 py-1 text-[10px] font-bold uppercase text-[#00a9b7]">
                                    {{ $relatedPost->category?->name ?? 'Berita' }}
                                </span>
                                <h3 class="mt-4 font-display text-base font-bold leading-6 text-[#123136]">
                                    {{ $relatedPost->title }}
                                </h3>
                                <p class="mt-3 text-sm leading-6 text-[#123136]/60">
                                    {{ \Illuminate\Support\Str::limit((string) $relatedPost->excerpt, 120) }}
                                </p>
                                <span class="mt-5 inline-flex text-sm font-bold text-[#00a9b7]">Baca Selengkapnya</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
