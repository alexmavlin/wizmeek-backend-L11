@extends('admin.layouts.app')

@section('content')
    <h1>Create new Artists</h1>
    <section class="create_form">
        <form action="{{ route('admin_artists_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('post')

            {{-- Name Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="text" placeholder="" name="name" value="{{ old('name') ?: '' }}">
                    <label for="">
                        @error('name')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Name*</span>
                        @enderror
                    </label>
                </div>
            </div>

            {{-- Avatar Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="file" name="avatar">
                    <label for="">
                        @error('avatar')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Avatar*</span>
                        @enderror
                    </label>
                    <span>For better performance use images that are converted to .webp fomat, and resized to 300px width.<br>
                        To ensure better images quality use <a href="https://imageresizer.com/" title="Image converter" target="_blank">official converter</a>.
                    </span>
                </div>
            </div>

            {{-- Description Textareas --}}
            <div class="form__row">
                <div class="form__froup">
                    <textarea name="short_description" placeholder="">{{ old('short_description') ?: '' }}</textarea>
                    <label for="">
                        @error('short_description')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Short Description</span>
                        @enderror
                    </label>
                    <span>Supports basic HTML tags (h1-h6, span, p, br strong) and inline styles*</span>
                </div>
                <div class="form__froup">
                    <textarea name="full_description" placeholder="">{{ old('full_description') ?: '' }}</textarea>
                    <label for="">
                        @error('full_description')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Full Description</span>
                        @enderror
                    </label>
                    <span>Supports basic HTML tags (h1-h6, span, p, br strong) and inline styles*</span>
                </div>
            </div>

            {{-- Additional settings --}}
            <div class="form__row labelled row">
                <span class="form__row--label">Additional Settings</span>
                <div class="form__group">
                    <input 
                        type="checkbox"
                        name="is_visible"
                        id="is_visible"
                        {{ old('is_visible') ? 'checked' : '' }}>
                    <label for="is_visible">
                        <div class="">
                            <svg class="checkbox__check" width="24" height="24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Visible
                    </label>
                    @error('is_visible')
                        <span class="danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="form__row">
                <button class="submit">Submit</button>
            </div>

        </form>
    </section>
@endsection