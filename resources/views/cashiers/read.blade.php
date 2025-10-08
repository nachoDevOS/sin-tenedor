@extends('voyager::master')

@section('page_title', 'Ver Caja')


@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i> Viendo Caja
        <a href="{{ route('cashiers.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        @if ($cashier->status == 'cierre pendiente')
            <a href="{{ route('cashiers.confirm_close', ['cashier' => $cashier->id]) }}" title="Ver"
                class="btn btn-sm btn-info">
                <i class="voyager-lock"></i> <span class="hidden-xs hidden-sm">Confirmar Cierre de Caja</span>
            </a>
        @endif
        @if ($cashier->status == 'cerrada')
            <a href="{{ route('cashiers.print', $cashier->id) }}" title="Imprimir" target="_blank"
                class="btn btn-sm btn-danger">
                <i class="fa fa-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
            </a>
        @endif
        {{-- <div class="btn-group">
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-print"></span> Impresión <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('print.open', ['cashier' => $cashier->id]) }}" target="_blank">Apertura</a></li>
                @if ($cashier->status == 'cerrada')
                <li><a href="{{ route('print.close', ['cashier' => $cashier->id]) }}" target="_blank">Cierre</a></li>
                @endif
            </ul>
        </div> --}}
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Descripción</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->title }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cajero</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->user->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->observations ?? 'Ninguna' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>

                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <h3 id="h4">Dinero abonado <label class="label label-success">Ingreso</label></h3>
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-bordered">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th style="text-align: center; width:15%">Fecha y Hora de Registro</th>
                                        <th style="text-align: center; width:30%">Registrado Por</th>
                                        <th>Detalle</th>
                                        <th style="text-align: center">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count = 1;
                                        $cashierInput = 0;
                                    @endphp
                                    @forelse ($cashier->movements->where('type', 'ingreso')->where('deleted_at', null) as $item)
                                        <tr>
                                            <td>
                                                {{ $count }}
                                                @if (auth()->user()->hasRole('admin'))
                                                    <br>
                                                    ID={{ $item->id }}
                                                @endif
                                            </td>
                                            <td style="text-align: center">
                                                {{ date('d/m/Y h:i:s a', strtotime($item->created_at)) }}<br><small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                                            </td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>{{ $item->description }} <br>
                                                @if ($item->transferCashier_id)
                                                    <label class="label label-success">Trasferencia de Caja</label>
                                                @endif
                                            </td>
                                            <td style="text-align: right">{{ $item->amount }}</td>
                                        </tr>
                                        @php
                                            $cashierInput += $item->amount;
                                            $count++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td class="text-center" valign="top" colspan="5" class="dataTables_empty">
                                                No hay datos disponibles en la tabla</td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="4" style="text-align: right"><b>TOTAL</b></td>
                                        <td style="text-align: right">{{ number_format($cashierInput, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <h3 id="h4">Ventas <label class="label label-success">Ingreso</label></h3>
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-bordered">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Código</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Ticket</th>
                                        <th>Pago Qr</th>
                                        <th>Pago Efectivo</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count = 1;
                                        $total_movements = 0;
                                        $total_movements_qr = 0;
                                        $total_movements_efectivo = 0;
                                        $total_movements_deleted = 0;
                                    @endphp
                                    @forelse ($cashier->sales as $item)
                                        <tr
                                            @if ($item->deleted_at) style="text-decoration: line-through; color: red;" @endif>
                                            <td style="text-align: center; font-size: 11px">{{ $count }}</td>
                                            <td style="font-size: 11px">
                                                @if ($item->deleted_at == null && $cashier->status == 'abierta')
                                                    <a href="#"
                                                        onclick="deleteItem('{{ route('sales.destroy', ['sale' => $item->id]) }}')"
                                                        title="Eliminar" data-toggle="modal" data-target="#modal-delete"
                                                        class="btn btn-sm btn-danger delete">
                                                        <i class="voyager-trash"></i>
                                                    </a>
                                                @endif

                                                {{ $item->code }}
                                            </td>
                                            <td style="font-size: 11px">
                                                @if ($item->person)
                                                    {{ strtoupper($item->person->first_name) }}
                                                    {{ $item->person->middle_name ? strtoupper($item->person->middle_name) : '' }}
                                                    {{ strtoupper($item->person->paternal_surname) }}
                                                    {{ strtoupper($item->person->maternal_surname) }}
                                                @else
                                                    Sin Datos
                                                @endif
                                            </td>
                                            <td style="text-align: center; font-size: 11px">
                                                {{ date('d/m/Y h:i a', strtotime($item->dateSale)) }}
                                            </td>
                                            <td style="text-align: center; font-size: 11px">{{ $item->ticket }}</td>

                                            @php
                                                $pagoQr = $item->saleTransactions
                                                    ->where('paymentType', 'Qr')
                                                    ->sum('amount');
                                                $pagoEfectivo = $item->saleTransactions
                                                    ->where('paymentType', 'Efectivo')
                                                    ->sum('amount');
                                                if ($item->deleted_at == null) {
                                                    $total_movements_qr += $pagoQr;
                                                    $total_movements_efectivo += $pagoEfectivo;

                                                    $total_movements += $pagoQr + $pagoEfectivo;
                                                } else {
                                                    $total_movements_deleted += $item->amount;
                                                }
                                            @endphp
                                            <td class="text-right">{{ number_format($pagoQr, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($pagoEfectivo, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->amount, 2, ',', '.') }}</td>


                                        </tr>
                                        @php
                                            $count++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td style="text-align: center" colspan="8">No hay datos disponibles en la
                                                tabla</td>
                                        </tr>
                                    @endempty
                                    <tr>
                                        <td colspan="7" class="text-right"><span class="text-danger"><b>TOTAL
                                                    ANULADO</b></span></td>
                                        <td class="text-right"><b
                                                class="text-danger">{{ number_format($total_movements_deleted, 2, ',', '.') }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right"><b>TOTAL COBROS</b></td>
                                        <td class="text-right">
                                            <b>{{ number_format($total_movements, 2, ',', '.') }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right"><b>TOTAL QR/TRANSFERENCIA</b></td>
                                        <td class="text-right">
                                            <b>{{ number_format($total_movements_qr, 2, ',', '.') }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right"><b>TOTAL EFECTIVO</b></td>
                                        <td class="text-right">
                                            <b>{{ number_format($total_movements_efectivo, 2, ',', '.') }}</b>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <div class="panel panel-bordered">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover" id="dataTable">
                            <tr>
                                <td>
                                    <h4>Dinero abonado</h4>
                                </td>
                                <td style="text-align: right">
                                    <h4>{{ number_format($cashierInput, 2, ',', '.') }}</h4>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>Cobros realizados en efectivo</h4>
                                </td>
                                <td style="text-align: right">
                                    <h4>{{ number_format($total_movements_efectivo, 2, ',', '.') }}</h4>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>Cobros realizados mediante QR</h4>
                                </td>
                                <td style="text-align: right">
                                    <h4>{{ number_format($total_movements_qr, 2, ',', '.') }}</h4>
                                </td>
                            </tr>
                            {{-- <tr>
                                    <td><h4>Prestamos entregados</h4></td>
                                    <td style="text-align: right"><h4>{{ number_format($loanCash+$pawnCash+$amountAditionalCash+$salaryPurchaseCash, 2, ',', '.') }}</h4></td>
                                </tr> --}}
                            {{-- <tr>
                                    <td><h4>Gastos realizados</h4></td>
                                    <td style="text-align: right"><h4>{{ number_format($extraExpenseCash + $transferAmountCash, 2, ',', '.') }}</h4></td>
                                </tr> --}}
                            <tr style="background-color: #E5E8E8">
                                <td>
                                    <h4>Dinero en efectivo disponible</h4>
                                </td>
                                <td style="text-align: right">
                                    <h4>{{ number_format($cashierInput + $total_movements_efectivo, 2, ',', '.') }}</h4>
                                </td>
                            </tr>
                            @if ($cashier->amount)
                                <tr style="background-color: #E5E8E8">
                                    <td>
                                        <h4>Dinero en efectivo al cerrar caja</h4>
                                    </td>
                                    <td style="text-align: right">
                                        <h4>{{ number_format($cashier->amount_real, 2, ',', '.') }}</h4>
                                    </td>
                                </tr>
                                <tr style="background-color: #E5E8E8">
                                    <td>
                                        <h4>Saldo</h4>
                                    </td>
                                    <td style="text-align: right">
                                        <h4
                                            class="@if ($cashier->balance > 0) text-success @endif @if ($cashier->balance < 0) text-danger @endif">
                                            {{ number_format($cashier->balance, 2, ',', '.') }}</h4>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- @include('partials.modal-delete')
    @include('partials.modal-mapsView') --}}

    @include('partials.modal-delete')




@stop

@section('javascript')
<script>

    function deleteItem(url) {
        $('#delete_form').attr('action', url);
    }

    $(document).ready(function() {
        $('.btn-delete').click(function() {
            let loan_id = $(this).data('id');
            $(`#form-delete input[name="loan_id"]`).val(loan_id);
        });
    });
</script>
@stop
