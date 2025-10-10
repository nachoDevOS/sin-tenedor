@extends('voyager::master')

@section('page_title', 'Ventas')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-cart-shopping"></i> Ventas
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_sales'))
                                <a href="{{ route('sales.create') }}" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Crear</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-5">
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
                            <div class="col-sm-3" style="margin-bottom: 10px">
                                <input type="text" id="input-search" placeholder="🔍 Buscar..." class="form-control">
                            </div>
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('sale'))
        <div id="popup-button">
            <div class="col-md-12" style="padding-top: 5px">
                <h4 class="text-muted">Desea imprimir el comprobante?</h4>
            </div>
            <div class="col-md-12 text-right">
                <button onclick="javascript:$('#popup-button').fadeOut('fast')" class="btn btn-default">Cerrar</button>
                <a id="btn-print" onclick="printTicket('{{ setting('servidores.print') }}',{{ json_encode(session('sale')) }}, '{{ url('admin/sales/ticket') }}')" title="Imprimir" class="btn btn-danger">Imprimir <i
                        class="glyphicon glyphicon-print"></i></a>
            </div>
        </div>
    @endif





    @include('partials.modal-delete')
    @include('partials.modal-success')




@stop

@section('css')
    <style>
        #popup-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            height: 100px;
            background-color: white;
            box-shadow: 5px 5px 15px grey;
            z-index: 1000;

            /* Mostrar/ocultar popup */
            /* @if (session('sale'))
            */ animation: show-animation 1s;
            /* @else
            */ right: -500px;
            /* @endif
            */
        }

        @keyframes show-animation {
            0% {
                right: -500px;
            }

            100% {
                right: 20px;
            }
        }
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>

    <!-- jQuery y Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Incluir el nuevo archivo JS de impresión -->
    <script src="{{ asset('js/printTicket.js') }}"></script>

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script>
        var countPage = 10,
            order = 'id',
            typeOrder = 'desc';
        let sale_id = null;
        $(document).ready(() => {
            list();

            $('#status').change(function() {
                list();
            });

            $('#typeSale').change(function() {
                list();
            });

            $('#input-search').on('keyup', function(e) {
                if (e.keyCode == 13) {
                    list();
                }
            });

            $('#select-paginate').change(function() {
                countPage = $(this).val();

                list();
            });


            @if (session('sale'))
                // alert(@json(json_decode(session('sale'), true)));
                printTicket('{{ setting('servidores.print') }}', @json(json_decode(session('sale'), true)), '{{ url('admin/sales/ticket') }}');
            @endif


            // Ocultar popup de impresión
            setTimeout(() => {
                $('#popup-button').fadeOut('fast');
            }, 8000);

        });


        function list(page = 1) {
            $('#div-results').loading({
                message: 'Cargando...'
            });

            let url = '{{ url('admin/sales/ajax/list') }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            let status = $("#status").val();
            let typeSale = $("#typeSale").val();


            $.ajax({
                // url: `${url}/${search}?paginate=${countPage}&page=${page}`,
                url: `${url}?search=${search}&paginate=${countPage}&page=${page}&status=${status}&typeSale=${typeSale}`,

                type: 'get',

                success: function(result) {
                    $("#div-results").html(result);
                    $('#div-results').loading('toggle');
                }
            });

        }

        $('.success_form').submit(function(e) {
            $('.btn-form-submit').attr('disabled', true);
            $('.btn-form-submit').val('Entregando...');
        });


        function deleteItem(url) {
            $('#delete_form').attr('action', url);
        }

        function successItem(url) {
            $('#success_form').attr('action', url);
        }
    </script>
@stop
