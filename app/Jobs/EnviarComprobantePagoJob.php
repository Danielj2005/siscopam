<?php

namespace App\Jobs;

use App\Mail\ComprobantePagoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarComprobantePagoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $pagoData;

    public function __construct(array $pagoData)
    {
        $this->pagoData = $pagoData;
    }

    public function handle(): void
    {
        if (! empty($this->pagoData['cliente_correo'])) {
            Mail::to($this->pagoData['cliente_correo'])
                ->send(new ComprobantePagoMail($this->pagoData));
        }
    }
}