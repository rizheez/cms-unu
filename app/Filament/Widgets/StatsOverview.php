<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Lecturer;
use App\Models\Post;
use App\Models\Setting;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $studentsCount = number_format((int) Setting::get('home_students_count', 12400), 0, ',', '.').'+';

        return [
            Stat::make('Total Artikel', Post::query()->count())
                ->description('Konten berita dan artikel')
                ->color('success'),
            Stat::make('Total Mahasiswa', $studentsCount)
                ->description('Diambil dari statistik beranda')
                ->color('warning'),
            Stat::make('Total Dosen', Lecturer::query()->count())
                ->description('Dosen aktif dan terdaftar')
                ->color('info'),
            Stat::make('Pesan Masuk', ContactMessage::query()->where('status', 'unread')->count())
                ->description('Belum dibaca')
                ->color('danger'),
        ];
    }
}
