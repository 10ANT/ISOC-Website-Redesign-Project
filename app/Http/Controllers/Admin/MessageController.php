<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type');

        return view('admin.messages.index', [
            'messages' => ContactMessage::query()
                ->when(array_key_exists((string) $type, ContactMessage::ENQUIRY_TYPES), fn ($q) => $q->where('enquiry_type', $type))
                ->when($request->boolean('unread'), fn ($q) => $q->unread())
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'types' => ContactMessage::ENQUIRY_TYPES,
        ]);
    }

    public function show(ContactMessage $message): View
    {
        if (! $message->read_at) {
            $message->forceFill(['read_at' => now()])->save();
        }

        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}
