@extends('voyager::master')

@section('page_title', 'Viendo Caja')


@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-cash-register"></i> Cajeros
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_cashiers'))
                                <a href="{{ route('cashiers.create') }}" class="btn btn-success">
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
                            <div class="col-sm-10">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="input-search" class="form-control">
                            </div>
                            {{-- <div class="col-md-12 text-right" style="margin-bottom: 30px !important">
                                <label class="radio-inline"><input type="radio" class="radio-status" name="status" value="">Todas</label>
                                <label class="radio-inline"><input type="radio" class="radio-status" name="status" value="abierta" checked>Abiertas</label>
                                <label class="radio-inline"><input type="radio" class="radio-status" name="status" value="cerrada">Cerradas</label>
                            </div> --}}
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px; padding-bottom: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @include('cashier.partials.agregar-gasto-modal') --}}
@stop

@section('css')
    <style>

    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';
        $(document).ready(() => {

            $('#select-cashier_movement_category_id').select2({dropdownParent: $('#agregar-gasto-modal')});

            @if(session('cashier_id'))
                let user =@json(session('user'))
                let cashier_id = "{{ session('cashier_id') }}";
                socket.emit(`reload notificationCashierOpen`, {cashierRegister_id: cashier_id, auth: user});
            @endif

            list();
            
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('.radio-status').click(function(e){
                list();
            });

            $('#select-paginate').change(function(){
                countPage = $(this).val();
                list();
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});
            let url = '{{ route("cashiers.list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}?search=${search}&paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(response){
                    $('#div-results').html(response);
                    $('#div-results').loading('toggle');
                }
            });
        }

        function openWindow(id){
            window.open("{{ route('print.open')}}/"+id, 'Apertura de caja', `width=1000, height=700`);
        }

        function closeWindow(id){
            window.open("{{ route('print.close')}}/"+id, 'Apertura de caja', `width=1000, height=700`);
        }
    </script>
@stop
