@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Session::has('item_created'))
            <div class="alert alert-success" role="alert">
                Item {{ Session::get('item_created') }} successfully created!
            </div>
        @endif
        @if (Session::has('item_updated'))
            <div class="alert alert-success" role="alert">
                Item {{ Session::get('item_updated') }} successfully updated!
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
                        <div>
                            @can('update', $item)
                                <a href="{{ route('items.edit', $item) }}" class="btn btn-light mb-1">Edit</a>
                            @endcan
                            @can('delete', $item)
                                <button class="btn btn-danger mb-1" data-bs-toggle="modal"
                                    data-bs-target="#confirmDialog">Delete</button>
                            @endcan
                        </div>
                    </div>
                    <div class="mt-2 mb-2">
                        @foreach ($item->visibleLabels as $label)
                            <a href="{{ route('labels.show', $label) }}"
                                class="badge rounded-pill text-decoration-none label-md"
                                style="background-color: {{ $label->color }}">{{ $label->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4">
                        <img src="{{ asset($item->image ? 'storage/' . $item->image : 'images/placeholder.png') }}"
                            alt="Image" class="img-fluid rounded mt-2 mb-3">
                    </div>
                    <div class="col-12 col-md-6 col-lg-8">
                        <p>{!! nl2br(e($item->description)) !!}</p>
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
                                @can('delete', $comment)
                                    <form action="delete.php" method="post" class="d-inline">
                                        <input type="hidden" name="id" value="">
                                        <input class="comment-del-btn text-danger float-end" type="submit" value="Delete">
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <h4 class="text-center text-muted">There are no comments</h4>
            @endforelse
        </div>
    </div>

    <form action="{{ route('items.destroy', $item) }}" method="POST">
        @method('DELETE')
        @csrf
        <div class="modal fade" id="confirmDialog" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirm delete</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this item?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
