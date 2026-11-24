<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(Request $request): View
    {
        $type = $request->query('type');

        return view('pages.contact', [
            'enquiryTypes' => ContactMessage::ENQUIRY_TYPES,
            'selectedType' => array_key_exists((string) $type, ContactMessage::ENQUIRY_TYPES) ? $type : 'general',
        ]);
    }

    /** Handles both the contact form and the Get Involved volunteer form. */
    public function store(Request $request): RedirectResponse
    {
        // Honeypot: bots tend to fill every field.
        if (filled($request->input('website'))) {
            return back()->with('success', 'Thank you! Your message has been sent.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'enquiry_type' => ['required', Rule::in(array_keys(ContactMessage::ENQUIRY_TYPES))],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Thank you! Your message has been sent. A member of the ISOC Jamaica team will be in touch.');
    }
}
