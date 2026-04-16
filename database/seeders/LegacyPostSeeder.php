<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LegacyPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('wp_posts.sql');

        if (! File::exists($path)) {
            $this->command?->warn('File wp_posts.sql tidak ditemukan.');

            return;
        }

        $author = $this->author();

        foreach ($this->legacyPosts($path) as $legacyPost) {
            $createdAt = $this->dateTime($legacyPost['post_date']) ?? now();
            $updatedAt = $this->dateTime($legacyPost['post_modified']) ?? $createdAt;
            $status = $legacyPost['post_status'] === 'publish' ? 'published' : 'draft';
            $excerpt = filled($legacyPost['post_excerpt']) ? $legacyPost['post_excerpt'] : $legacyPost['post_content'];
            $plainExcerpt = html_entity_decode(strip_tags($excerpt));

            Post::query()->updateOrCreate(
                ['slug' => $legacyPost['slug']],
                [
                    'post_category_id' => null,
                    'user_id' => $author->id,
                    'title' => $legacyPost['title'],
                    'slug' => $legacyPost['slug'],
                    'excerpt' => Str::limit($plainExcerpt, 180),
                    'content' => $legacyPost['post_content'],
                    'featured_image' => null,
                    'status' => $status,
                    'is_featured' => false,
                    'views' => 0,
                    'meta_title' => $legacyPost['title'],
                    'meta_description' => Str::limit($plainExcerpt, 155),
                    'meta_keywords' => null,
                    'og_image' => null,
                    'is_in_sitemap' => true,
                    'published_at' => $status === 'published' ? $createdAt : null,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ],
            );
        }
    }

    private function author(): User
    {
        return User::query()->first() ?? User::query()->create([
            'name' => 'Penulis Lama',
            'email' => 'legacy-post-seeder@unu.test',
            'password' => Hash::make(Str::random(32)),
        ]);
    }

    /**
     * @return array<int, array{
     *     id: int,
     *     title: string,
     *     slug: string,
     *     post_content: string,
     *     post_excerpt: string,
     *     post_status: string,
     *     post_date: string|null,
     *     post_modified: string|null
     * }>
     */
    private function legacyPosts(string $path): array
    {
        $sql = File::get($path);

        preg_match_all(
            '/INSERT INTO\s+`wp_posts`\s*\((.*?)\)\s+VALUES\s*(.*?);\s*(?=INSERT INTO|--|ALTER TABLE|$)/s',
            $sql,
            $matches,
            PREG_SET_ORDER,
        );

        if ($matches === []) {
            return [];
        }

        $posts = collect($matches)
            ->flatMap(function (array $match): array {
                $columns = collect(explode(',', $match[1]))
                    ->map(fn (string $column): string => trim($column, " `\n\r\t"))
                    ->values();

                return collect($this->extractTuples($match[2]))
                    ->map(function (string $tuple) use ($columns): array {
                        return $columns->combine($this->parseTuple($tuple))->all();
                    })
                    ->all();
            })
            ->filter(fn (array $post): bool => $post['post_type'] === 'post')
            ->filter(fn (array $post): bool => in_array($post['post_status'], ['publish', 'draft'], true))
            ->map(fn (array $post): array => [
                'id' => (int) $post['ID'],
                'title' => (string) $post['post_title'],
                'slug' => filled($post['post_name']) ? (string) $post['post_name'] : Str::slug((string) $post['post_title']),
                'post_content' => (string) $post['post_content'],
                'post_excerpt' => (string) $post['post_excerpt'],
                'post_status' => (string) $post['post_status'],
                'post_date' => $post['post_date'],
                'post_modified' => $post['post_modified'],
            ])
            ->filter(fn (array $post): bool => filled($post['slug']) && filled($post['title']))
            ->values();

        return $this->ensureUniqueSlugs($posts)->all();
    }

    /**
     * @param  Collection<int, array{id: int, slug: string}>  $posts
     * @return Collection<int, array{id: int, slug: string}>
     */
    private function ensureUniqueSlugs(Collection $posts): Collection
    {
        $seen = [];

        return $posts->map(function (array $post) use (&$seen): array {
            $slug = $post['slug'];
            $seen[$slug] = ($seen[$slug] ?? 0) + 1;

            if ($seen[$slug] > 1) {
                $post['slug'] = $slug.'-wp-'.$post['id'];
            }

            return $post;
        });
    }

    /**
     * @return array<int, string>
     */
    private function extractTuples(string $values): array
    {
        $values = trim($values);
        $values = preg_replace('/^\(/', '', $values) ?? $values;
        $values = preg_replace('/\)$/', '', $values) ?? $values;

        return preg_split('/\),\s*\r?\n\(/', $values) ?: [];
    }

    /**
     * @return array<int, string|null>
     */
    private function parseTuple(string $tuple): array
    {
        return array_map(
            fn (?string $value): ?string => $this->parseValue((string) $value),
            str_getcsv($tuple, ',', "'", '\\'),
        );
    }

    private function parseValue(string $value): ?string
    {
        $value = trim($value);

        if (Str::lower($value) === 'null') {
            return null;
        }

        if (Str::startsWith($value, "'") && Str::endsWith($value, "'")) {
            return stripcslashes(substr($value, 1, -1));
        }

        return stripcslashes($value);
    }

    private function dateTime(?string $value): ?Carbon
    {
        if (blank($value) || $value === '0000-00-00 00:00:00') {
            return null;
        }

        return Carbon::parse($value);
    }
}
