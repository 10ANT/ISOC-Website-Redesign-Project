@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            @foreach ($fields as $group => $groupFields)
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">{{ $group }}</div>
                        <div class="card-body">
                            @foreach ($groupFields as $key => [$label, $type])
                                @if ($type === 'textarea')
                                    <x-admin.textarea :name="$key" :label="$label" :value="$values[$key] ?? null" rows="4" />
                                @else
                                    <x-admin.input :type="$type" :name="$key" :label="$label" :value="$values[$key] ?? null"
                                        :placeholder="$type === 'url' ? 'https://' : null" />
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Save settings</button>
        </div>
    </form>
@endsection
