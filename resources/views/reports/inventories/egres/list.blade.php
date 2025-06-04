
<div class="col-md-12 text-right">

    {{-- <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Excel</button> --}}
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            @if ($detail == 0)
                <table id="dataTable" style="width:100%"  class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th style="text-align: center">N&deg;</th>
                            <th style="text-align: center">CODIGO</th>
                            <th style="text-align: center">CLIENTE</th>
                            <th style="text-align: center; width: 15%">FECHA DE VENTAS</th>
                            <th style="text-align: center; width: 8%">TICKET</th>
                            <th style="text-align: center; width: 8%">TOTAL</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                            $total = 0;
                        @endphp
                        @forelse ($sales as $item)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $item->code }}</td>
                                <td>
                                    @if ($item->person)
                                        {{ strtoupper($item->person->first_name) }} {{ $item->person->middle_name??strtoupper($item->person->middle_name) }} {{ strtoupper($item->person->paternal_surname) }}  {{ strtoupper($item->person->maternal_surname) }}
                                    @else
                                        Sin Datos 
                                     @endif 
                                </td>
                                <td style="text-align: center">
                                    {{date('d/m/Y h:i: a', strtotime($item->dateSale))}}<br><small>
                                </td>
                                <td style="text-align: center">{{ $item->ticket }}</td>
                                <td style="text-align: right">
                                    Bs. {{ number_format($item->amount, 2, ',', '.') }}
                                </td>  
                            </tr>
                            @php
                                $count++;     
                                $total+=$item->amount;
                            @endphp
                                
                        @empty
                            <tr style="text-align: center">
                                <td colspan="6">No se encontraron registros.</td>
                            </tr>
                        @endforelse
                            <tr>
                                <td colspan="5" style="text-align: right">Total</td>
                                <td style="text-align: right"><small>Bs.</small> {{ number_format($total,2, ',', '.') }}</td>
                            </tr>
                    </tbody>
                </table>
            @else
                <table id="dataTable" style="width:100%"  class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th style="text-align: center">N&deg;</th>
                            <th style="text-align: center">CODIGO</th>
                            <th style="text-align: center">CLIENTE</th>
                            <th style="text-align: center; width: 15%">FECHA DE VENTAS</th>
                            <th style="text-align: center; width: 8%">TICKET</th>
                            <th style="text-align: center; width: 8%">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $count = 1;
                            $total = 0;
                        @endphp
                        @forelse ($sales as $item)
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $item->code }}</td>
                                <td>
                                    @if ($item->person)
                                        {{ strtoupper($item->person->first_name) }} {{ $item->person->middle_name??strtoupper($item->person->middle_name) }} {{ strtoupper($item->person->paternal_surname) }}  {{ strtoupper($item->person->maternal_surname) }}
                                    @else
                                        Sin Datos 
                                     @endif 
                                </td>
                                <td style="text-align: center">
                                    {{date('d/m/Y h:i a', strtotime($item->dateSale))}}<br><small>
                                </td>
                                <td style="text-align: center">{{ $item->ticket }}</td>
                                <td style="text-align: right">
                                    Bs. {{ number_format($item->amount, 2, ',', '.') }}
                                </td>  
                            </tr>

                            <tr>
                                <th style="text-align: center; background-color: #ffffff !important; padding: 0.1px;"></th>
                                <th style="text-align: center; background-color: #ffffff !important; padding: 0.1px;"></th>
                                <th style="text-align: center; background-color: #b1b0b0 !important; padding: 0.1px;">ITEMS</th>
                                <th style="text-align: center; background-color: #b1b0b0 !important; padding: 0.1px;">CANTIDAD</th>
                                <th style="text-align: center; background-color: #b1b0b0 !important; padding: 0.1px;">PRECIO</th>
                                <th style="text-align: center; background-color: #b1b0b0 !important; padding: 0.1px;">SUBTOTAL</th>
                            </tr>
                            @forelse ($item->saleDetails as $product)
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                        {{ $product->itemSale->name}}
                                    </td>
                                    <td style="text-align: right">
                                        {{ number_format($product->quantity, 2, ',', '.') }}
                                    </td>  
                                    <td style="text-align: right">
                                        Bs. {{ number_format($product->price, 2, ',', '.') }}
                                    </td>  
                                    <td style="text-align: right">
                                        Bs. {{ number_format($product->amount, 2, ',', '.') }}
                                    </td>  
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="border: 1px solid #ddd; padding: 6px; text-align: center;">
                                        No se encontraron senadores
                                    </td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="6"></td>
                            </tr>
                            @php
                                $count++;     
                                $total+=$item->amount;
                            @endphp
                                
                        @empty
                            <tr style="text-align: center">
                                <td colspan="6">No se encontraron registros.</td>
                            </tr>
                        @endforelse
                            <tr>
                                <td colspan="5" style="text-align: right">Total</td>
                                <td style="text-align: right"><small>Bs.</small> {{ number_format($total,2, ',', '.') }}</td>
                            </tr>
                    </tbody>
                </table>
            @endif
            
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function(){

})
</script>