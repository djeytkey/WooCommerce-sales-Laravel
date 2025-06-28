# Package Structure

This document outlines the complete structure of the WooCommerce Order Export Package.

```
wooSales/
├── composer.json                 # Package dependencies and autoloading
├── README.md                     # Main documentation
├── MIGRATION_GUIDE.md           # Detailed setup instructions
├── PACKAGE_STRUCTURE.md         # This file
├── .gitignore                   # Git ignore rules
├── phpunit.xml                  # PHPUnit configuration
│
├── config/
│   └── wooSales.php            # Package configuration
│
├── src/
│   ├── WooSalesServiceProvider.php  # Main service provider
│   │
│   ├── Models/                 # Eloquent models
│   │   ├── WooOrder.php        # Maps to wp_posts (orders)
│   │   ├── WooOrderItem.php    # Maps to wp_woocommerce_order_items
│   │   ├── WooOrderItemMeta.php # Maps to wp_woocommerce_order_itemmeta
│   │   ├── WooPostMeta.php     # Maps to wp_postmeta
│   │   └── WooProduct.php      # Maps to wp_posts (products)
│   │
│   ├── Http/
│   │   └── Controllers/
│   │       └── WooOrdersController.php  # Main controller
│   │
│   └── Exports/
│       └── WooOrdersExport.php  # Laravel Excel export class
│
├── routes/
│   └── web.php                 # Package routes
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php   # Main layout with dependencies
│       └── orders/
│           └── index.blade.php # Main orders page
│
└── tests/
    └── WooOrdersTest.php       # Basic test suite
```

## File Descriptions

### Core Files

- **composer.json**: Defines package metadata, dependencies, and autoloading
- **README.md**: Comprehensive documentation with installation and usage instructions
- **MIGRATION_GUIDE.md**: Step-by-step setup guide for database configuration
- **.gitignore**: Excludes vendor files and sensitive data from version control
- **phpunit.xml**: PHPUnit testing configuration

### Configuration

- **config/wooSales.php**: Package configuration including database connection, export limits, and table prefix

### Service Provider

- **src/WooSalesServiceProvider.php**: Main service provider that registers routes, views, and configurations

### Models

- **src/Models/WooOrder.php**: Eloquent model for WooCommerce orders (wp_posts table)
- **src/Models/WooOrderItem.php**: Model for order line items (wp_woocommerce_order_items table)
- **src/Models/WooOrderItemMeta.php**: Model for order item metadata (wp_woocommerce_order_itemmeta table)
- **src/Models/WooPostMeta.php**: Model for post metadata (wp_postmeta table)
- **src/Models/WooProduct.php**: Model for WooCommerce products (wp_posts table)

### Controllers

- **src/Http/Controllers/WooOrdersController.php**: Main controller handling data retrieval, filtering, and export

### Exports

- **src/Exports/WooOrdersExport.php**: Laravel Excel export class for large dataset exports

### Routes

- **routes/web.php**: Package routes for orders page, data API, and export functionality

### Views

- **resources/views/layouts/app.blade.php**: Main layout with Bootstrap, DataTables, and other dependencies
- **resources/views/orders/index.blade.php**: Main orders page with filters, DataTable, and export functionality

### Tests

- **tests/WooOrdersTest.php**: Basic test suite for package functionality

## Key Features Implemented

1. **Direct Database Integration**: Connects to WooCommerce MySQL database using Eloquent
2. **Advanced Filtering**: Date range, order ID, and order status filters
3. **DataTable Display**: Paginated table with search and sorting
4. **Smart Export System**: Client-side for small datasets, server-side for large datasets
5. **Performance Optimized**: Efficient queries with proper indexing support
6. **Modern UI**: Bootstrap 5 with DataTables and Flatpickr
7. **Security**: Input validation, CSRF protection, and proper database permissions
8. **Extensible**: Easy to customize and extend

## Installation Commands

```bash
# Install package
composer require boukjijtarik/woo-sales

# Publish configuration
php artisan vendor:publish --tag=wooSales-config

# Publish views (optional)
php artisan vendor:publish --tag=wooSales-views

# Run tests
./vendor/bin/phpunit
```

## Access Points

- **Main Page**: `/woo-orders`
- **Data API**: `/woo-orders/data`
- **Export API**: `/woo-orders/export`

## Dependencies

- Laravel 12.0+
- PHP 8.1+
- maatwebsite/excel (Laravel Excel)
- DataTables (JavaScript)
- Bootstrap 5
- Flatpickr (Date picker) 