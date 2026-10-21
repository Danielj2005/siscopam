<?php

namespace App\Exports;

use App\Models\CierreCaja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CierresCajaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected string $sedeId;

    public function __construct(string $sedeId)
    {
        $this->sedeId = $sedeId;
    }

    public function collection()
    {
        return CierreCaja::with(['cajero', 'sede'])
            ->where('sede_id', $this->sedeId)
            ->where('estatus', 'CERRADA')
            ->orderBy('fecha_cierre', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Cierre',
            'Sede',
            'Cajero',
            'Fecha Apertura',
            'Fecha Cierre',
            'Efectivo Declarado ($)',
            'Efectivo Declarado (Bs)',
            'Pago Móvil (Bs)',
            'Punto de Venta (Bs)',
            'Zelle ($)',
            'Diferencia Global (USD)',
            'Observaciones'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->sede->nombre ?? 'N/A',
            $row->cajero->name ?? 'Sistema',
            $row->fecha_apertura->format('d/m/Y H:i'),
            $row->fecha_cierre ? $row->fecha_cierre->format('d/m/Y H:i') : 'N/A',
            (float) $row->declarado_efectivo_usd,
            (float) $row->declarado_efectivo_ves,
            (float) $row->declarado_pago_movil_ves,
            (float) $row->declarado_punto_ves,
            (float) $row->declarado_zelle_usd,
            (float) $row->diferencia_usd,
            $row->observaciones ?? 'Sin observaciones'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']]],
        ];
    }
}