<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GeneralMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $mail_arr = [];
 

    public function __construct($name, $mailFromId, $txt, $subject, $rowid = null)
    {
        //
        $this->mail_arr['name'] = $name;
        $this->mail_arr['mailFromId'] = $mailFromId;
        $this->mail_arr['txt'] = $txt;
        $this->mail_arr['subject'] = $subject;               
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address($this->mail_arr['mailFromId'], config()->get('mail.from.name')),
            subject: $this->mail_arr['subject'],
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(

            // view: 'mails.only_text_mail_layout.blade',
            // html: 'emails.orders.shipped',
            // text: 'emails.orders.shipped-text'
            markdown: 'mails.only_text_mail_layout',
            with: [
                'mail_arr' => $this->mail_arr,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            // Attachment::fromPath(asset('uploads/profile_images/1670928231_4.chrlws.jpg'))
            //     ->as('admin_propic.jpg')
                // ->withMime('application/pdf'),
                // ->withMime('image/jpeg'),
        ];
        
    }
}
