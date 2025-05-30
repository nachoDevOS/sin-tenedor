@extends('voyager::master')

@section('page_title', 'Egresos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-right-from-bracket"></i> Egresos
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_sales'))
                            <a href="{{ route('egres-inventories.create') }}" class="btn btn-success">
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
                            <div class="col-sm-9">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>

                            {{-- <div class="col-sm-2" style="margin-bottom: 10px">
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
                            </div> --}}
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



    @include('partials.modal-delete')
    @include('partials.modal-success')



 
@stop

@section('css')
    <style>

    
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
        
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';
        $(document).ready(() => {
            list();
            
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('#select-paginate').change(function(){
                countPage = $(this).val();
               
                list();
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});

            let url = '{{ url("admin/egres-inventories/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            let status =$("#status").val();
            let typeSale =$("#typeSale").val();


            $.ajax({
                // url: `${url}/${search}?paginate=${countPage}&page=${page}`,
                url: `${url}?search=${search}&paginate=${countPage}&page=${page}&status=${status}&typeSale=${typeSale}`,

                type: 'get',
                
                success: function(result){
                    $("#div-results").html(result);
                    $('#div-results').loading('toggle');
                }
            });

        }

        $('.success_form').submit(function(e){
                $('.btn-form-submit').attr('disabled', true);
                $('.btn-form-submit').val('Entregando...');
        });


        function deleteItem(url){
            $('#delete_form').attr('action', url);
        }

        function successItem(url){
            $('#success_form').attr('action', url);
        }
       

       
    </script>
@stop