@extends('admin.layouts.app')

@section('title', 'Site Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Site Settings</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.site-settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="whatsapp_phone">WhatsApp Phone Number</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="whatsapp_phone" 
                                   name="whatsapp_phone" 
                                   value="{{ $settings->whatsapp_phone }}"
                                   placeholder="e.g., 8801333257604"
                                   required>
                            <small class="form-text text-muted">Enter the phone number without + or spaces. Example: 8801333257604</small>
                            @error('whatsapp_phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
