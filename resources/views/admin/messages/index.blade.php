@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
    <div class="page-actions">
        <form method="GET" class="align-items-center">
            <select name="type" class="form-select w-auto" aria-label="Filter by enquiry type" onchange="this.form.submit()">
                <option value="">All enquiries</option>
                @foreach ($types as $value => $label)
                    <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="unread" value="1" id="filter-unread" @checked(request()->boolean('unread')) onchange="this.form.submit()">
                <label class="form-check-label" for="filter-unread">Unread only</label>
            </div>
            <button class="btn btn-outline-secondary">Filter</button>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Enquiry</th>
                        <th>Message</th>
                        <th>Received</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr @class(['fw-bold' => ! $message->read_at])>
                            <td>
                                <a href="{{ route('admin.messages.show', $message) }}" class="title-link">{{ $message->name }}</a>
                                @unless ($message->read_at)
                                    <span class="badge text-bg-primary ms-1">New</span>
                                @endunless
                                <div class="small text-muted fw-normal">{{ $message->email }}</div>
                            </td>
                            <td class="text-nowrap">{{ $message->enquiryLabel() }}</td>
                            <td>
                                @if ($message->subject)<div>{{ $message->subject }}</div>@endif
                                <div class="small text-muted fw-normal">{{ Str::limit($message->message, 90) }}</div>
                            </td>
                            <td class="small text-muted text-nowrap" title="{{ $message->created_at->format('M j, Y g:i a') }}">{{ $message->created_at->diffForHumans() }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-outline-primary" title="Read"><i class="fa-solid fa-envelope-open-text"></i><span class="visually-hidden">Read</span></a>
                                <x-admin.delete-button :action="route('admin.messages.destroy', $message)" confirm="Delete this message?" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-row">No messages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $messages->links() }}</div>
@endsection
