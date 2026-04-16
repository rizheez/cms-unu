<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Download;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setDefault();

        return view('downloads.index', [
            'downloads' => Download::query()->where('is_active', true)->latest()->paginate(12),
        ]);
    }

    public function download(Download $download): RedirectResponse
    {
        abort_unless($download->is_active, 404);

        $download->incrementDownloadCount();

        return redirect()->to(Storage::disk('public')->url($download->file));
    }
}
