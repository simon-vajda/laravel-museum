@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach ($labels as $label)
            <div>
                {{ $label->name }}
            </div>
        @endforeach
    </div>
@endsection
