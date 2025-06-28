<?php

namespace BoukjijTarik\WooSales\Exports;

use BoukjijTarik\WooSales\Models\WooOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WooOrdersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Get the query for the export
     */
    public function query()
    {
        $query = WooOrder::with(['orderItems.meta'])
            ->where('post_type', 'shop_order');

        // Apply filters
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->byDateRange($this->filters['start_date'], $this->filters['end_date']);
        }

        if (!empty($this->filters['order_id'])) {
            $query->byOrderId($this->filters['order_id']);
        }

        if (!empty($this->filters['order_status']) && is_array($this->filters['order_status'])) {
            $query->whereIn('post_status', $this->filters['order_status']);
        }

        return $query;
    }

    /**
     * Get the headings for the export
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'Product Name',
            'Quantity',
            'Line Subtotal',
            'Line Discount',
            'Order Date',
            'Order Status'
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($order): array
    {
        $rows = [];
        
        foreach ($order->orderItems as $item) {
            $rows[] = [
                $order->ID,
                $item->order_item_name,
                $item->quantity,
                number_format($item->subtotal, 2),
                number_format($item->discount, 2),
                $order->post_date,
                $order->post_status
            ];
        }
        
        return $rows;
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }
} 