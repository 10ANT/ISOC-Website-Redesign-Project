@props(['action', 'confirm' => 'Delete this item? This cannot be undone.', 'label' => null])

<form method="POST" action="{{ $action }}" class="d-inline" data-confirm="{{ $confirm }}">
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->class(['btn btn-sm btn-outline-danger']) }} title="Delete">
        <i class="fa-solid fa-trash-can"></i>@if ($label) {{ $label }}@else<span class="visually-hidden">Delete</span>@endif
    </button>
</form>
