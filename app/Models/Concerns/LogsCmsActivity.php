<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsCmsActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('cms')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->dontLogIfAttributesChangedOnly($this->ignoredActivityAttributes())
            ->setDescriptionForEvent(fn (string $eventName): string => $this->activityDescription($eventName));
    }

    /**
     * @return array<int, string>
     */
    protected function ignoredActivityAttributes(): array
    {
        return [
            'updated_at',
            'views',
            'download_count',
            'read_at',
        ];
    }

    protected function activityDescription(string $eventName): string
    {
        $eventLabel = match ($eventName) {
            'created' => 'dibuat',
            'updated' => 'diperbarui',
            'deleted' => 'dihapus',
            'restored' => 'dipulihkan',
            default => $eventName,
        };

        return sprintf('%s "%s" %s', class_basename($this), $this->activityTitle(), $eventLabel);
    }

    protected function activityTitle(): string
    {
        $value = $this->getAttribute('title')
            ?? $this->getAttribute('name')
            ?? $this->getAttribute('label')
            ?? $this->getAttribute('question')
            ?? $this->getKey();

        return (string) $value;
    }
}
