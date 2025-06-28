# Migration Guide - WooCommerce Sales Report Package

This guide will help you set up the WooCommerce Sales Report Package in your Laravel 12 application.

## Prerequisites

Before starting, ensure you have:

1. A Laravel 12 application
2. Access to your WordPress/WooCommerce database
3. Database credentials with read permissions
4. Composer installed

## Step 1: Install the Package

```bash
composer require boukjijtarik/woo-sales
```

## Step 2: Configure Database Connection

### 2.1 Update Database Configuration

Add the WooCommerce database connection to your `config/database.php` file:

```php
'connections' => [
    // ... existing connections
    
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

### 2.2 Update Environment Variables

Add these variables to your `.env` file:

```env
# WooCommerce Database Connection
WOOCOMMERCE_DB_CONNECTION=woocommerce
WOOCOMMERCE_DB_HOST=your-woocommerce-host
WOOCOMMERCE_DB_PORT=3306
WOOCOMMERCE_DB_DATABASE=your-wordpress-database
WOOCOMMERCE_DB_USERNAME=your-database-username
WOOCOMMERCE_DB_PASSWORD=your-database-password

# Package Configuration
WOOCOMMERCE_TABLE_PREFIX=wp_
WOOCOMMERCE_MAX_CLIENT_EXPORT=1500
WOOCOMMERCE_ITEMS_PER_PAGE=25
```

**Important Notes:**
- Replace `your-woocommerce-host` with your actual database host
- Replace `your-wordpress-database` with your WordPress database name
- Replace `your-database-username` and `your-database-password` with your database credentials
- Adjust `WOOCOMMERCE_TABLE_PREFIX` if your WordPress installation uses a different table prefix

## Step 3: Publish Configuration

Publish the package configuration file:

```bash
php artisan vendor:publish --tag=wooSales-config
```

This will create `config/wooSales.php` in your application.

## Step 4: Verify Database Connection

Test the database connection by running:

```bash
php artisan tinker
```

Then execute:

```php
use BoukjijTarik\WooSales\Models\WooOrder;
WooOrder::count();
```

If this returns a number, your connection is working correctly.

## Step 5: Database Indexing (Recommended)

For optimal performance, add these indexes to your WooCommerce database:

```sql
-- Index for orders table
CREATE INDEX idx_posts_type_status ON wp_posts(post_type, post_status);
CREATE INDEX idx_posts_date ON wp_posts(post_date);

-- Index for order items
CREATE INDEX idx_order_items_order_id ON wp_woocommerce_order_items(order_id);

-- Index for order item meta
CREATE INDEX idx_order_itemmeta_item_id ON wp_woocommerce_order_itemmeta(order_item_id);
CREATE INDEX idx_order_itemmeta_key ON wp_woocommerce_order_itemmeta(meta_key);
```

## Step 6: Test the Installation

1. **Clear configuration cache:**
```bash
php artisan config:clear
```

2. **Access the orders page:**
Navigate to `http://your-app.com/woo-orders`

3. **Verify functionality:**
- Check if the page loads without errors
- Test the filters (date range, order ID, status)
- Test the DataTable pagination and search
- Test the export functionality

## Step 7: Customization (Optional)

### 7.1 Publish Views for Customization

If you want to customize the views:

```bash
php artisan vendor:publish --tag=wooSales-views
```

This will publish the views to `resources/views/vendor/wooSales/`.

### 7.2 Customize Configuration

Edit `config/wooSales.php` to adjust:

- Export limits
- Items per page
- Table prefix
- Database connection

## Troubleshooting

### Common Issues and Solutions

#### 1. Database Connection Error

**Error:** `SQLSTATE[HY000] [1045] Access denied for user`

**Solution:**
- Verify database credentials in `.env`
- Ensure the database user has read permissions
- Check if the database host is accessible

#### 2. No Data Displayed

**Error:** DataTable shows "No data available"

**Solution:**
- Verify WooCommerce orders exist in the database
- Check if the table prefix matches your WordPress installation
- Test the database connection manually

#### 3. Export Not Working

**Error:** Export button doesn't work or fails

**Solution:**
- Ensure Laravel Excel is installed: `composer require maatwebsite/excel`
- Check file permissions for temporary storage
- Verify browser console for JavaScript errors

#### 4. Performance Issues

**Error:** Slow loading or timeouts

**Solution:**
- Add the recommended database indexes
- Reduce the number of items per page
- Check server resources

### Debug Mode

Enable debug mode for detailed error messages:

```env
APP_DEBUG=true
```

## Security Considerations

1. **Database Permissions:**
   - Use a database user with read-only permissions
   - Limit access to only the WooCommerce database
   - Use strong passwords

2. **Environment Variables:**
   - Never commit `.env` files to version control
   - Use different credentials for development and production

3. **Network Security:**
   - If the WooCommerce database is on a different server, ensure secure network access
   - Use SSL connections when possible

## Production Deployment

1. **Set proper environment variables for production**
2. **Disable debug mode:**
```env
APP_DEBUG=false
```

3. **Optimize for production:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. **Monitor performance and adjust settings as needed**

## Support

If you encounter issues not covered in this guide:

1. Check the main README.md file
2. Review Laravel and WooCommerce documentation
3. Open an issue on the package repository
4. Contact the package maintainer

## Next Steps

After successful installation:

1. Explore the filtering options
2. Test export functionality with different dataset sizes
3. Customize the views if needed
4. Monitor performance and optimize as required
5. Consider adding custom features by extending the package classes 