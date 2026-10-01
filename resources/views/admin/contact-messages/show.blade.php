@extends('admin.layouts.app')

@section('title', 'Message Details')

@section('page_title', 'Message Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.contact-messages.index') }}">Contact Messages</a></li>
    <li class="breadcrumb-item active">Message #{{ $message->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Message Information</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Name:</label>
                    <p class="form-control-static">{{ $message->name }}</p>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <p class="form-control-static">
                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                    </p>
                </div>
                @if($message->phone)
                <div class="form-group">
                    <label>Phone:</label>
                    <p class="form-control-static">
                        <a href="tel:{{ $message->phone }}">{{ $message->phone }}</a>
                    </p>
                </div>
                @endif
                <div class="form-group">
                    <label>Subject:</label>
                    <p class="form-control-static">{{ $message->subject }}</p>
                </div>
                <div class="form-group">
                    <label>Message:</label>
                    <div class="p-3 bg-light rounded">
                        {{ $message->message }}
                    </div>
                </div>
                <div class="form-group">
                    <label>Received:</label>
                    <p class="form-control-static">{{ $message->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <p class="form-control-static">
                        @if($message->status == 'unread')
                            <span class="badge bg-warning">Unread</span>
                        @elseif($message->status == 'read')
                            <span class="badge bg-info">Read</span>
                        @elseif($message->status == 'replied')
                            <span class="badge bg-success">Replied</span>
                        @endif
                    </p>
                </div>
                @if($message->replied_at)
                <div class="form-group">
                    <label>Replied At:</label>
                    <p class="form-control-static">{{ $message->replied_at->format('F d, Y h:i A') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Update Status</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.contact-messages.update-status', $message->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="unread" {{ $message->status == 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ $message->status == 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $message->status == 'replied' ? 'selected' : '' }}>Replied</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="admin_notes">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="4" class="form-control" placeholder="Add private notes...">{{ $message->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Update
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-success btn-block mb-2">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
                @if($message->phone)
                <a href="tel:{{ $message->phone }}" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-phone"></i> Call
                </a>
                @endif
                <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" class="d-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Delete this message?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Messages
        </a>
    </div>
</div>
@endsection