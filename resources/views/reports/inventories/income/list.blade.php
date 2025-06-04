
<div class="col-md-12 text-right">

    {{-- <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Excel</button> --}}
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table id="dataTable" style="width:100%"  class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th style="text-align: center">N&deg;</th>
                        <th style="text-align: center">ITEMS / PRODUCTOS</th>
                        <th style="text-align: center">CATEGORIA</th>
                        <th style="text-align: center">FECHA INGRESO</th>
                        <th style="text-align: center">INGRESADO POR</th>
                        <th style="text-align: center">CANTIDAD</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                        // $total = 0;
                    @endphp
                    @forelse ($data as $item)
                        <tr>
                            <td style="text-align: center">{{ $count }}</td>
                            <td>{{ $item->itemInventory->name }}</td>
                            <td>{{ $item->itemInventory->category->name }}</td>
                            <td style="text-align: center">{{date('d/m/Y h:i a', strtotime($item->created_at))}}</td>
                            <td style="text-align: center">{{ $item->register->name }}</td>
                            <td style="text-align: right">{{ number_format($item->quantity,2, ',','.') }}</td>           
                        </tr>
                        @php
                            $count++;     
                        @endphp
                            
                    @empty
                        <tr style="text-align: center">
                            <td colspan="6">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                        {{-- <tr>
                            <td colspan="9" style="text-align: right">Total</td>
                            <td style="text-align: right"><small>Bs.</small> {{ number_format($total,2, ',', '.') }}</td>
                        </tr> --}}
                </tbody>
            </table>

            
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function(){

})
</script>