<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Notifications\MembresiaExpiracionNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotificarVencimientosMembresia extends Command
{
    protected $signature = 'membresias:notificar-vencimientos';
    protected $description = 'Notifica a los clientes cuyas membresías vencen en 3 días o que vencieron hoy';

    public function handle(): int
    {
        $hoy = Carbon::today();
        $enTresDias = Carbon::today()->addDays(3);

        // 1. Clientes con vencimiento en 3 días
        $clientesPorVencer = Cliente::whereDate('fecha_vencimiento', $enTresDias)
            ->where('activo', true)
            ->get();

        foreach ($clientesPorVencer as $cliente) {
            $cliente->notify(new MembresiaExpiracionNotification('POR_VENCER', 3));
        }

        // 2. Clientes cuya membresía venció hoy
        $clientesExpirados = Cliente::whereDate('fecha_vencimiento', $hoy)
            ->where('activo', true)
            ->get();

        foreach ($clientesExpirados as $cliente) {
            $cliente->notify(new MembresiaExpiracionNotification('EXPIRADA', 0));
        }

        $this->info("Notificaciones procesadas: {$clientesPorVencer->count()} por vencer, {$clientesExpirados->count()} expiradas.");

        return Command::SUCCESS;
    }
}