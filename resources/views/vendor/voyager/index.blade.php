@extends('voyager::master')
@section('page_header')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Hola, {{ Auth::user()->name }}</h2>
                            </div>                        
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('content')
    @php
        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');       
    @endphp
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        @include('voyager::dimmers')

        

      
      
    </div>
@stop

@section('javascript')


<script>
    $(document).ready(function(){   
        $('.form-submit').submit(function(e){
            $('.btn-form-submit').attr('disabled', true);
            $('.btn-form-submit').val('Guardando...');
        });
    });
</script>
 

@stop