<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        try {
            Mail::to(config('app.contact_email', 'fikaafizh7@gmail.com'))->send(new ContactMail($data));

            return back()->with('status', 'Pesan terkirim! Terima kasih, kami akan segera membalas.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('status', 'Maaf, pesan gagal terkirim. Silakan hubungi kami via WhatsApp.');
        }
    }
}
