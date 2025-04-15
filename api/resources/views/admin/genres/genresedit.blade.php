@extends('admin.layouts.app')

@section('content')
    <h1>Edit Artist - {{ $data["genre"]->name }}</h1>
    <section class="edit_form">
        <form action="{{ route('admin_genres_update', $data["genre"]->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')

            {{-- Genre Name Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="text" placeholder="" id="genre" name="genre" value="{{ old('genre') ?: $data["genre"]->genre }}">
                    <label for="genre">
                        @error('genre')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Genre*</span>
                        @enderror
                    </label>
                </div>
            </div>
            {{-- // Genre Name Input --}}

            {{-- Genre Color Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="color" placeholder="" id="color" name="color" value="{{ old('color') ?: $data["genre"]->color }}">
                    <label for="color">
                        @error('color')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Select a Genre Color*</span>
                        @enderror
                    </label>
                </div>
            </div>
            {{-- // Genre Color Input --}}

            {{-- Image Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="file" placeholder="" id="image" name="image">
                    <label for="image">
                        @error('image')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Choose an image</span>
                        @enderror
                    </label>
                </div>
            </div>
            {{-- // Image Input --}}

            {{-- Submit Button --}}
            <div class="form__row">
                <button class="submit">Submit</button>
            </div>

        </form>
    </section>
@endsection