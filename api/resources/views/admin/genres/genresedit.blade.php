@extends('admin.layouts.app')

@section('content')
    <h1>Edit Artist - {{ $data["genre"]->name }}</h1>
    <section class="edit_form">
        <form action="{{ route('admin_genres_update', $data["genre"]->id) }}" method="POST">
            @csrf
            @method('put')

            {{-- Genre Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="text" placeholder="" name="genre" value="{{ old('genre') ?: $data["genre"]->genre }}">
                    <label for="">
                        @error('name')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Genre*</span>
                        @enderror
                    </label>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="form__row">
                <button class="submit">Submit</button>
            </div>

        </form>
    </section>
@endsection