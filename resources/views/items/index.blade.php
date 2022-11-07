@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Session::has('item_deleted'))
            <div class="alert alert-success" role="alert">
                Item {{ Session::get('item_deleted') }} successfully deleted!
            </div>
        @endif

        @yield('header')
        <div class="row gy-4">
            @forelse ($items as $item)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card">
                        <img src="{{ asset($item->image ? 'storage/' . $item->image : 'images/placeholder.png') }}"
                            class="card-img-top" alt="Card image">

                        <div class="card-body">
                            <h5 class="card-title mb-0">{{ $item->name }}</h5>
                            <small class="text-muted">Obtained on {{ $item->obtained }}</small>
                            <div class="mt-2 mb-2">
                                @foreach ($item->visibleLabels as $label)
                                    <a href="{{ route('labels.show', $label) }}"
                                        class="badge rounded-pill text-decoration-none"
                                        style="background-color: {{ $label->color }}">{{ $label->name }}</a>
                                @endforeach
                            </div>
                            <p class="card-text">{{ Str::limit($item->description, 120, $end = '...') }}</p>
                            <div class="d-grid">
                                <a href="{{ route('items.show', $item) }}" class="btn btn-primary">Show more</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <p class="mb-0">No items found.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
@endsection
