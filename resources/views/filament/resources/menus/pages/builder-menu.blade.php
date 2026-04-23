<x-filament-panels::page>
    <div
        x-data="menuBuilder"
        x-init="init($refs.builder)"
        x-on:menu-builder-refresh.window="$nextTick(() => init($refs.builder))"
        class="grid gap-6 lg:grid-cols-[22rem_1fr]"
    >
        <div class="space-y-4">
            <section class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-white/10">
                    <h2 class="text-sm font-semibold text-gray-950 dark:text-white">Tambahkan Laman</h2>
                </div>

                <div class="max-h-72 space-y-2 overflow-y-auto px-4 py-4">
                    @forelse ($this->getAvailablePages() as $page)
                        <label class="flex items-start gap-3 rounded-lg border border-gray-200 px-3 py-2 text-sm dark:border-white/10">
                            <input type="checkbox" wire:model="selectedPages" value="{{ $page['id'] }}" class="mt-1 rounded border-gray-300">
                            <span>
                                <span class="block font-medium text-gray-950 dark:text-white">{{ $page['title'] }}</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $page['meta'] }}</span>
                            </span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada laman.</p>
                    @endforelse
                </div>

                <div class="border-t border-gray-200 px-4 py-3 dark:border-white/10">
                    <button type="button" wire:click="addPages" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500">
                        Tambahkan ke Menu
                    </button>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-white/10">
                    <h2 class="text-sm font-semibold text-gray-950 dark:text-white">Tambahkan Berita</h2>
                </div>

                <div class="max-h-72 space-y-2 overflow-y-auto px-4 py-4">
                    @forelse ($this->getAvailablePosts() as $post)
                        <label class="flex items-start gap-3 rounded-lg border border-gray-200 px-3 py-2 text-sm dark:border-white/10">
                            <input type="checkbox" wire:model="selectedPosts" value="{{ $post['id'] }}" class="mt-1 rounded border-gray-300">
                            <span>
                                <span class="block font-medium text-gray-950 dark:text-white">{{ $post['title'] }}</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $post['meta'] }}</span>
                            </span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada berita.</p>
                    @endforelse
                </div>

                <div class="border-t border-gray-200 px-4 py-3 dark:border-white/10">
                    <button type="button" wire:click="addPosts" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500">
                        Tambahkan ke Menu
                    </button>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-white/10">
                    <h2 class="text-sm font-semibold text-gray-950 dark:text-white">Tautan Khusus</h2>
                </div>

                <div class="space-y-4 px-4 py-4">
                    <div class="space-y-1.5">
                        <label for="menu-builder-custom-label" class="text-sm font-medium text-gray-950 dark:text-white">Label</label>
                        <x-filament::input.wrapper>
                            <x-filament::input
                                id="menu-builder-custom-label"
                                type="text"
                                wire:model="customLabel"
                                placeholder="Contoh: Kontak"
                            />
                        </x-filament::input.wrapper>
                    </div>

                    <div class="space-y-1.5">
                        <label for="menu-builder-custom-url" class="text-sm font-medium text-gray-950 dark:text-white">URL</label>
                        <x-filament::input.wrapper>
                            <x-filament::input
                                id="menu-builder-custom-url"
                                type="text"
                                wire:model="customUrl"
                                placeholder="/kontak atau https://example.com"
                            />
                        </x-filament::input.wrapper>
                    </div>

                    <div class="space-y-1.5">
                        <label for="menu-builder-custom-target" class="text-sm font-medium text-gray-950 dark:text-white">Target</label>
                        <x-filament::input.wrapper>
                            <x-filament::input.select id="menu-builder-custom-target" wire:model="customTarget">
                                <option value="_self">Tab yang sama</option>
                                <option value="_blank">Tab baru</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                </div>

                <div class="border-t border-gray-200 px-4 py-3 dark:border-white/10">
                    <x-filament::button type="button" wire:click="addCustomLink" icon="heroicon-o-plus">
                        Tambahkan ke Menu
                    </x-filament::button>
                </div>
            </section>
        </div>

        <section class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-5 py-4 dark:border-white/10">
                <div>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">{{ $this->getRecord()->name }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Geser item untuk mengubah urutan. Tarik item ke area anak untuk membuat submenu.
                    </p>
                </div>

                <button
                    type="button"
                    x-on:click="save($refs.tree)"
                    class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-500"
                >
                    Simpan Struktur
                </button>
            </div>

            <div x-ref="builder" data-menu-builder="{{ $this->getRecord()->getKey() }}" class="px-5 py-5">
                <div x-ref="tree">
                    <ol data-menu-list class="min-h-14 space-y-3 rounded-xl border border-dashed border-gray-300 bg-gray-50/70 p-3 dark:border-white/10 dark:bg-white/5">
                        @forelse ($this->getMenuItemsTree() as $item)
                            @include('filament.resources.menus.pages.partials.menu-builder-item', ['item' => $item])
                        @empty
                            <li class="rounded-lg border border-dashed border-gray-300 bg-white px-4 py-6 text-center text-sm text-gray-500 dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
                                Belum ada item menu. Tambahkan laman, berita, atau tautan khusus dari panel kiri.
                            </li>
                        @endforelse
                    </ol>
                </div>
            </div>
        </section>
    </div>

    @once
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('menuBuilder', () => ({
                    init(builder) {
                        this.$nextTick(() => this.bindSortables(builder))
                    },
                    bindSortables(builder) {
                        if (!builder || !window.Sortable) {
                            return
                        }

                        builder.querySelectorAll('[data-menu-list]').forEach((list) => {
                            if (list.dataset.sortableBound === '1') {
                                return
                            }

                            list.dataset.sortableBound = '1'

                            window.Sortable.create(list, {
                                group: `menu-builder-${builder.dataset.menuBuilder}`,
                                handle: '[data-menu-drag-handle]',
                                animation: 160,
                                fallbackOnBody: true,
                                swapThreshold: 0.65,
                                ghostClass: 'opacity-50',
                            })
                        })
                    },
                    save(tree) {
                        this.$wire.saveTree(this.collect(tree.querySelector(':scope > [data-menu-list]')))
                    },
                    collect(list) {
                        if (!list) {
                            return []
                        }

                        return Array.from(list.children)
                            .filter((item) => item.hasAttribute('data-menu-item'))
                            .map((item) => ({
                                id: item.dataset.menuItem,
                                children: this.collect(item.querySelector(':scope > div [data-menu-list]')),
                            }))
                    },
                }))
            })
        </script>
    @endonce
</x-filament-panels::page>
