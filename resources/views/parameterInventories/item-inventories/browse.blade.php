@extends('voyager::master')
@section('page_title', 'Viendo Productos del Almacén')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-bag"></i> Productos / Item del Almacén
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_people'))
                            <a href="{{ route('voyager.item-inventories.create') }}" class="btn btn-success">
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
                            <div class="col-sm-3" style="margin-bottom: 10px">
                                <select id="category" name="category" class="form-control select2">
                                    <option value="" selected>Todos</option>
                                    @foreach ($category as $item)
                                        <option value="{{$item->categoryInventory_id }}">{{$item->category->name}}</option>
                                    @endforeach
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



    @include('partials.modal-delete')






 
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

            $('#category').change(function(){
                list();
            });
            $('#stock').change(function(){
                list();
            });
            
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

            // let url = '{{ route("voyager.item-sales.index", ["country" => "country_id"]) }}'.replace('country_id', country);
            let url = '{{ url("admin/item-inventories/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            let category =$("#category").val();
            // let stock =$("#stock").val();
            

            $.ajax({
                // url: `${url}/${search}?paginate=${countPage}&page=${page}`,
                url: `${url}?search=${search}&paginate=${countPage}&page=${page}&category=${category}`,

                type: 'get',
                
                success: function(result){
                    $("#div-results").html(result);
                    $('#div-results').loading('toggle');
                }
            });

        }


        function deleteItem(url){
            $('#delete_form').attr('action', url);
        }
       

       
    </script>
@stop