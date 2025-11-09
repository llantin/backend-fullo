<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Receipt;
use App\Models\ReceiptDetail;
use App\Models\Movement;
use App\Models\Item;
use Illuminate\Support\Str;

class MoreReceipts extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('es_ES');

        // Datos fijos que mencionaste
        $userId = 1;       // Carlos Cardenas
        $personId = 2;     // Frank Fuentes
        $types = ['Compra', 'Venta'];
        $descriptions = ['Boleta', 'Factura'];
        $units = ['CM', 'GL', 'G', 'KG', 'LB', 'L', 'M', 'M3', 'ML', 'OZ', 'IN', 'UND'];

        $items = Item::pluck('id')->toArray();

        for ($i = 1; $i <= 300; $i++) {
            $type = $faker->randomElement($types);
            $desc = $faker->randomElement($descriptions);
            $receiptCode = 'CC-' . str_pad($i, 4, '0', STR_PAD_LEFT);

            // Crear cabecera del comprobante
            $receipt = Receipt::create([
                'receipt_code' => $receiptCode,
                'description' => $desc,
                'user_id' => $userId,
                'person_id' => $personId,
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Número aleatorio de artículos por comprobante
            $numArticles = rand(1, 5);

            for ($j = 0; $j < $numArticles; $j++) {
                $itemId = $faker->randomElement($items);
                $quantity = $faker->randomFloat(2, 1, 50);
                $unit = $faker->randomElement($units);
                $price = $faker->randomFloat(2, 1, 500);
                $subtotal = $quantity * $price;

                // Crear detalle
                $detail = ReceiptDetail::create([
                    'receipt_id' => $receipt->id,
                    'item_id' => $itemId,
                    'quantity' => $quantity,
                    'unit' => $unit,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Crear movimiento (Compra → suma stock, Venta → resta stock)
                $movement = Movement::create([
                    'item_id' => $itemId,
                    'user_id' => $userId,
                    'receipt_id' => $receipt->id,
                    'receipt_detail_id' => $detail->id,
                    'quantity' => $quantity,
                    'type' => $type,
                    'price' => $price,
                    'stock' => $this->simulateStockChange($itemId, $type, $quantity),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Simula la actualización del stock (solo para poblar datos de ejemplo)
     */
    private function simulateStockChange($itemId, $type, $quantity)
    {
        static $stocks = [];

        if (!isset($stocks[$itemId])) {
            $stocks[$itemId] = rand(20, 100);
        }

        if ($type === 'Compra') {
            $stocks[$itemId] += $quantity;
        } else {
            $stocks[$itemId] -= $quantity;
        }

        return max(0, round($stocks[$itemId]));
    }
}
