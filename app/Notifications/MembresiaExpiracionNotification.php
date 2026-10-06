<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembresiaExpiracionNotification extends Notification
{
    use Queueable;

    public string $tipo; // 'POR_VENCER' o 'EXPIRADA'
    public int $diasRestantes;

    public function __construct(string $tipo, int$diasRestantes = 0)
    {
        $this->tipo =$tipo;
        $this->diasRestantes =$diasRestantes;
    }

    public function via(object $notifiable): array
    {
        // Enviar por Correo Electrónico y guardar en la Base de Datos para el Panel In-App
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->tipo === 'POR_VENCER') {
            return (new MailMessage)
                ->subject('¡Tu membresía está por vencer!')
                ->greeting("Hola, {$notifiable->nombre}")
                ->line("Te recordamos que tu membresía vence en {$this->diasRestantes} días.")
                ->line("Fecha de vencimiento: {$notifiable->fecha_vencimiento->format('d/m/Y')}")
                ->action('Renovar Membresía', url('/portal/renovar'))
                ->line('¡No dejes de entrenar!');
        }

        return (new MailMessage)
            ->subject('Tu membresía ha expirado')
            ->greeting("Hola, {$notifiable->nombre}")
            ->line('Tu membresía ha vencido el día de hoy.')
            ->line('Para mantener el acceso a las instalaciones, realiza tu renovación desde nuestro portal o en recepción.')
            ->action('Pagar en línea', url('/portal/pagos'));
    }

    public function toArray(object $notifiable): array
    {
        // Estructura almacenada en la tabla `notifications` (In-App Push)
        return [
            'titulo' => $this->tipo === 'POR_VENCER' ? 'Membresía por vencer' : 'Membresía expirada',
            'mensaje' => $this->tipo === 'POR_VENCER'
                ? "Tu membresía vence en {$this->diasRestantes} días ({$notifiable->fecha_vencimiento->format('d/m/Y')})."
                : 'Tu membresía ha expirado. Renueva para continuar ingresando.',
            'tipo' => $this->tipo,
            'fecha_vencimiento' => $notifiable->fecha_vencimiento->toDateString(),
            'accion_url' => '/portal/pagos',
        ];
    }
}