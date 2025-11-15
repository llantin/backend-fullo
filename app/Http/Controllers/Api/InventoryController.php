<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Movement;
use App\Models\UnitConversion;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Controlador API para Gestión de Inventario
 *
 * Maneja las consultas de inventario y exportaciones a Excel,
 * incluyendo reportes generales y kardex por artículo.
 */
class InventoryController extends Controller
{
    /**
     * Listar todo el inventario
     *
     * Devuelve una lista completa de todos los artículos con su
     * información de categoría y último movimiento registrado.
     *
     * @return \Illuminate\Http\JsonResponse Lista completa del inventario
     */
    public function index()
    {
        $inventory = Item::with([
            'category',
            'movements' => function ($query) {
                $query->latest()->limit(1);
            }
        ])->get();

        return response()->json([
            'status' => true,
            'inventory' => $inventory
        ], 200);
    }
    /**
     * Exportar inventario completo a Excel
     *
     * Genera un archivo Excel con el inventario completo del sistema,
     * incluyendo información de stock actual, categorías y detalles de productos.
     * Utiliza PhpSpreadsheet para crear el archivo con formato profesional.
     *
     */
    public function exportInventory()
    {
        $inventory = Item::with([
            'category',
            'movements' => function ($query) {
                $query->latest()->limit(1);
            }
        ])->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Inventario General');
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'REGISTRO DE INVENTARIO');
        $headers = ['Nombre', 'Marca', 'Modelo', 'Descripcion', 'Stock', 'Unidad de medida', 'Presentacion', 'Categoria'];
        $sheet->fromArray($headers, null, 'A2');
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '95B3D7'],
            ],
        ];

        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
        $sheet->getStyle('A2:H2')->applyFromArray($headerStyle);

        $fila = 3;
        foreach ($inventory as $item) {
            $sheet->setCellValue("A{$fila}", $item->name);
            $sheet->setCellValue("B{$fila}", $item->brand);
            $sheet->setCellValue("C{$fila}", $item->model);
            $sheet->setCellValue("D{$fila}", $item->description);
            $sheet->setCellValue("E{$fila}", $item->movements()->latest()->first()->stock ?? 'No definido');
            $sheet->setCellValue("F{$fila}", $item->unit_measurement);
            $sheet->setCellValue("G{$fila}", $item->presentation);
            $sheet->setCellValue("H{$fila}", $item->category->name);
            $sheet->getStyle("A{$fila}:H{$fila}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);
            $fila++;
        }
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        $writer->save($tempFile);
        $fecha_hoy = Carbon::now()->format('d-m-Y');
        return response()->download($tempFile, "Inventario {$fecha_hoy}.xlsx")->deleteFileAfterSend(true);
    }
    /**
     * Exportar kardex de un artículo específico
     *
     * Genera un reporte Excel de kardex (movimientos de inventario) para un artículo
     * específico en un rango de fechas determinado. Incluye entradas, salidas,
     * stock final y conversiones de unidades cuando sea necesario.
     *
     * @param int $item_id ID del artículo
     * @param string $init_date Fecha inicial (formato Y-m-d)
     * @param string $end_date Fecha final (formato Y-m-d)
     */
    public function exportKardex($item_id, $init_date, $end_date)
    {
        $item = Item::with('movements.receipt', 'movements.receipt_detail')->findOrFail($item_id);

        $movements = $item->movements
            ->whereBetween('created_at', [$init_date, $end_date])
            ->sortBy('created_at');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:C3');
        $sheet->mergeCells('D1:E1');
        $sheet->mergeCells('D2:E2');
        $sheet->mergeCells('D3:E3');

        $sheet->setCellValue('A1', "KÁRDEX DE ARTICULO: {$item->name}");
        $sheet->setCellValue('D1', 'Unidad de medida:');
        $sheet->setCellValue('F1', $item->unit_measurement);
        $sheet->setCellValue('D2', 'Stock minimo:');
        $sheet->setCellValue('F2', $item->minimum_stock);
        $sheet->setCellValue('D3', 'Stock maximo:');
        $sheet->setCellValue('F3', $item->maximum_stock);
        $sheet->setCellValue('A4', 'Fecha');
        $sheet->setCellValue('B4', 'Concepto');
        $sheet->setCellValue('C4', 'Costo Unitario');
        $sheet->setCellValue('D4', 'Entradas');
        $sheet->setCellValue('E4', 'Salidas');
        $sheet->setCellValue('F4', 'Inv. Final');

        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BDD7EE'],
            ],
        ];
        $subheaderStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BDD7EE'],
            ],
        ];

        $sheet->getStyle('A1:E3')->applyFromArray($headerStyle);
        $sheet->getStyle('A1:C3')->getFont()->setSize(14);
        $sheet->getStyle('A4:F4')->applyFromArray($subheaderStyle);
        $sheet->getStyle("F1:F3")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $fila = 5;

        foreach ($movements as $m) {
            $entrada = $m->type === 'Compra' ? $m->quantity : 0;
            $salida = $m->type === 'Venta' ? $m->quantity : 0;

            $sheet->setCellValue("A{$fila}", $m->created_at->format('d-m-Y'));
            $sheet->setCellValue("B{$fila}", "{$m->receipt->description} {$m->type} {$m->receipt->receipt_code}");
            $sheet->setCellValue("C{$fila}", "{$m->price}");
            $sheet->setCellValue("D{$fila}", "{$entrada}");
            if ($m->receipt_detail->unit !== $item->unit_measurement) {
                $conversion = UnitConversion::where('comercial_unit', $m->receipt_detail->unit)
                    ->where('base_unit', $item->unit_measurement)
                    ->first();
                $salida_converted = $salida / $conversion->conversion_factor;
                $sheet->setCellValue("E{$fila}", "{$salida_converted}");
            } else {
                $sheet->setCellValue("E{$fila}", "{$salida}");
            }
            $sheet->setCellValue("F{$fila}", "{$m->stock}");

            $sheet->getStyle("A{$fila}:F{$fila}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            $sheet->getStyle("D{$fila}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'c7f1b9'],
                ],
            ]);

            $sheet->getStyle("E{$fila}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'f0acb2'],
                ],
            ]);

            $sheet->getStyle("F{$fila}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'ffe79a'],
                ],
            ]);

            $fila++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'kardex');
        $writer->save($tempFile);
        $item_name = str_replace(['"', '/', '\\'], '-', $item->name);

        $fecha_hoy = str_replace(['/', '\\'], '-', $fecha_hoy);

        $filename = "Kardex_{$item_name}_{$fecha_hoy}.xlsx";

        return response()
            ->download($tempFile, $filename)
            ->deleteFileAfterSend(true);

    }
}

