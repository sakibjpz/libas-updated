@extends('admin.layouts.app')

@section('title', 'Payment Details - Steadfast')

@section('page_title', 'Payment Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.steadfast.index') }}">Steadfast</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.steadfast.payments') }}">Payments</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Payment Information</h3></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                @foreach((array) $payment as $key => $value)
                    @if(!is_array($value) && !is_object($value))
                        <tr>
                            <th style="width:220px">{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                            <td>{{ $value }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
        @if(!empty($payment['consignments']) && is_array($payment['consignments']))
            <h5 class="mt-4">Consignments in this payment</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead><tr><th>Consignment ID</th><th>Invoice</th><th>COD</th></tr></thead>
                    <tbody>
                        @foreach($payment['consignments'] as $c)
                            <tr>
                                <td>{{ $c['consignment_id'] ?? '-' }}</td>
                                <td>{{ $c['invoice'] ?? '-' }}</td>
                                <td>৳{{ number_format((float)($c['cod_amount'] ?? 0), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
