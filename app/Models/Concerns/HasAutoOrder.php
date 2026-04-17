<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasAutoOrder
{
    protected static function bootHasAutoOrder(): void
    {
        static::creating(function (Model $model): void {
            if ($model->getAttribute('order') !== null) {
                return;
            }

            $query = $model->newQuery();

            foreach ($model->autoOrderGroupColumns() as $column) {
                $value = $model->getAttribute($column);

                if ($value === null) {
                    $query->whereNull($column);

                    continue;
                }

                $query->where($column, $value);
            }

            $model->setAttribute('order', ((int) $query->max('order')) + 1);
        });
    }

    /**
     * @return array<int, string>
     */
    protected function autoOrderGroupColumns(): array
    {
        return [];
    }
}
