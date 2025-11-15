<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Movement;
use App\Models\Receipt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador API para Dashboard
 *
 * Proporciona endpoints para obtener estadísticas y datos analíticos
 * del sistema de inventario. Incluye métricas generales, movimientos,
 * estado de stock y flujos de inventario para visualización en dashboard.
 */
class DashboardController extends Controller
{
    /**
     * Obtener estadísticas generales
     *
     * Devuelve métricas básicas del sistema: total de ítems, receipts,
     * movimientos y ítems con stock excesivo.
     *
     * @return \Illuminate\Http\JsonResponse Estadísticas generales del sistema
     */
    public function getStats()
    {
        $total_items = Item::count();
        $total_receipts = Receipt::count();
        $total_movements = Movement::count();
        $total_items_excess_stock = Item::whereHas('movements', function (Builder $query) {
            $query->latest()->limit(1)
                ->whereColumn('stock', '>', 'items.maximum_stock');
        })->count();

        // Sample data - replace with actual logic to fetch stats
        $stats = [
            'total_items' => $total_items,
            'total_receipts' => $total_receipts,
            'total_movements' => $total_movements,
            'total_items_excess_stock' => $total_items_excess_stock,
        ];

        return response()->json(['stats' => $stats]);
    }

    /**
     * Obtener estadísticas de movimientos diarios
     *
     * Devuelve el conteo diario de movimientos de entrada (compras)
     * y salidas (ventas) ordenados por fecha descendente.
     *
     * @return \Illuminate\Http\JsonResponse Estadísticas diarias de movimientos
     */
    public function getMovementsStats()
    {
        $daily_entries = Movement::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->where('type', 'Compra')
            ->get();
        $daily_usages = Movement::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->where('type', 'Venta')
            ->get();
        return response()->json([
            'chart_stats' => [
                'daily_entries' => $daily_entries,
                'daily_usages' => $daily_usages,
            ]
        ]);
    }

    /**
     * Obtener estadísticas circulares de stock
     *
     * Devuelve métricas de estado de stock: ítems con stock bueno,
     * bajo y sin stock, basadas en los niveles mínimo y máximo.
     *
     * @return \Illuminate\Http\JsonResponse Estadísticas de estado de stock
     */
    public function getCircleStats()
    {
        $items_with_good_stock = Item::whereHas('movements', function (Builder $query) {
            $query->latest()->limit(1)
                ->whereColumn('stock', '>', 'items.minimum_stock');
        })->count();
        $items_with_low_stock = Item::whereHas('movements', function (Builder $query) {
            $query->latest()->limit(1)
                ->whereColumn('stock', '<=', 'items.minimum_stock');
        })->count();
        $lastMovements = DB::table('movements as m1')
            ->select('m1.item_id', 'm1.stock')
            ->whereRaw('m1.id = (select max(m2.id) from movements m2 where m2.item_id = m1.item_id)');

        $items_without_stock = Item::leftJoinSub($lastMovements, 'last_m', function ($join) {
            $join->on('items.id', '=', 'last_m.item_id');
        })
            ->where(function ($query) {
                $query->where('last_m.stock', 0)   // último movimiento sin stock
                    ->orWhereNull('last_m.stock'); // items sin movimientos
            })
            ->count();
        return response()->json([
            'circle_stats' => [
                'items_with_good_stock' => $items_with_good_stock,
                'items_with_low_stock' => $items_with_low_stock,
                'items_without_stock' => $items_without_stock,
            ]
        ]);
    }

    /**
     * Obtener últimos movimientos
     *
     * Devuelve los 10 movimientos más recientes con información
     * del ítem, usuario y detalles del receipt asociados.
     *
     * @return \Illuminate\Http\JsonResponse Lista de últimos movimientos
     */
    public function getLastMovements()
    {
        $movements = Movement::with(['item', 'user.person', 'receipt_detail'])->limit(10)->orderBy('id', 'desc')->get();
        return response()->json([
            'last_movements' => $movements
        ]);
    }

    /**
     * Obtener ítems con más movimientos
     *
     * Devuelve los 10 ítems con mayor cantidad de movimientos,
     * incluyendo información de categoría y último movimiento.
     *
     * @return \Illuminate\Http\JsonResponse Lista de ítems más activos
     */
    public function getItemsWithMostMovements()
    {
        $items = Item::withCount('movements')
            ->with([
                'category',
                'movements' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
            ->orderBy('movements_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'items_with_most_movements' => $items
        ]);
    }

    /**
     * Obtener flujo de movimientos de los últimos 7 días
     *
     * Devuelve datos de ingresos (compras) y salidas (ventas)
     * para los últimos 7 días, formateados para gráficos.
     *
     * @return \Illuminate\Http\JsonResponse Datos de flujo de movimientos con etiquetas y valores
     */
    public function getMovementFlow()
    {
        // Obtener datos de movimientos de los últimos 7 días
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        $ingresos = Movement::selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->where('type', 'Compra')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $salidas = Movement::selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->where('type', 'Venta')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $ingresosData = [];
        $salidasData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');
            $ingresosData[] = $ingresos[$date] ?? 0;
            $salidasData[] = $salidas[$date] ?? 0;
        }

        return response()->json([
            'movement_flow' => [
                'labels' => $labels,
                'ingresos' => $ingresosData,
                'salidas' => $salidasData,
            ]
        ]);
    }
}
