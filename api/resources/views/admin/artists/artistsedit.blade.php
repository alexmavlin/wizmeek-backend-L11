@extends('admin.layouts.app')

@section('content')
    <h1>Edit Artist - {{ $data["artist"]->name }}</h1>
    <section class="edit_form">
        <form action="{{ route('admin_artists_update', $data["artist"]->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('post')

            {{-- Name Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="text" placeholder="" name="name" value="{{ old('name') ?: $data["artist"]->name }}">
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
                            <span>Avatar</span>
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
                    <textarea name="short_description" placeholder="">{{ old('short_description') ?: $data["artist"]->short_description }}</textarea>
                    <label for="">
                        @error('short_description')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Short Description</span>
                        @enderror
                    </label>
                    <span>Supports basic HTML tags (h1-h6, span, p, br, strong) and inline styles*</span>
                </div>
                <div class="form__froup">
                    <textarea name="full_description" placeholder="">{{ old('full_description') ?: $data["artist"]->full_description }}</textarea>
                    <label for="">
                        @error('full_description')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Full Description</span>
                        @enderror
                    </label>
                    <span>Supports basic HTML tags (h1-h6, span, p, br, strong) and inline styles*</span>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="form__row">
                <button class="submit">Submit</button>
            </div>

        </form>
    </section>
@endsection