<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComprobantePagoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $pagoData;

    public function __construct(array $pagoData)
    {
        $this->pagoData = $pagoData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Comprobante de Pago {$this->pagoData['numero_comprobante']} - {$this->pagoData['empresa_nombre']}",
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: "
                <p>Hola <strong>{$this->pagoData['cliente_nombre']}</strong>,</p>
                <p>Hemos procesado tu pago exitosamente. Adjunto a este correo encontrarás tu comprobante digital en formato PDF con el desglose de tu compra/mensualidad.</p>
                <p><strong>Detalles rápidos:</strong></p>
                <ul>
                    <li><strong>Comprobante:</strong> {$this->pagoData['numero_comprobante']}</li>
                    <li><strong>Monto:</strong> \${$this->pagoData['total_usd']} USD (Bs. {$this->pagoData['total_ves']})</li>
                    <li><strong>Tasa BCV:</strong> Bs. {$this->pagoData['tasa_bcv']}</li>
                </ul>
                <p>¡Gracias por mantenerte activo con nosotros!</p>
                <hr>
                <small>SisCoPaM | Create Tech Solutions</small>
            "
        );
    }

    public function attachments(): array
    {
        // Generación del PDF en memoria para adjuntar sin guardar basura en disco
        $pdf = Pdf::loadView('pdf.comprobante_pago', ['pago' => $this->pagoData]);

        return [
            Attachment::fromData(fn () => $pdf->output(), "Comprobante_{$this->pagoData['numero_comprobante']}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}