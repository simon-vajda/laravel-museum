@extends('items.index')

@section('header')
    <div class="d-flex justify-content-between">
        <h1>
            <div class="d-flex">
                <span>Items with label</span>
                <small class="ms-2">
                    <span class="badge rounded-pill mb-3 {{ !$label->display ? 'display-off' : '' }}"
                        style="background-color: {{ $label->color }}">{{ $label->name }}</span>
                </small>
            </div>
        </h1>
        <div>
            @can('update', $label)
                <a href="{{ route('labels.edit', $label) }}" class="btn btn-secondary mb-1">Edit label</a>
            @endcan
            @can('delete', $label)
                <button class="btn btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#confirmDialog">Delete label</button>
            @endcan
        </div>
    </div>

    <form action="{{ route('labels.destroy', $label) }}" method="POST">
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
                        Are you sure you want to delete this label?
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
