@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('page_title', 'Contact Messages')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Contact Messages</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Messages</h3>
        <div class="card-tools">
            <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="form-inline">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                <tr>
                    <td><input type="checkbox" class="message-checkbox" value="{{ $message->id }}"></td>
                    <td>{{ $message->id }}</td>
                    <td>{{ $message->name }}</td>
                    <td>{{ $message->email }}</td>
                    <td>{{ Str::limit($message->subject, 40) }}</td>
                    <td>
                        @if($message->status == 'unread')
                            <span class="badge bg-warning">Unread</span>
                        @elseif($message->status == 'read')
                            <span class="badge bg-info">Read</span>
                        @elseif($message->status == 'replied')
                            <span class="badge bg-success">Replied</span>
                        @endif
                    </td>
                    <td>{{ $message->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.contact-messages.show', $message->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No messages found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        <div class="float-left">
            <select id="bulk-action" class="form-control form-control-sm d-inline" style="width: 150px;">
                <option value="">Bulk Actions</option>
                <option value="mark-read">Mark as Read</option>
                <option value="mark-replied">Mark as Replied</option>
                <option value="delete">Delete</option>
            </select>
            <button id="apply-bulk-action" class="btn btn-sm btn-primary">Apply</button>
        </div>
        <div class="float-right">
            {{ $messages->withQueryString()->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('select-all').addEventListener('change', function(e) {
    document.querySelectorAll('.message-checkbox').forEach(checkbox => {
        checkbox.checked = e.target.checked;
    });
});

document.getElementById('apply-bulk-action').addEventListener('click', function() {
    const action = document.getElementById('bulk-action').value;
    const selectedIds = Array.from(document.querySelectorAll('.message-checkbox:checked')).map(cb => cb.value);
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    if (selectedIds.length === 0) {
        alert('Please select messages');
        return;
    }
    
    if (confirm('Are you sure?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.contact-messages.bulk-action") }}';
        
        const csrf = document.createElement('input');
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        const actionInput = document.createElement('input');
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endpush
@endsection