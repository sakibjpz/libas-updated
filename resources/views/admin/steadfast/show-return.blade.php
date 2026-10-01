@extends('admin.layouts.app')

@section('title', 'Return Details - Steadfast')

@section('page_title', 'Return Request Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.steadfast.index') }}">Steadfast</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.steadfast.returns') }}">Returns</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Return Request Information</h3></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                @foreach((array) $return as $key => $value)
                    @if(!is_array($value) && !is_object($value))
                        <tr>
                            <th style="width:220px">{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                            <td>{{ $value }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection
