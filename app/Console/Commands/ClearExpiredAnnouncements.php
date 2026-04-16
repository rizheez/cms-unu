<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Announcement;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('announcements:clear-expired')]
#[Description('Deactivate expired announcements')]
class ClearExpiredAnnouncements extends Command
{
    public function handle(): int
    {
        $count = Announcement::query()
            ->where('is_active', true)
            ->whereNotNull('end_at')
            ->where('end_at', '<', now())
            ->update(['is_active' => false]);

        $this->info("{$count} pengumuman kedaluwarsa dinonaktifkan.");

        return self::SUCCESS;
    }
}
