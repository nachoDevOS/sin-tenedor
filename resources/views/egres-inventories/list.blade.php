<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="text-align: center; width: 15%">Codigo</th>
                    <th style="text-align: center">Detalles</th>
                    <th style="text-align: center">Fecha Egreso</th>
                    <th style="text-align: center">Estado</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->observation?$item->observation:'Sin Detalles' }}</td>

                    <td style="text-align: center; width: 20%">
                        Registrado por {{$item->register->name}} <br>
                        {{date('d/m/Y h:i:s a', strtotime($item->dateEgres))}}<br><small>{{\Carbon\Carbon::parse($item->dateEgres)->diffForHumans()}}
                    </td>
                    <td style="text-align: center; width: 12%">
                        @if ($item->status!='Pendiente')  
                            <label class="label label-success">Entregado</label>
                        @else
                            <label class="label label-warning">Pendiente</label>
                        @endif

                        
                    </td>
                    <td style="width: 18%" class="no-sort no-click bread-actions text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-print">
                                {{-- </span> Impresión <span class="caret"></span> --}}
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{route('egres-inventories.print', ['id'=>$item->id])}}" target="_blank"><i class="fa-solid fa-print"></i> Salida</a></li>                                
                            </ul>
                        </div>
                     
                        @if (auth()->user()->hasPermission('read_egres_inventories'))
                            <a href="{{ route('egres-inventories.show', ['egres_inventory' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i>
                            </a>
                        @endif
                        
                        @if (auth()->user()->hasPermission('delete_egres_inventories'))
                            <a href="#" onclick="deleteItem('{{ route('egres-inventories.destroy', ['egres_inventory' => $item->id]) }}')" title="Eliminar" data-toggle="modal" data-target="#modal-delete" class="btn btn-sm btn-danger delete">
                                <i class="voyager-trash"></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <h5 class="text-center" style="margin-top: 50px">
                                <img src="{{ asset('images/empty.png') }}" width="120px" alt="" style="opacity: 0.8">
                                <br><br>
                                No hay resultados
                            </h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<script>
   
   var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>