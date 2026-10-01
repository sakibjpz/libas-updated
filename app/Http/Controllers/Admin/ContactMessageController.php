<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->latest()->paginate(15);
        $unreadCount = ContactMessage::where('status', 'unread')->count();

        return view('admin.contact-messages.index', compact('messages', 'unreadCount'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Mark as read when viewed
        if ($message->status === 'unread') {
            $message->markAsRead();
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    public function updateStatus(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:unread,read,replied',
            'admin_notes' => 'nullable|string'
        ]);

        $message->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'replied_at' => $request->status === 'replied' ? now() : $message->replied_at
        ]);

        return redirect()->back()->with('success', 'Message status updated successfully.');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark-read,mark-replied',
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_messages,id'
        ]);

        $messages = ContactMessage::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'delete':
                $messages->delete();
                $message = 'Selected messages deleted successfully.';
                break;
            case 'mark-read':
                $messages->update(['status' => 'read']);
                $message = 'Selected messages marked as read.';
                break;
            case 'mark-replied':
                $messages->update([
                    'status' => 'replied',
                    'replied_at' => now()
                ]);
                $message = 'Selected messages marked as replied.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}