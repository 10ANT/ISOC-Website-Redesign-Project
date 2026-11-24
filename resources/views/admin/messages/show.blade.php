@extends('layouts.admin')

@section('title', 'Message')

@section('content')
    @php
        $subject = $message->subject ?: $message->enquiryLabel();
        $mailto = 'mailto:'.$message->email.'?subject='.rawurlencode('Re: '.$subject);
    @endphp

    <div class="page-actions">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-light"><i class="fa-solid fa-arrow-left me-1"></i> All messages</a>
        <div class="d-flex gap-2">
            <a href="{{ $mailto }}" class="btn btn-primary"><i class="fa-solid fa-reply me-1"></i> Reply by email</a>
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" data-confirm="Delete this message?">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger"><i class="fa-solid fa-trash-can me-1"></i> Delete</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">{{ $subject }}</div>
                <div class="card-body">
                    <div class="message-body">{{ $message->message }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Details</div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt>From</dt>
                        <dd>{{ $message->name }}</dd>
                        <dt>Email</dt>
                        <dd><a href="{{ $mailto }}">{{ $message->email }}</a></dd>
                        @if ($message->phone)
                            <dt>Phone</dt>
                            <dd><a href="tel:{{ preg_replace('/[^0-9+]/', '', $message->phone) }}">{{ $message->phone }}</a></dd>
                        @endif
                        <dt>Enquiry type</dt>
                        <dd>{{ $message->enquiryLabel() }}</dd>
                        <dt>Received</dt>
                        <dd class="mb-0">{{ $message->created_at->format('l, M j, Y \a\t g:i a') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
