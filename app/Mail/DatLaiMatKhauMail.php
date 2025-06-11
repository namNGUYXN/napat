<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DatLaiMatKhauMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.auth.dat-lai-mat-khau')
                    ->from('namdemoit11@gmail.com', 'NAPAT E-Learning')
                    ->subject('[NAPAT] Yêu Cầu Đặt Lại Mật Khẩu')
                    ->with($this->data);
    }
}
