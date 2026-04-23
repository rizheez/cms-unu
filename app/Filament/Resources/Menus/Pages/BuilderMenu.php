<?php

declare(strict_types=1);

namespace App\Filament\Resources\Menus\Pages;

use App\Filament\Resources\Menus\MenuResource;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page as ResourcePage;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BuilderMenu extends ResourcePage
{
    use InteractsWithRecord;

    protected static string $resource = MenuResource::class;

    protected string $view = 'filament.resources.menus.pages.builder-menu';

    protected static ?string $title = 'Builder Menu';

    /**
     * @var array<int|string>
     */
    public array $selectedPages = [];

    /**
     * @var array<int|string>
     */
    public array $selectedPosts = [];

    public ?string $customLabel = null;

    public ?string $customUrl = null;

    public string $customTarget = '_self';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Edit Info Menu')
                ->icon('heroicon-o-pencil-square')
                ->url(fn (): string => MenuResource::getUrl('edit', ['record' => $this->getRecord()])),
        ];
    }

    public function addPages(): void
    {
        $pageIds = collect($this->selectedPages)
            ->map(fn (int|string $pageId): int => (int) $pageId)
            ->filter()
            ->unique()
            ->values();

        if ($pageIds->isEmpty()) {
            $this->notifyWarning('Pilih minimal satu laman.');

            return;
        }

        Page::query()
            ->whereIn('id', $pageIds)
            ->orderBy('title')
            ->get(['id', 'title'])
            ->each(fn (Page $page): MenuItem => $this->menu()->items()->create([
                'page_id' => $page->id,
                'label' => $page->title,
                'target' => '_self',
            ]));

        $this->selectedPages = [];

        $this->notifySuccess('Laman ditambahkan ke menu.');
    }

    public function addPosts(): void
    {
        $postIds = collect($this->selectedPosts)
            ->map(fn (int|string $postId): int => (int) $postId)
            ->filter()
            ->unique()
            ->values();

        if ($postIds->isEmpty()) {
            $this->notifyWarning('Pilih minimal satu berita.');

            return;
        }

        Post::query()
            ->whereIn('id', $postIds)
            ->orderBy('title')
            ->get(['id', 'title', 'slug'])
            ->each(fn (Post $post): MenuItem => $this->menu()->items()->create([
                'label' => $post->title,
                'url' => route('news.show', ['post' => $post->slug], absolute: false),
                'target' => '_self',
            ]));

        $this->selectedPosts = [];

        $this->notifySuccess('Berita ditambahkan ke menu.');
    }

    public function addCustomLink(): void
    {
        $label = trim((string) $this->customLabel);
        $url = trim((string) $this->customUrl);

        if ($label === '' || $url === '') {
            $this->notifyWarning('Label dan URL tautan khusus wajib diisi.');

            return;
        }

        $this->menu()->items()->create([
            'label' => $label,
            'url' => $url,
            'target' => $this->customTarget === '_blank' ? '_blank' : '_self',
        ]);

        $this->customLabel = null;
        $this->customUrl = null;
        $this->customTarget = '_self';

        $this->notifySuccess('Tautan khusus ditambahkan ke menu.');
    }

    public function deleteItem(int $itemId): void
    {
        $this->menu()
            ->items()
            ->whereKey($itemId)
            ->firstOrFail()
            ->delete();

        $this->notifySuccess('Item menu dihapus.');
    }

    /**
     * @param  array<int, array{id: int|string, children?: array<int, mixed>}>  $items
     *
     * @throws ValidationException
     */
    public function saveTree(array $items): void
    {
        $allowedItemIds = $this->menu()
            ->items()
            ->pluck('id')
            ->map(fn (int|string $itemId): int => (int) $itemId)
            ->all();

        $orderedItems = collect($this->flattenTree($items))
            ->values();

        $orderedItemIds = $orderedItems
            ->pluck('id')
            ->sort()
            ->values()
            ->all();

        $expectedItemIds = collect($allowedItemIds)
            ->sort()
            ->values()
            ->all();

        if ($orderedItemIds !== $expectedItemIds) {
            throw ValidationException::withMessages([
                'items' => 'Struktur menu tidak valid. Muat ulang halaman lalu coba lagi.',
            ]);
        }

        DB::transaction(function () use ($orderedItems): void {
            $orderedItems->each(function (array $item): void {
                MenuItem::query()
                    ->where('menu_id', $this->menu()->getKey())
                    ->whereKey($item['id'])
                    ->update([
                        'parent_id' => $item['parent_id'],
                        'order' => $item['order'],
                    ]);
            });
        });

        $this->notifySuccess('Struktur menu disimpan.');
    }

    public function getMenuItemsTree(): EloquentCollection
    {
        return $this->menu()
            ->rootItems()
            ->with(['page', 'childrenRecursive'])
            ->get();
    }

    public function getAvailablePages(): Collection
    {
        return Page::query()
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'status'])
            ->map(fn (Page $page): array => [
                'id' => $page->id,
                'title' => $page->title,
                'meta' => $page->status === 'published' ? "/{$page->slug}" : "{$page->status} - /{$page->slug}",
            ]);
    }

    public function getAvailablePosts(): Collection
    {
        return Post::query()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(30)
            ->get(['id', 'title', 'slug', 'status'])
            ->map(fn (Post $post): array => [
                'id' => $post->id,
                'title' => $post->title,
                'meta' => $post->status === 'published' ? "/berita/{$post->slug}" : "{$post->status} - /berita/{$post->slug}",
            ]);
    }

    private function menu(): Menu
    {
        /** @var Menu $menu */
        $menu = $this->getRecord();

        return $menu;
    }

    /**
     * @param  array<int, array{id: int|string, children?: array<int, mixed>}>  $items
     * @return array<int, array{id: int, parent_id: int|null, order: int}>
     */
    private function flattenTree(array $items, ?int $parentId = null): array
    {
        $flattenedItems = [];

        foreach (array_values($items) as $order => $item) {
            $itemId = (int) ($item['id'] ?? 0);

            if ($itemId <= 0) {
                continue;
            }

            $flattenedItems[] = [
                'id' => $itemId,
                'parent_id' => $parentId,
                'order' => $order + 1,
            ];

            $children = $item['children'] ?? [];

            if (is_array($children) && $children !== []) {
                $flattenedItems = [
                    ...$flattenedItems,
                    ...$this->flattenTree($children, $itemId),
                ];
            }
        }

        return $flattenedItems;
    }

    private function notifySuccess(string $message): void
    {
        $this->dispatch('menu-builder-refresh');

        Notification::make()
            ->success()
            ->title($message)
            ->send();
    }

    private function notifyWarning(string $message): void
    {
        $this->dispatch('menu-builder-refresh');

        Notification::make()
            ->warning()
            ->title($message)
            ->send();
    }
}
