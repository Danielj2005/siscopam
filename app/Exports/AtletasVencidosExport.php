<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AtletasVencidosExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected string $empresaId;

    public function __construct(string $empresaId)
    {
        $this->empresaId = $empresaId;
    }

    public function collection()
    {
        return Cliente::where('empresa_id', $this->empresaId)
            ->where(function($q) {
                $q->whereNull('fecha_vencimiento')
                  ->orWhere('fecha_vencimiento', '<', now());
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'Cédula',
            'Nombres',
            'Apellidos',
            'Teléfono',
            'Correo Electrónico',
            'Último Vencimiento',
            'Días Vencido'
        ];
    }

    public function map($row): array
    {
        $diasVencido = $row->fecha_vencimiento ? now()->diffInDays($row->fecha_vencimiento, false) * -1 : 999;

        return [
            $row->cedula,
            $row->nombre,
            $row->apellido,
            $row->telefono ?? 'N/A',
            $row->email ?? 'N/A',
            $row->fecha_vencimiento ? $row->fecha_vencimiento->format('d/m/Y') : 'Nunca Activo',
            $diasVencido > 0 ? $diasVencido . ' días' : 'Indefinido'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '451A03']]],
        ];
    }
}