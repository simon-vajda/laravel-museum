@extends('items.index')

@section('header')
    <h1>
        <div class="d-flex">
            <span>Items with label</span>
            <small class="ms-2">
                <span class="badge rounded-pill mb-3" style="background-color: {{ $label->color }}">{{ $label->name }}</span>
            </small>
        </div>
    </h1>
@endsection
