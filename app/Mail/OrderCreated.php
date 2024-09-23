<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\OrdersClient;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $orderDetails;
    public $clientEmail;

    public function __construct($orderDetails, $clientEmail)
    {
        $this->orderDetails = $orderDetails; 
        $this->clientEmail = $clientEmail;
    }

    public function build()
    {
        return $this->view('mail.sendOrder')
            ->with([
                'order' => $this->orderDetails,
                'clientEmail' => $this->clientEmail,
            ])
            ->subject('Seu Pedido foi Criado');
    }
}
