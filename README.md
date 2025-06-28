# WooCommerce Sales Report Package for Laravel 12

A Laravel 12 package that integrates directly with a WordPress WooCommerce MySQL database to fetch, display, and export order details using Eloquent ORM.

## Features

- **Direct Database Integration**: Connects directly to WooCommerce MySQL database using Eloquent
- **Advanced Filtering**: Filter by date range, order ID, and order status
- **DataTable Display**: Paginated DataTable with search and sorting capabilities
- **Smart Export System**: 
  - Client-side export for datasets ≤ 1500 items
  - Server-side export for datasets > 1500 items using Laravel Excel
- **Performance Optimized**: Efficient queries with proper indexing support
- **Modern UI**: Bootstrap 5 with DataTables and Flatpickr date picker

## Requirements

- PHP 8.1+
- Laravel 12.0+
- MySQL/MariaDB (WooCommerce database)
- Composer

## Installation

1. **Install the package via Composer:**

```bash
composer require boukjijtarik/woo-sales
```

2. **Publish the configuration file:**

```bash
php artisan vendor:publish --tag=wooSales-config
```

3. **Publish the views (optional):**

```bash
php artisan vendor:publish --tag=wooSales-views
```

## Configuration

### 1. Database Connection

Add a new database connection in your `config/database.php` file:

```php
'connections' => [
    // ... other connections
    
    'woocommerce' => [
        'driver' => 'mysql',
        'host' => env('WOOCOMMERCE_DB_HOST', '127.0.0.1'),
        'port' => env('WOOCOMMERCE_DB_PORT', '3306'),
        'database' => env('WOOCOMMERCE_DB_DATABASE', 'wordpress'),
        'username' => env('WOOCOMMERCE_DB_USERNAME', 'root'),
        'password' => env('WOOCOMMERCE_DB_PASSWORD', ''),
        'unix_socket' => env('WOOCOMMERCE_DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
],
```

### 2. Environment Variables

Add these variables to your `.env` file:

# Package Configuration
MAX_CLIENT_EXPORT=1500
ITEMS_PER_PAGE=25
```

### 3. Package Configuration

The package configuration file (`config/wooSales.php`) contains:

- `database_connection`: The database connection name to use
- `max_client_export`: Maximum items for client-side export (default: 1500)
- `table_prefix`: WordPress table prefix (default: 'wp_')
- `items_per_page`: Number of items per page in DataTable (default: 25)

## Usage

### Access the Orders Page

Once installed and configured, you can access the WooCommerce orders page at:

```
/woo-orders
```

### Available Routes

- `GET /woo-orders` - Display the orders page
- `GET /woo-orders/data` - Get orders data for DataTable (AJAX)
- `POST /woo-orders/export` - Export orders to Excel

### Features

#### 1. Filtering

- **Date Range**: Filter orders by creation date
- **Order ID**: Search for specific order IDs
- **Order Status**: Multi-select filter for order statuses (completed, processing, etc.)

#### 2. Data Display

The DataTable shows:
- Order ID
- Product Name
- Line Quantity
- Line Subtotal
- Line Discount
- Order Date
- Order Status

#### 3. Export Options

- **Small Datasets (≤1500 items)**: Client-side export using DataTables
- **Large Datasets (>1500 items)**: Server-side export using Laravel Excel

## Database Schema

The package works with the following WooCommerce tables:

- `wp_posts` - Orders and products
- `wp_woocommerce_order_items` - Order line items
- `wp_woocommerce_order_itemmeta` - Order item metadata
- `wp_postmeta` - Order and product metadata

## Models

The package includes these Eloquent models:

- `WooOrder` - Maps to `wp_posts` (shop orders)
- `WooOrderItem` - Maps to `wp_woocommerce_order_items`
- `WooOrderItemMeta` - Maps to `wp_woocommerce_order_itemmeta`
- `WooPostMeta` - Maps to `wp_postmeta`
- `WooProduct` - Maps to `wp_posts` (products)

## Performance Considerations

1. **Database Indexing**: Ensure proper indexes on:
   - `wp_posts.ID` and `wp_posts.post_type`
   - `wp_posts.post_date`
   - `wp_posts.post_status`
   - `wp_woocommerce_order_items.order_id`

2. **Query Optimization**: The package uses eager loading and optimized queries to minimize database load.

3. **Export Performance**: Large exports are handled server-side to prevent browser memory issues.

## Security

- All inputs are sanitized and validated
- Uses Laravel's built-in CSRF protection
- Database queries use parameterized statements
- Proper database user permissions are required

## Customization

### Customizing Views

If you've published the views, you can customize them in `resources/views/vendor/wooSales/`.

### Customizing Export

You can extend the `WooOrdersExport` class to customize the export format and styling.

### Adding Custom Filters

Extend the `WooOrdersController` to add custom filtering logic.

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify your WooCommerce database credentials
   - Ensure the database user has read permissions
   - Check if the table prefix is correct

2. **No Data Displayed**
   - Verify that WooCommerce orders exist in the database
   - Check if the table prefix matches your WordPress installation
   - Ensure the database connection is working

3. **Export Not Working**
   - Check if Laravel Excel is properly installed
   - Verify file permissions for temporary storage
   - Check browser console for JavaScript errors

### Debug Mode

Enable Laravel's debug mode to see detailed error messages:

```env
APP_DEBUG=true
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please open an issue on the GitHub repository or contact the maintainer.

## Changelog

### Version 1.0.0
- Initial release
- Basic order data retrieval and display
- Filtering and export functionality
- DataTable integration
- Laravel Excel integration for large exports 