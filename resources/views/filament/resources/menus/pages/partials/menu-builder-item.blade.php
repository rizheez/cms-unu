<li data-menu-item="{{ $item->id }}" class="space-y-2">
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="flex items-center gap-3 px-4 py-3">
            <button
                type="button"
                data-menu-drag-handle
                class="cursor-grab rounded-md border border-gray-200 px-2 py-1 text-xs font-semibold text-gray-500 hover:bg-gray-50 active:cursor-grabbing dark:border-white/10 dark:hover:bg-white/5"
                title="Geser item"
            >
                ::
            </button>

            <div class="min-w-0 flex-1">
                <div class="truncate text-sm font-semibold text-gray-950 dark:text-white">{{ $item->label }}</div>
                <div class="truncate text-xs text-gray-500 dark:text-gray-400">
                    {{ $item->page?->title ?? $item->url ?? 'Tautan kosong' }}
                </div>
            </div>

            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-white/10 dark:text-gray-300">
                {{ $item->page_id ? 'Laman' : 'URL' }}
            </span>

            <a
                href="{{ \App\Filament\Resources\MenuItems\MenuItemResource::getUrl('edit', ['record' => $item]) }}"
                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/5"
            >
                Edit
            </a>

            <button
                type="button"
                wire:click="deleteItem({{ $item->id }})"
                wire:confirm="Hapus item menu ini?"
                class="rounded-lg border border-danger-200 px-3 py-2 text-xs font-semibold text-danger-600 hover:bg-danger-50 dark:border-danger-500/30 dark:hover:bg-danger-500/10"
            >
                Hapus
            </button>
        </div>

        <div class="border-t border-gray-100 px-4 py-3 dark:border-white/10">
            <ol data-menu-list class="min-h-10 space-y-2 rounded-lg border border-dashed border-gray-200 bg-gray-50/80 p-2 dark:border-white/10 dark:bg-white/5">
                @foreach ($item->childrenRecursive as $child)
                    @include('filament.resources.menus.pages.partials.menu-builder-item', ['item' => $child])
                @endforeach
            </ol>
        </div>
    </div>
</li>
