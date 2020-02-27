<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class AlertsEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $sendMail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendMail )
    {
        $this->sendMail = $sendMail;
    }

    /**
     * Build the message.
     * Puede o no recibe un attach_file (archivo_adjunto) 
     * @return $this
     */
    public function build()
    {       
        $data=$this->sendMail;
        if(isset($data['attach_file'])){
            return $this->view('mails.'.$data['file'],compact('data'))
                    ->subject('Documento recibido desde '.$data['title'])
                    ->attach(storage_path($data['attach_file']));
        }else{
            return $this->view('mails.'.$data['file'],compact('data'))->subject($data['subject'].', '.$data['title']);
        }
        
    }
}
