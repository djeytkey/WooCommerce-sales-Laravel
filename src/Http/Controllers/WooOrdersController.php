<?php

namespace BoukjijTarik\WooSales\Http\Controllers;

use App\Http\Controllers\Controller;
use BoukjijTarik\WooSales\Models\WooOrder;
use BoukjijTarik\WooSales\Models\WooOrderItem;
use BoukjijTarik\WooSales\Models\WooOrderItemMeta;
use BoukjijTarik\WooSales\Exports\WooOrdersExport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class WooOrdersController extends Controller
{
    /**
     * Display the orders page
     */
    public function index(): View
    {
        return view('wooSales::orders.index');
    }

    /**
     * Get orders data for DataTable
     */
    public function getData(Request $request): JsonResponse
    {
        $query = $this->buildQuery($request);
        
        $totalRecords = $query->count();
        
        // Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', config('wooSales.items_per_page'));
        
        $orders = $query->skip($start)->take($length)->get();
        
        $data = [];
        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                $data[] = [
                    'order_id' => $order->ID,
                    'product_name' => $item->order_item_name,
                    'quantity' => $item->quantity,
                    'subtotal' => number_format($item->subtotal, 2),
                    'discount' => number_format($item->discount, 2),
                    'order_date' => $order->post_date,
                    'order_status' => $order->post_status
                ];
            }
        }
        
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    /**
     * Export orders to Excel
     */
    public function export(Request $request)
    {
        $query = $this->buildQuery($request);
        $totalRecords = $query->count();
        
        // Check if we should use server-side export
        if ($totalRecords > config('wooSales.max_client_export')) {
            return Excel::download(
                new WooOrdersExport($request->all()),
                'woo-orders-' . date('Y-m-d-H-i-s') . '.xlsx'
            );
        }
        
        // For smaller datasets, return data for client-side export
        $orders = $query->get();
        $data = [];
        
        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                $data[] = [
                    'Order ID' => $order->ID,
                    'Product Name' => $item->order_item_name,
                    'Quantity' => $item->quantity,
                    'Subtotal' => $item->subtotal,
                    'Discount' => $item->discount,
                    'Order Date' => $order->post_date,
                    'Order Status' => $order->post_status
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'filename' => 'woo-orders-' . date('Y-m-d-H-i-s') . '.xlsx'
        ]);
    }

    /**
     * Build the query based on filters
     */
    private function buildQuery(Request $request)
    {
        $query = WooOrder::with(['orderItems.meta'])
            ->where('post_type', 'shop_order');

        // Apply filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        if ($request->filled('order_id')) {
            $query->byOrderId($request->order_id);
        }

        if ($request->filled('order_status') && is_array($request->order_status)) {
            $query->whereIn('post_status', $request->order_status);
        }

        // Apply search
        if ($request->filled('search') && $request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('ID', 'like', "%{$searchValue}%")
                  ->orWhere('post_title', 'like', "%{$searchValue}%");
            });
        }

        // Apply sorting
        if ($request->filled('order')) {
            $columnIndex = $request->input('order.0.column');
            $direction = $request->input('order.0.dir');
            
            $columns = ['ID', 'post_title', 'post_date', 'post_status'];
            if (isset($columns[$columnIndex])) {
                $query->orderBy($columns[$columnIndex], $direction);
            }
        }

        return $query;
    }
} 