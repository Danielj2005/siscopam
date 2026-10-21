<?php

namespace App\Http\Controllers;

use App\Exports\AtletasVencidosExport;
use App\Exports\CierresCajaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Exportar Cierres de Caja por Sede en XLSX o CSV.
     */
    public function exportarCierresCaja(Request $request, string $formato = 'xlsx')
    {
        $sedeId = $request->user()->sede_id;
        $extension = $formato === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;
        $filename = 'cierres_caja_' . now()->format('Y-m-d') . '.' . $formato;

        return Excel::download(new CierresCajaExport($sedeId), $filename, $extension);
    }

    /**
     * Exportar Atletas Vencidos en XLSX o CSV.
     */
    public function exportarAtletasVencidos(Request $request, string $formato = 'xlsx')
    {
        $empresaId = $request->user()->empresa_id;
        $extension = $formato === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;
        $filename = 'atletas_vencidos_' . now()->format('Y-m-d') . '.' . $formato;

        return Excel::download(new AtletasVencidosExport($empresaId), $filename, $extension);
    }
}