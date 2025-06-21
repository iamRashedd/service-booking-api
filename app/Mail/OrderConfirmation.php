<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    protected $order_id;
    /**
     * Create a new message instance.
     */
    public function __construct($id)
    {
        $this->order_id = $id;
    }
    public function build() {

        $order = Order::with('order_items','user')->where('id',$this->order_id)->first();
        
        Log::info('Sending Order Email to: '.$order->user->email.' Order id:'.$order->id);
        $this->subject('Order confirmation from Service Booking')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->to($order->user->email)
            ->markdown('emails.order_confirmation',compact('order'));
        
    }
}
