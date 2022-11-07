@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Session::has('item_created'))
            <div class="alert alert-success" role="alert">
                Post ({{ Session::get('item_created') }}) successfully created!
            </div>
        @endif

        <div class="row">
            <div class="container-fluid">
                <div class="row">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h1>{{ $item->name }}</h1>
                            <div class="text-muted">Obtained on {{ $item->obtained }}</div>
                        </div>
                        @if (Auth::check() && Auth::user()->is_admin)
                            <div>
                                <button class="btn btn-light mb-1">Edit</button>
                                <button class="btn btn-danger mb-1">Delete</button>
                            </div>
                        @endif
                    </div>
                    <div class="mt-2 mb-2">
                        @foreach ($item->visibleLabels as $label)
                            <span class="badge rounded-pill"
                                style="background-color: {{ $label->color }}">{{ $label->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4">
                        <img src="{{ asset($item->image ? 'storage/' . $item->image : 'images/placeholder.png') }}"
                            alt="Image" class="img-fluid rounded mt-2 mb-3">
                    </div>
                    <div class="col-12 col-md-6 col-lg-8">
                        <p>{{ $item->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <form action="{{ route('comments.store') }}" method="post" novalidate>
                @csrf

                <input type="hidden" name="item" value="{{ $item->id }}">
                @guest
                    <div class="mb-1 text-danger">
                        Log in to leave a comment.
                    </div>
                @endguest
                <textarea class="form-control" name="text" id="new-comment" rows="2" placeholder="New comment..."
                    @guest{{ 'disabled' }} @endguest></textarea>
                <button class="btn btn-primary float-end me-2 mt-2 mb-4" type="submit"
                    @guest{{ 'disabled' }} @endguest>
                    Submit
                </button>

                @error('text')
                    <div class="mb-1 text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </form>
        </div>

        <div class="row">
            @forelse ($item->comments as $comment)
                <div class="mb-3">
                    <div class="card mb-2">
                        <div class="card-body pb-1">
                            <div class="card-text">{{ $comment->text }}</div>
                            <hr class="mt-2 mb-1" />
                            <div class="mb-0">
                                <span class="text-dark">{{ $comment->author->name }}</span>
                                <span class="text-secondary"><small>•</small> {{ $comment->updated_at }}</span>
                                @if (Auth::check() && Auth::user()->is_admin)
                                    <form action="delete.php" method="post" class="d-inline">
                                        <input type="hidden" name="id" value="">
                                        <input class="comment-del-btn text-danger float-end" type="submit" value="Delete">
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <h4 class="text-center text-muted">There are no comments</h4>
            @endforelse
        </div>
    </div>
@endsection
