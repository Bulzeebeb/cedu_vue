<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OnlineMarketReportsController extends Controller
{
    public function getReportsData(Request $request)
    {
        $categoryId = $request->query('cat');
        $period = $request->query('period', 'daily');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Map category IDs to actual category names
        $categoryMap = [
            'fruit' => 'Fruits',
            'vegetables' => 'Vegetables',
            'poultry' => 'Poultry'
        ];

        $actualCategory = isset($categoryMap[$categoryId]) ? $categoryMap[$categoryId] : null;

        // Get categories for the dropdown (only Fruits, Vegetables, Poultry)
        $categories = [
            ['id' => 'fruit', 'name' => 'Fruits'],
            ['id' => 'vegetables', 'name' => 'Vegetables'],
            ['id' => 'poultry', 'name' => 'Poultry']
        ];

        // Build query for order items
        $query = OrderItem::with(['order', 'product']);

        if ($actualCategory) {
            $query->whereHas('product', function ($q) use ($actualCategory) {
                $q->where('category', $actualCategory);
            });
        } else {
            // If no category selected, show all three categories
            $query->whereHas('product', function ($q) {
                $q->whereIn('category', ['Fruits', 'Vegetables', 'Poultry']);
            });
        }

        if ($startDate && $endDate) {
            $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('order_date', [$startDate, $endDate]);
            });
        }

        $orderItems = $query->get();

        $reportData = [];
        foreach ($orderItems as $item) {
            $order = $item->order;
            $product = $item->product;

            if (!$order || !$product) {
                continue; // Skip items with missing order or product
            }

            $reportData[] = [
                'date' => $order->order_date ? Carbon::parse($order->order_date)->format('Y-m-d') : '',
                'time' => $order->order_date ? Carbon::parse($order->order_date)->format('H:i:s') : '',
                'client' => $order->first_name . ' ' . $order->last_name,
                'product' => $item->product_name,
                'category' => $product->category,
                'quantity' => $item->quantity,
                'price' => $item->unit_price,
                'total' => $item->subtotal,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $reportData,
            'categories' => $categories
        ]);
    }
}
