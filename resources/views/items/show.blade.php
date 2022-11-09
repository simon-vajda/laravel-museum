@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('success') }}
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
                                <a href="{{ route('items.edit', $item) }}" class="btn btn-secondary mb-1">Edit</a>
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
                    <div class="card mb-2" data-id="{{ $comment->id }}">
                        <div class="card-header">
                            <span class="text-dark">{{ $comment->author->name }}</span>
                            <span class="text-secondary"><small>•</small> {{ $comment->updated_at }}</span>
                            <span class="float-end">
                                @can('update', $comment)
                                    <button class="btn small-btn btn-secondary edit-btn" type="submit"
                                        onclick="editComment({{ $comment->id }})" data-bs-toggle="modal"
                                        data-bs-target="#commentUpdateDialog">Edit</button>
                                @endcan
                                @can('delete', $comment)
                                    <form action="{{ route('comments.destroy', $comment) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn small-btn btn-danger" type="submit">Delete</button>
                                    </form>
                                @endcan
                            </span>
                        </div>
                        <div class="card-body pb-1">
                            <div class="card-text">{{ $comment->text }}</div>
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

    <form method="POST">
        @method('PUT')
        @csrf
        <input type="hidden" name="comment_id" id="comment_id" value="{{ old('comment_id') }}">
        <div class="modal fade" id="commentUpdateDialog" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update comment</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control @error('update_text') is-invalid @enderror" name="update_text" rows="6"></textarea>
                        @error('update_text')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        function editComment(id) {
            const commentCard = document.querySelector(`[data-id="${id}"]`);
            const text = commentCard.querySelector('.card-text').textContent;
            const dialog = document.querySelector('#commentUpdateDialog');
            const form = dialog.parentElement;
            const idInput = dialog.previousElementSibling;
            const textArea = dialog.querySelector("textarea");
            textArea.value = text;
            form.action = `/comments/${id}`;
            idInput.value = id;
        }

        if (
            @error('update_text')
                true
            @else
                false
            @enderror ) {
            window.addEventListener('load', () => {
                console.log("old id", "{{ old('comment_id') }}");
                editComment({{ old('comment_id') }});
                const editBtn = document.querySelector('.edit-btn');
                editBtn.click();
            })
        }
    </script>
@endsection
