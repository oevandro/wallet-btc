<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailServices extends Mailable implements ShouldQueue
{

    use Queueable, SerializesModels;

    public static function sendEmail($name, $mailto, $content, $subject)
    {
        $data = array(
            'name'=> $name,
            'mailto' => $mailto,
            'content' => $content,
            'subject' => $subject
        );

        try {
            Mail::to('mail', $data, function($message) use ($data) {
                $message->to($data['mailto'], $data['name'])->subject($data['subject']);
                $message->from('teste@codeninja.com.br','Teste Lumen API');
            })->queue(new MailServices());
        } catch (\Throwable $th) {}

        return true;
    }
}
