@extends('layouts.admin')

@section('content')
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Dashboard</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Menu</h6>
                            <h2 class="mt-2 mb-0">{{ $totalMenu }}</h2>
                        </div>
                        <i class="fas fa-utensils fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Pesanan Hari Ini</h6>
                            <h2 class="mt-2 mb-0">{{ $ordersToday }}</h2>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Reservasi Hari Ini</h6>
                            <h2 class="mt-2 mb-0">{{ $reservationsToday }}</h2>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Pendapatan (30 Hari)</h6>
                            <h2 class="mt-2 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mb-4">
        <!-- Line Chart -->
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Pesanan & Pendapatan</h5>
                </div>
                <div class="card-body">
                    <div style="height: 250px">
                        <canvas id="orderRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pie Chart -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Distribusi Status Pesanan</h5>
                </div>
                <div class="card-body d-flex align-items-center">
                    <div style="height: 250px" class="w-100">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">5 Pesanan Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Menu</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>#{{ $order['id'] }}</td>
                            <td>{{ $order['customer_name'] }}</td>
                            <td>{{ $order['menu_items'] }}</td>
                            <td>Rp {{ number_format($order['total'], 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning',
                                        'processing' => 'bg-info',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger'
                                    ][$order['status']] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order['id']) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pesanan terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Line Chart - Orders & Revenue
    const orderRevenueChart = new Chart(document.getElementById('orderRevenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Jumlah Pesanan',
                data: {!! json_encode($chartData['orderCounts']) !!},
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartData['revenues']) !!},
                borderColor: '#0dcaf0',
                backgroundColor: 'rgba(13, 202, 240, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Pesanan'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Pendapatan (Rp)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Pie Chart - Order Status Distribution
    const orderStatusChart = new Chart(document.getElementById('orderStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
            datasets: [{
                data: {!! json_encode($chartData['statusCounts']) !!},
                backgroundColor: [
                    '#ffc107', // warning
                    '#0dcaf0', // info
                    '#198754', // success
                    '#dc3545'  // danger
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
@endsection
