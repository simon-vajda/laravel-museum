@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Display</th>
                    <th scope="col">Item count</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($labels as $label)
                    <tr>
                        <th scope="row">{{ $label->id }}</th>
                        <td><a href="{{ route('labels.show', $label) }}"
                                class="badge rounded-pill text-decoration-none label-md {{ !$label->display ? 'display-off' : '' }}"
                                style="background-color: {{ $label->color }}">{{ $label->name }}</a></td>
                        <td>{{ $label->display ? 'True' : 'False' }}</td>
                        <td>{{ $label->items()->count() }}</td>
                        <td>
                            <div class="float-end">
                                @can('update', $label)
                                    <a href="{{ route('labels.edit', $label) }}" class="btn small-btn btn-secondary">Edit</a>
                                @endcan
                                @can('delete', $label)
                                    <form action="{{ route('labels.destroy', $label) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn small-btn btn-danger" type="submit">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
