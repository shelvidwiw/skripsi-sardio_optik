<?php

namespace Tests\Unit\Iterasi3;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    #[Test]
    public function index_displays_dashboard_with_order_statistics(): void
    {
        $orderMock = Mockery::mock('alias:' . Order::class);

        $orderMock->shouldReceive('count')
            ->once()
            ->andReturn(10);

        $pendingQuery = Mockery::mock();
        $pendingQuery->shouldReceive('count')
            ->once()
            ->andReturn(3);

        $orderMock->shouldReceive('where')
            ->once()
            ->with('status', 'pending')
            ->andReturn($pendingQuery);

        $diprosesQuery = Mockery::mock();
        $diprosesQuery->shouldReceive('count')
            ->once()
            ->andReturn(4);

        $orderMock->shouldReceive('where')
            ->once()
            ->with('status', 'diproses')
            ->andReturn($diprosesQuery);

        $selesaiQuery = Mockery::mock();
        $selesaiQuery->shouldReceive('count')
            ->once()
            ->andReturn(3);

        $orderMock->shouldReceive('where')
            ->once()
            ->with('status', 'selesai')
            ->andReturn($selesaiQuery);

        $controller = new DashboardController();

        $response = $controller->index();

        $this->assertInstanceOf(View::class, $response);

        $this->assertEquals(
            'admin.dashboard',
            $response->name()
        );

        $this->assertEquals(
            10,
            $response->getData()['totalOrders']
        );

        $this->assertEquals(
            3,
            $response->getData()['pending']
        );

        $this->assertEquals(
            4,
            $response->getData()['diproses']
        );

        $this->assertEquals(
            3,
            $response->getData()['selesai']
        );
    }

    #[Test]
    public function index_displays_zero_when_there_are_no_orders(): void
    {
        $orderMock = Mockery::mock('alias:' . Order::class);

        $orderMock->shouldReceive('count')
            ->once()
            ->andReturn(0);

        $pendingQuery = Mockery::mock();
        $pendingQuery->shouldReceive('count')
            ->once()
            ->andReturn(0);

        $orderMock->shouldReceive('where')
            ->once()
            ->with('status', 'pending')
            ->andReturn($pendingQuery);

        $diprosesQuery = Mockery::mock();
        $diprosesQuery->shouldReceive('count')
            ->once()
            ->andReturn(0);

        $orderMock->shouldReceive('where')
            ->once()
            ->with('status', 'diproses')
            ->andReturn($diprosesQuery);

        $selesaiQuery = Mockery::mock();
        $selesaiQuery->shouldReceive('count')
            ->once()
            ->andReturn(0);

        $orderMock->shouldReceive('where')
            ->once()
            ->with('status', 'selesai')
            ->andReturn($selesaiQuery);

        $controller = new DashboardController();

        $response = $controller->index();

        $this->assertEquals(
            0,
            $response->getData()['totalOrders']
        );

        $this->assertEquals(
            0,
            $response->getData()['pending']
        );

        $this->assertEquals(
            0,
            $response->getData()['diproses']
        );

        $this->assertEquals(
            0,
            $response->getData()['selesai']
        );
    }
}