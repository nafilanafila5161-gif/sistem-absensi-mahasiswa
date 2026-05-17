<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Tambahkan variabel publik agar bisa dibaca oleh View email
    public $user;
    public $password;

    // 2. Terima data dari Controller lewat construct
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Detail Akun Baru Anda', // Subjek email yang muncul di inbox
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user_created', // Pastikan file ini ada di resources/views/emails/user_created.blade.php
        );
    }

    public function attachments(): array
    {
        return [];
    }
}