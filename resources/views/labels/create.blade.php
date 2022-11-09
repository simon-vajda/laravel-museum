@extends('layouts.app')

@section('content')
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif

        <h1>New label</h1>
        <form action="{{ route('labels.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="container-fluid">
                        <div class="row mb-3">
                            <label for="labelName" class="col-form-label col-sm-2">Name</label>
                            <div class=" col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="labelName" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="labelColorPicker" class="col-form-label col-sm-2">Color picker</label>
                            <div class="col-sm-10">
                                <input type="color"
                                    class="form-control form-control-color @error('color') is-invalid @enderror"
                                    id="labelColorPicker" value="{{ old('color', '#ffc107') }}" name="color"
                                    title="Choose your color">
                                @error('color')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-2">
                                <label>Visibility</label>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="display"
                                        id="labelDisplay" checked>
                                    <label class="form-check-label" for="labelDisplay">Display</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="row mt-4">
                        <div class="h2 text-center">
                            <span class="badge rounded-pill" style="background-color: {{ old('color', '#ffc107') }}"
                                id="previewPill">preview</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-end">Create</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const labelName = document.querySelector('#labelName');
        const displayCheckbox = document.querySelector('#labelDisplay');
        const labelColorPicker = document.querySelector('#labelColorPicker');
        const previewPill = document.querySelector('#previewPill');

        labelName.addEventListener('input', () => {
            previewPill.innerText = labelName.value || 'preview';
        });

        labelColorPicker.addEventListener('input', () => {
            previewPill.style.backgroundColor = labelColorPicker.value;
        });

        displayCheckbox.addEventListener('change', () => {
            if (displayCheckbox.checked) {
                previewPill.classList.remove('display-off');
            } else {
                previewPill.classList.add('display-off');
            }
        });
    </script>
@endsection
