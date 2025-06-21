<?php

namespace App\Jobs;

use App\Mail\OrderConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $id;
    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Preparing order confirmation email. order_id:'.$this->id);
        Mail::send( new OrderConfirmation( $this->id));
    }
}
