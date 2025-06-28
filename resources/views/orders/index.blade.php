@extends('layouts.admin')

@section('title')
تقرير المبيعات
@endsection

@section('contentheader')
تقرير المبيعات
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .filters-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .export-buttons {
        margin-bottom: 20px;
    }
    
    .loading {
        display: none;
    }
    
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
@if (session()->has('message'))
<div class="content w-50">
    <div class="container-fluid">
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="right: auto; opacity: .6;">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session()->get('message') !!}
        </div>
    </div>
</div>
@endif
@if (session()->has('error'))
<div class="content w-50">
    <div class="container-fluid">
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="right: auto; opacity: .6;">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session()->get('error') !!}
        </div>
    </div>
</div>
@endif

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">WooCommerce Orders</h1>
                    <div class="export-buttons">
                        <button type="button" class="btn btn-success" id="exportExcel">
                            <i class="bi bi-file-earmark-excel"></i> Export to Excel
                        </button>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filters-section">
                    <form id="filtersForm">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Select start date">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="text" class="form-control" id="end_date" name="end_date" placeholder="Select end date">
                            </div>
                            <div class="col-md-2">
                                <label for="order_id" class="form-label">Order ID</label>
                                <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Order ID">
                            </div>
                            <div class="col-md-3">
                                <label for="order_status" class="form-label">Order Status</label>
                                <select class="form-select" id="order_status" name="order_status[]" multiple>
                                    <option value="wc-completed">Completed</option>
                                    <option value="wc-processing">Processing</option>
                                    <option value="wc-on-hold">On Hold</option>
                                    <option value="wc-pending">Pending</option>
                                    <option value="wc-cancelled">Cancelled</option>
                                    <option value="wc-refunded">Refunded</option>
                                    <option value="wc-failed">Failed</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- DataTable -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="ordersTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Line Subtotal</th>
                                        <th>Line Discount</th>
                                        <th>Order Date</th>
                                        <th>Order Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div class="loading text-center mt-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(document).ready(function() {
    // Initialize date pickers
    flatpickr("#start_date", {
        dateFormat: "Y-m-d",
        allowInput: true
    });
    
    flatpickr("#end_date", {
        dateFormat: "Y-m-d",
        allowInput: true
    });

    // Initialize DataTable
    var table = $('#ordersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("woo-orders.data") }}',
            type: 'GET',
            data: function(d) {
                // Add filter data
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.order_id = $('#order_id').val();
                d.order_status = $('#order_status').val();
            }
        },
        columns: [
            { data: 'order_id', name: 'order_id' },
            { data: 'product_name', name: 'product_name' },
            { data: 'quantity', name: 'quantity' },
            { data: 'subtotal', name: 'subtotal' },
            { data: 'discount', name: 'discount' },
            { data: 'order_date', name: 'order_date' },
            { data: 'order_status', name: 'order_status' }
        ],
        pageLength: {{ config('wooSales.items_per_page', 25) }},
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: 'Export to Excel',
                className: 'btn btn-success',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });

    // Handle filter form submission
    $('#filtersForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    // Handle export button click
    $('#exportExcel').on('click', function() {
        var $btn = $(this);
        var originalText = $btn.text();
        
        $btn.prop('disabled', true).text('Exporting...');
        $('.loading').show();

        $.ajax({
            url: '{{ route("woo-orders.export") }}',
            type: 'POST',
            data: {
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                order_id: $('#order_id').val(),
                order_status: $('#order_status').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Client-side export for small datasets
                    var csvContent = "data:text/csv;charset=utf-8,";
                    
                    // Add headers
                    var headers = Object.keys(response.data[0]);
                    csvContent += headers.join(",") + "\r\n";
                    
                    // Add data rows
                    response.data.forEach(function(row) {
                        var values = headers.map(function(header) {
                            return '"' + (row[header] || '').toString().replace(/"/g, '""') + '"';
                        });
                        csvContent += values.join(",") + "\r\n";
                    });
                    
                    // Create download link
                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", response.filename.replace('.xlsx', '.csv'));
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    console.log("Error Response from success : " + response);
                    alert('Export failed. Please try again.');
                }
            },
            error: function() {
                console.log("Error Response from error : " + response);
                alert('Export failed. Please try again.');
            },
            complete: function() {
                $btn.prop('disabled', false).text(originalText);
                $('.loading').hide();
            }
        });
    });

    // Handle order status multi-select
    $('#order_status').on('change', function() {
        table.ajax.reload();
    });
});
</script>
@endsection 