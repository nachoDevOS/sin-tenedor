@extends('voyager::master')

@section('page_header')
    @php
        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');       
    @endphp
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Hola, {{ Auth::user()->name }}</h2>
                                <p class="text-muted">Resumen de rendimiento - {{date('d').' de '.$meses[intval(date('m'))].' '.date('Y')}}</p>
                            </div>
                            {{-- <div class="col-md-4 text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" id="refresh-dashboard">
                                        <i class="voyager-refresh"></i> Actualizar
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#" data-range="today">Hoy</a></li>
                                        <li><a href="#" data-range="week">Esta semana</a></li>
                                        <li><a href="#" data-range="month">Este mes</a></li>
                                        <li><a href="#" data-range="year">Este año</a></li>
                                    </ul>
                                </div>
                            </div> --}}
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
   
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        @include('voyager::dimmers')
        @php
            $sales = $global_index['sales'];
            // dump($sales);

            $amountDaytotal = $global_index['sales']
                ->where('deleted_at', null)
                ->filter(function ($sale) {
                    return $sale->created_at->format('Y-m-d') === date("Y-m-d");
                })
                ->sum('amount');

            $saleDaytotal = $global_index['sales']
                ->where('deleted_at', null)
                ->filter(function ($sale) {
                    return $sale->created_at->format('Y-m-d') === date("Y-m-d");
                })
                ->count();

            $customer = $global_index['people']
                ->count();

            $monthInteractive = $global_index['monthInteractive'];
            // $monthInteractive = $global_index['monthInteractive'];

        @endphp

        <!-- KPI Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-bordered dashboard-kpi">
                    <div class="panel-body text-center">
                        <div class="kpi-icon">
                            <i class="voyager-dollar"></i>
                        </div>
                        <h3 class="kpi-value">Bs. {{number_format($amountDaytotal, 2, ',','.')}}</h3>
                        <p class="kpi-label">Ventas Total del Día</p>
                        {{-- <div class="kpi-trend trend-up">
                            <i class="voyager-up"></i> 12.5%
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered dashboard-kpi">
                    <div class="panel-body text-center">
                        <div class="kpi-icon">
                            <i class="voyager-bag"></i>
                        </div>
                        <h3 class="kpi-value">{{$saleDaytotal}}</h3>
                        <p class="kpi-label">Pedidos del Día</p>
                        {{-- <div class="kpi-trend trend-up">
                            <i class="voyager-up"></i> 5.2%
                        </div> --}}
                    </div>
                </div>
            </div>            
            <div class="col-md-3">
                <div class="panel panel-bordered dashboard-kpi">
                    <div class="panel-body text-center">
                        <div class="kpi-icon">
                            <i class="voyager-bar-chart"></i>
                        </div>
                        <h3 class="kpi-value">Bs. {{$amountDaytotal? number_format($amountDaytotal/$saleDaytotal, 2, ',','.') : 0}}</h3>
                        <p class="kpi-label">Ticket Promedio</p>
                        {{-- <div class="kpi-trend trend-up">
                            <i class="voyager-up"></i> 8.7%
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered dashboard-kpi">
                    <div class="panel-body text-center">
                        <div class="kpi-icon">
                            <i class="voyager-person"></i>
                        </div>
                        <h3 class="kpi-value">{{$customer}}</h3>
                        <p class="kpi-label">Clientes</p>
                        {{-- <div class="kpi-trend trend-down">
                            <i class="voyager-down"></i> 3.1%
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gráfico de ventas por día de la semana -->
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Ventas por Día de la Semana</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="ventasDiasChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de productos más vendidos -->
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">5 Productos Más Vendidos del Día</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="topProductosChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gráfico de ventas mensuales -->
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Ventas Mensuales</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="ventasMensualesChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de comparación año actual vs año anterior -->
            {{-- <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Comparación Anual</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="comparacionAnualChart" height="250"></canvas>
                    </div>
                </div>
            </div> --}}
        </div>
        
        <div class="row">
            <!-- Tabla de últimos pedidos -->
            {{-- <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Pedidos del Día</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th># Pedido</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#12345</td>
                                        <td>Juan Pérez</td>
                                        <td>20 Nov 2023</td>
                                        <td>$125.80</td>
                                        <td><span class="label label-success">Completado</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Ver</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#12344</td>
                                        <td>María García</td>
                                        <td>20 Nov 2023</td>
                                        <td>$89.50</td>
                                        <td><span class="label label-warning">Procesando</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Ver</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#12343</td>
                                        <td>Carlos López</td>
                                        <td>19 Nov 2023</td>
                                        <td>$210.00</td>
                                        <td><span class="label label-success">Completado</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Ver</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#12342</td>
                                        <td>Ana Martínez</td>
                                        <td>19 Nov 2023</td>
                                        <td>$56.90</td>
                                        <td><span class="label label-danger">Cancelado</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Ver</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#12341</td>
                                        <td>Pedro Sánchez</td>
                                        <td>18 Nov 2023</td>
                                        <td>$178.30</td>
                                        <td><span class="label label-success">Completado</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Ver</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>

                            <div class="col-sm-2" style="margin-bottom: 10px">
                                <select id="status" name="status" class="form-control select2">
                                    <option value="" selected>Todos</option>
                                    <option value="Pendiente">Pendientes</option>
                                    <option value="Entregado">Entregados</option>
                                </select>
                            </div>
                            <div class="col-sm-2" style="margin-bottom: 10px">
                                <select id="typeSale" name="typeSale" class="form-control select2">
                                    <option value="" selected>Todos</option>
                                    <option value="Llevar">Para LLevar</option>
                                    <option value="Mesa">Para Mesa</option>
                                </select>
                            </div>
                            <div class="col-sm-2" style="margin-bottom: 10px">
                                <input type="text" id="input-search" placeholder="🔍 Buscar..." class="form-control">
                            </div>
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@stop

@section('css')
    <style>
        .dashboard-kpi {
            transition: all 0.3s ease;
        }
        .dashboard-kpi:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .kpi-icon {
            font-size: 24px;
            color: #22A7F0;
            margin-bottom: 10px;
        }
        .kpi-value {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }
        .kpi-label {
            color: #6c757d;
            margin-bottom: 5px;
        }
        .kpi-trend {
            font-size: 12px;
            font-weight: bold;
        }
        .trend-up {
            color: #2ecc71;
        }
        .trend-down {
            color: #e74c3c;
        }
        .panel-heading .btn-group {
            margin-top: -5px;
        }
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
    </style>
@stop

@section('javascript')
    <!-- Incluir Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function(){   
            // Configuración de rangos de fecha
            $('.dropdown-menu a').click(function(e) {
                e.preventDefault();
                let range = $(this).data('range');
                $('#refresh-dashboard').html('<i class="voyager-refresh"></i> Actualizando...');
                
                // Simular carga de datos
                setTimeout(function() {
                    $('#refresh-dashboard').html('<i class="voyager-refresh"></i> Actualizar');
                    toastr.success('Datos actualizados para el período: ' + range);
                }, 1500);
            });
            console.log(@json($monthInteractive));
            const monthData = @json($monthInteractive);
            const ventasMensualesData = {
                labels: monthData.map(item => item.month.substring(0, 3)+'-'+item.year),
                datasets: [{
                    label: 'Ventas',
                    data: monthData.map(item => item.amount),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',

                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            };

            // Datos para el gráfico de productos más vendidos
            const productTop5Day = @json($global_index['productTop5Day']);

            const topProductosData = {
                labels: productTop5Day.map(item => item.name),
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: productTop5Day.map(item => item.total_quantity),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Datos para el gráfico de ventas por día de la semana
            $weekDays = @json($global_index['weekDays']);
            const ventasDiasData = {
                labels: $weekDays.map(item => item.name+' ('+item.dateInverso+')'),

                datasets: [{
                    label: 'Ventas promedio',
                    data: $weekDays.map(item => item.amount),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            };

            const comparacionAnualData = {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [
                    {
                        label: '2022',
                        data: [100000, 150000, 130000, 160000, 190000, 210000, 230000, 200000, 220000, 240000, 260000, 280000],
                        borderColor: 'rgba(201, 203, 207, 1)',
                        backgroundColor: 'rgba(201, 203, 207, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: '2023',
                        data: [120000, 190000, 150000, 180000, 210000, 230000, 250000, 220000, 240000, 260000, 280000, 300000],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            };

            // Configuración común para los gráficos
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            };
            
            const pieChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            };

            // Crear los gráficos
            new Chart(document.getElementById('ventasMensualesChart'), {
                type: 'bar',
                data: ventasMensualesData,
                options: chartOptions
            });

            new Chart(document.getElementById('topProductosChart'), {
                type: 'pie',
                data: topProductosData,
                options: pieChartOptions
            });

            new Chart(document.getElementById('ventasDiasChart'), {
                type: 'line',
                data: ventasDiasData,
                options: chartOptions
            });

            new Chart(document.getElementById('comparacionAnualChart'), {
                type: 'line',
                data: comparacionAnualData,
                options: chartOptions
            });
        });
    </script>
@stop