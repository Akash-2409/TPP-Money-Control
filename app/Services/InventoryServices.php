<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\DailyProduction;
use App\Models\Product;

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
        $inventory = $product->inventory;

        if (!$inventory) {
            return;
        }

        // Total produced
        $totalProduced = DailyProduction::where('product_id', $productId)->sum('production_qty');

        // Total sold (if you add sales later)
        $totalSold = 0;

        // Update inventory
        $inventory->update([
            'total_produced' => $totalProduced,
            'total_sold'     => $totalSold,
            'current_stock'  => ($inventory->opening_stock + $totalProduced) - $totalSold,
        ]);
    }
}
