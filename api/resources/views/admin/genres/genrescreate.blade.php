@extends('admin.layouts.app')

@section('content')
    <h1>Create new Genre</h1>
    <section class="create_form">
        <form action="{{ route('admin_genres_store') }}" method="POST">
            @csrf
            @method('post')

            {{-- Genre Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="text" placeholder="" name="genre" value="{{ old('genre') ?: '' }}">
                    <label for="">
                        @error('genre')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Genre*</span>
                        @enderror
                    </label>
                </div>
            </div>

            {{-- Genre Color Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="color" placeholder="" id="color" name="color" value="{{ old('color') ?: '' }}">
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

            {{-- Submit Button --}}
            <div class="form__row">
                <button class="submit">Submit</button>
            </div>

        </form>
    </section>
@endsection