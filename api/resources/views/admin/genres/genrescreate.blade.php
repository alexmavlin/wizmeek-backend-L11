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

            {{-- Submit Button --}}
            <div class="form__row">
                <button class="submit">Submit</button>
            </div>

        </form>
    </section>
@endsection