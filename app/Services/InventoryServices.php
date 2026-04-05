<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\DailyProduction;
use App\Models\Product;
use App\Models\Sale;

class InventoryServices
{
    /**
     * Recalculate inventory values for a specific product.
     */
    public function recalculateForProduct($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        // Opening stock (from inventory table)
        // If inventory doesn't exist, create it with 0 opening stock
        $inventory = $product->inventory ?: Inventory::create(['product_id' => $productId, 'opening_stock' => 0]);

        // Total produced
        $totalProduced = DailyProduction::where('product_id', $productId)->sum('production_qty');

        // Total sold
        $totalSold = Sale::where('product_id', $productId)->sum('quantity');

        // Update inventory
        $inventory->update([
            'total_produced' => $totalProduced,
            'total_sold'     => $totalSold,
            'current_stock'  => ($inventory->opening_stock + $totalProduced) - $totalSold,
        ]);
    }
}
