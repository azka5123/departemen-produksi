@extends('layouts.app')

@section('title', 'Dashboard')

@section('main-content')
    <x-page-header title="Dashboard" />

    <div class="row">
        <x-summary-card borderColor="primary" text="Total Transaksi Minggu Ini"
            textSecond="{{ $data['totalTransactionThisWeek'] }}" />
        <x-summary-card borderColor="success" text="Total Pendapatan Minggu Ini"
            textSecond="{{ $data['totalIncomeThisWeek'] }}" icon="fas fa-dollar-sign fa-2x" />
        <x-summary-card borderColor="primary" text="Total Transaksi Bulan Ini"
            textSecond="{{ $data['totalTransactionThisMonth'] }}" />
        <x-summary-card borderColor="success" text="Total Pendapatan Bulan Ini"
            textSecond="{{ $data['totalIncomeThisMonth'] }}" icon="fas fa-dollar-sign fa-2x" />
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-7">
            <x-form.card title="Pendapatan bulan {{ now()->format('F Y') }}" class="flex-row align-items-center">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </x-form.card>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dist/vendor/chart.js/Chart.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('myAreaChart').getContext('2d');
            var incomeData = @json(array_values($data['incomeData']));
            var days = @json(array_keys($data['incomeData']));

            var myAreaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Pendapatan',
                        data: incomeData,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'day'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 31
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp' + value.toLocaleString();
                                },
                                maxTicksLimit: 5,
                                padding: 10
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + ': Rp' + tooltipItem.yLabel.toLocaleString();
                            }
                        },
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10
                    }
                }
            });
        });
    </script>
@endpush
