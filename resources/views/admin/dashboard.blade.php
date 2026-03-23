@extends('admin.master')

@section('content')
<style>
    .dashboard-wrapper {
        padding: 20px;
        background: #f4f6f9;
        min-height: 100vh;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .dashboard-header h2 {
        margin: 0;
        font-size: 30px;
        font-weight: 700;
        color: #1f2937;
    }

    .dashboard-header p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .dashboard-btn {
        background: #0d6efd;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.20);
    }

    .dashboard-btn:hover {
        background: #0b5ed7;
        color: #fff;
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        margin-bottom: 24px;
        min-height: 150px;
    }

    .stat-card .title {
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .stat-card .value {
        font-size: 30px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
        line-height: 1.2;
    }

    .stat-card .change {
        font-size: 13px;
        font-weight: 600;
    }

    .stat-card .change.up {
        color: #198754;
    }

    .stat-card .change.down {
        color: #dc3545;
    }

    .stat-icon {
        position: absolute;
        right: 20px;
        top: 20px;
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 24px;
    }

    .bg-blue-gradient {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .bg-green-gradient {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .bg-orange-gradient {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .bg-red-gradient {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .chart-card,
    .list-card,
    .table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        margin-bottom: 24px;
    }

    .chart-card .card-header,
    .list-card .card-header,
    .table-card .card-header {
        background: transparent;
        border-bottom: 1px solid #edf2f7;
        padding: 18px 20px;
    }

    .chart-card .card-header h4,
    .list-card .card-header h4,
    .table-card .card-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .chart-card .card-body,
    .list-card .card-body,
    .table-card .card-body {
        padding: 20px;
    }

    .chart-box {
        position: relative;
        height: 350px;
    }

    .chart-box.small {
        height: 350px;
    }

    .recent-orders-table {
        margin-bottom: 0;
    }

    .recent-orders-table thead th {
        background: #f8fafc;
        color: #374151;
        font-size: 13px;
        font-weight: 700;
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 16px;
        white-space: nowrap;
    }

    .recent-orders-table tbody td {
        padding: 14px 16px;
        font-size: 14px;
        color: #374151;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }

    .recent-orders-table tbody tr:hover {
        background: #f9fafb;
    }

    .custom-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-completed {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge-shipped {
        background: #cff4fc;
        color: #055160;
    }

    .badge-cancelled {
        background: #f8d7da;
        color: #842029;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #eef2f7;
    }

    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-item:first-child {
        padding-top: 0;
    }

    .info-item h6 {
        margin: 0 0 4px;
        font-size: 15px;
        font-weight: 600;
        color: #111827;
    }

    .info-item small {
        color: #6b7280;
    }

    .amount-green {
        color: #198754;
        font-weight: 700;
    }

    .stock-low {
        color: #dc3545;
        font-weight: 600;
    }

    @media (max-width: 991px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .dashboard-header h2 {
            font-size: 26px;
        }

        .stat-card .value {
            font-size: 24px;
        }

        .chart-box,
        .chart-box.small {
            height: 300px;
        }
    }

    @media (max-width: 767px) {
        .dashboard-wrapper {
            padding: 15px;
        }

        .chart-box,
        .chart-box.small {
            height: 260px;
        }
    }
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-header">
        <div>
            <h2>Dashboard</h2>
            <p>Welcome back, Admin User</p>
        </div>
        <button class="dashboard-btn">
            <i class="fa fa-download"></i> Download Report
        </button>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon bg-blue-gradient">
                    <i class="fa fa-dollar"></i>
                </div>
                <div class="title">Total Revenue</div>
                <div class="value">$24,500</div>
                <div class="change up">
                    <i class="fa fa-arrow-up"></i> 12.5% from last month
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon bg-green-gradient">
                    <i class="fa fa-shopping-bag"></i>
                </div>
                <div class="title">Total Orders</div>
                <div class="value">1,245</div>
                <div class="change up">
                    <i class="fa fa-arrow-up"></i> 8.2% from last month
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon bg-orange-gradient">
                    <i class="fa fa-users"></i>
                </div>
                <div class="title">Total Customers</div>
                <div class="value">3,540</div>
                <div class="change down">
                    <i class="fa fa-arrow-down"></i> 2.4% from last month
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon bg-red-gradient">
                    <i class="fa fa-store"></i>
                </div>
                <div class="title">Active Vendors</div>
                <div class="value">87</div>
                <div class="change up">
                    <i class="fa fa-arrow-up"></i> 5.1% from last month
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="card-header">
                    <h4>Sales Overview</h4>
                </div>
                <div class="card-body">
                    <div class="chart-box">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="chart-card">
                <div class="card-header">
                    <h4>Revenue Sources</h4>
                </div>
                <div class="card-body">
                    <div class="chart-box small">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h4>Recent Orders</h4>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-responsive">
                        <table class="table recent-orders-table">
                            <thead>
                                <tr>
                                    <th>#Order ID</th>
                                    <th>Customer</th>
                                    <th>Vendor</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#1001</td>
                                    <td>John Smith</td>
                                    <td>Fashion Store</td>
                                    <td>$120</td>
                                    <td><span class="custom-badge badge-pending">Pending</span></td>
                                    <td>22 Mar 2026</td>
                                </tr>
                                <tr>
                                    <td>#1002</td>
                                    <td>Sarah Khan</td>
                                    <td>Electronics Hub</td>
                                    <td>$450</td>
                                    <td><span class="custom-badge badge-completed">Completed</span></td>
                                    <td>22 Mar 2026</td>
                                </tr>
                                <tr>
                                    <td>#1003</td>
                                    <td>David Lee</td>
                                    <td>Grocery World</td>
                                    <td>$80</td>
                                    <td><span class="custom-badge badge-shipped">Shipped</span></td>
                                    <td>21 Mar 2026</td>
                                </tr>
                                <tr>
                                    <td>#1004</td>
                                    <td>Emma Watson</td>
                                    <td>Beauty Shop</td>
                                    <td>$230</td>
                                    <td><span class="custom-badge badge-cancelled">Cancelled</span></td>
                                    <td>21 Mar 2026</td>
                                </tr>
                                <tr>
                                    <td>#1005</td>
                                    <td>Michael Roy</td>
                                    <td>Sports Zone</td>
                                    <td>$310</td>
                                    <td><span class="custom-badge badge-completed">Completed</span></td>
                                    <td>20 Mar 2026</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="list-card">
                <div class="card-header">
                    <h4>Top Vendors</h4>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div>
                            <h6>Fashion Store</h6>
                            <small>245 Orders</small>
                        </div>
                        <div class="amount-green">$5,400</div>
                    </div>

                    <div class="info-item">
                        <div>
                            <h6>Electronics Hub</h6>
                            <small>180 Orders</small>
                        </div>
                        <div class="amount-green">$4,850</div>
                    </div>

                    <div class="info-item">
                        <div>
                            <h6>Beauty Shop</h6>
                            <small>162 Orders</small>
                        </div>
                        <div class="amount-green">$3,920</div>
                    </div>

                    <div class="info-item">
                        <div>
                            <h6>Sports Zone</h6>
                            <small>140 Orders</small>
                        </div>
                        <div class="amount-green">$3,100</div>
                    </div>
                </div>
            </div>

            <div class="list-card">
                <div class="card-header">
                    <h4>Low Stock Products</h4>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div>
                            <h6>iPhone 14 Case</h6>
                            <small class="stock-low">Only 4 items left</small>
                        </div>
                    </div>

                    <div class="info-item">
                        <div>
                            <h6>Wireless Mouse</h6>
                            <small class="stock-low">Only 7 items left</small>
                        </div>
                    </div>

                    <div class="info-item">
                        <div>
                            <h6>Women Handbag</h6>
                            <small class="stock-low">Only 3 items left</small>
                        </div>
                    </div>

                    <div class="info-item">
                        <div>
                            <h6>Running Shoes</h6>
                            <small class="stock-low">Only 6 items left</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [
                {
                    label: 'Sales',
                    data: [3200, 4100, 3800, 5200, 6100, 7200, 6800],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.10)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d6efd'
                },
                {
                    label: 'Orders',
                    data: [220, 280, 250, 340, 410, 470, 430],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#198754'
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.03)'
                    }
                }
            }
        }
    });

    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'doughnut',
        data: {
            labels: ['Direct Sales', 'Vendor Commission', 'Subscriptions', 'Other'],
            datasets: [{
                data: [45, 30, 15, 10],
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            cutout: '55%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
