@extends('admin.layouts.app')

@section('title', 'Steadfast Courier Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Steadfast Courier Integration</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.steadfast.balance') }}" class="btn btn-sm btn-primary" onclick="event.preventDefault(); checkBalance();">
                            Refresh Balance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($balance['success']) && $balance['success'])
                        <div class="alert alert-success">
                            <h4>Current Balance: ৳{{ number_format($balance['data']['current_balance'] ?? 0, 2) }}</h4>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h4>Failed to fetch balance</h4>
                            <p>{{ $balance['message'] ?? 'Connection error' }}</p>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>Send Orders</h3>
                                    <p>Send to Steadfast</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                                    View Orders <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>Track</h3>
                                    <p>Track Orders</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#trackModal">
                                    Track Now <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>Returns</h3>
                                    <p>Manage Returns</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-undo"></i>
                                </div>
                                <a href="{{ route('admin.steadfast.returns') }}" class="small-box-footer">
                                    View Returns <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>Payments</h3>
                                    <p>View Payments</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill"></i>
                                </div>
                                <a href="{{ route('admin.steadfast.payments') }}" class="small-box-footer">
                                    View Payments <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Track Modal -->
<div class="modal fade" id="trackModal" tabindex="-1" role="dialog" aria-labelledby="trackModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trackModalLabel">Track Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.steadfast.track') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Search by:</label>
                        <select class="form-control" name="type" id="trackType">
                            <option value="invoice">Invoice ID</option>
                            <option value="consignment">Consignment ID</option>
                            <option value="tracking">Tracking Code</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="identifier">Enter ID/Code:</label>
                        <input type="text" class="form-control" name="identifier" id="identifier" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Track Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function checkBalance() {
    fetch('{{ route("admin.steadfast.balance") }}')
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Current Balance: ৳' + (data.data.current_balance || 0));
                location.reload();
            } else {
                alert('Failed to fetch balance');
            }
        });
}
</script>
@endpush