@extends('admin.layouts.app')

@section('title', 'Police Stations - Steadfast')

@section('page_title', 'Delivery Zones (Police Stations)')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.steadfast.index') }}">Steadfast</a></li>
    <li class="breadcrumb-item active">Police Stations</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Steadfast Delivery Coverage Areas</h3>
    </div>
    <div class="card-body">
        @if(count($stations) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Station / Area Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stations as $i => $station)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ is_array($station) ? ($station['name'] ?? $station['police_station'] ?? json_encode($station)) : $station }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted mb-0">No stations found.</p>
        @endif
    </div>
</div>
@endsection
