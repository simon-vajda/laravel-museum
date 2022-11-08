@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="row">
                <div class="col-12 col-md-5 mb-4">
                    <img src="{{ asset($item->image ? 'storage/' . $item->image : 'images/placeholder.png') }}" alt="Image"
                        class="img-fluid rounded mt-2 mb-3" id="item-image">
                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="file-picker"
                        name="image">
                    @error('image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-12 col-md-7">
                    <div class="d-flex flex-column h-100">
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $item->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="3"
                                    name="description">{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="obtained" class="form-label">Obtained</label>
                                <input class="form-control @error('obtained') is-invalid @enderror" type="date"
                                    name="obtained" id="obtained" value="{{ old('obtained', $item->obtained) }}">
                                @error('obtained')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="labels" class="form-label d-block">Labels</label>
                                @forelse ($labels as $label)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="labels[]"
                                            value="{{ $label->id }}" @checked(in_array($label->id, old('labels', $item->labels->pluck('id')->toArray())))>
                                        <span class="badge rounded-pill"
                                            style="background-color: {{ $label->color }}">{{ $label->name }}</span>
                                    </div>
                                @empty
                                    <div class="mb-1 text-muted">
                                        No labels available.
                                    </div>
                                @endforelse

                                @error('labels.*')
                                    <ul class="text-danger">
                                        @foreach ($errors->get('labels.*') as $error)
                                            <li>{{ implode(', ', $error) }}</li>
                                        @endforeach
                                    </ul>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary float-end">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script></script>
@endsection

@section('scripts')
    <script>
        const image = document.querySelector('#item-image');
        const filePicker = document.querySelector('#file-picker');

        filePicker.onchange = event => {
            const [file] = filePicker.files;
            if (file) {
                image.src = URL.createObjectURL(file);
            } else {
                image.src = '/images/placeholder.png';
            }
        }
    </script>
@endsection
