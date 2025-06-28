<?php

namespace BoukjijTarik\WooSales\Tests;

use Orchestra\Testbench\TestCase;
use BoukjijTarik\WooSales\WooSalesServiceProvider;
use BoukjijTarik\WooSales\Models\WooOrder;
use BoukjijTarik\WooSales\Models\WooOrderItem;
use BoukjijTarik\WooSales\Models\WooOrderItemMeta;
use BoukjijTarik\WooSales\Models\WooPostMeta;
use BoukjijTarik\WooSales\Models\WooProduct;

class WooOrdersTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            WooSalesServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Configure the database connection for testing
        $app['config']->set('database.connections.woocommerce', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('wooSales.database_connection', 'woocommerce');
        $app['config']->set('wooSales.table_prefix', '');
    }

    /** @test */
    public function it_can_load_models()
    {
        $this->assertTrue(class_exists(WooOrder::class));
        $this->assertTrue(class_exists(WooOrderItem::class));
        $this->assertTrue(class_exists(WooOrderItemMeta::class));
        $this->assertTrue(class_exists(WooPostMeta::class));
        $this->assertTrue(class_exists(WooProduct::class));
    }

    /** @test */
    public function it_can_configure_database_connection()
    {
        $wooOrder = new WooOrder();
        $this->assertEquals('woocommerce', $wooOrder->getConnectionName());
    }

    /** @test */
    public function it_can_set_table_names()
    {
        $wooOrder = new WooOrder();
        $this->assertEquals('posts', $wooOrder->getTable());
        
        $wooOrderItem = new WooOrderItem();
        $this->assertEquals('woocommerce_order_items', $wooOrderItem->getTable());
    }

    /** @test */
    public function it_can_apply_scopes()
    {
        $query = WooOrder::byStatus('wc-completed');
        $this->assertStringContainsString('post_status', $query->toSql());
        
        $query = WooOrder::byDateRange('2023-01-01', '2023-12-31');
        $this->assertStringContainsString('post_date', $query->toSql());
        
        $query = WooOrder::byOrderId(123);
        $this->assertStringContainsString('ID', $query->toSql());
    }
} 