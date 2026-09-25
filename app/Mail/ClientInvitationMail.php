<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class ClientInvitationMail extends Mailable {
    use Queueable, SerializesModels;
    public function __construct(public string $clientName,public string $acceptUrl,public string $role='admin') {}
    public function build(): self { return $this->subject('Your Sembark '.$this->role.' invitation')->view('emails.client-invitation'); }
}
