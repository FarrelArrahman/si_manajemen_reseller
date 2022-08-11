<?php

namespace App\Jobs;

use App\Mail\NotifyMail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmailJob implements ShouldQueue
{
    public $tries = 3;
    protected $details;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new NotifyMail($this->details);
        Mail::to($this->details['email'])
            ->subject("Tes judul dari SendEmailJob")
            ->send($email);
    }

    public function failed(Exception $ex)
    {
        info($ex->getMessage());
    }
}
