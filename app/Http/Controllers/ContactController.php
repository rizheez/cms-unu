<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Jobs\ContactFormReceived;
use App\Models\ContactMessage;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function create(SeoService $seo): View
    {
        $seo->setDefault();

        return view('contact');
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $message = ContactMessage::query()->create($request->validated());

        ContactFormReceived::dispatch($message)->onQueue('emails');

        return back()->with('status', 'Pesan Anda sudah terkirim. Tim kami akan menghubungi Anda.');
    }
}
