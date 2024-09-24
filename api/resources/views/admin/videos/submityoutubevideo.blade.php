@extends('admin.layouts.app')

@section('content')
    <section class="addVideo">
        <form action="" class="addvideo__form">

            {{-- Video Link Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input 
                        type="text" 
                        placeholder="" 
                        name="original_link" 
                        value="{{ old('original_link') ?: '' }}" 
                        id="original_link"
                        data-youtube-api="{{ route('admin_get_youtube_video_data') }}">
                    <label for="original_link">
                        @error('original_link')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Youtube video link*</span>
                        @enderror
                    </label>
                </div>
            </div>

            {{-- Iframe --}}
            <div class="form__row">
                <div class="form__group">
                    <iframe 
                        width="560" 
                        height="315" 
                        src="" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        referrerpolicy="strict-origin-when-cross-origin" 
                        allowfullscreen
                        id="iframe">
                    </iframe>
                </div>
            </div>

            {{-- Content Type --}}
            <div class="form__row">
                <div class="form__group">
                    <select name="content_type_id" id="">
                        @foreach ($data['content_types'] as $content_type)
                            <option value="{{ $content_type->id}}">{{ $content_type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Artist Name --}}
            <div class="form__row">
                <div class="form__group">
                    <input 
                        type="text" 
                        placeholder="" 
                        name="artist_name" 
                        value="{{ old('artist_name') ?: '' }}" 
                        id="artist_name">
                    <label for="artist_name">
                        @error('artist_name')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Artist*</span>
                        @enderror
                    </label>
                </div>
            </div>

            {{-- Artist Name --}}
            <div class="form__row">
                <div class="form__group">
                    <input 
                        type="text" 
                        placeholder="" 
                        name="title" 
                        value="{{ old('title') ?: '' }}" 
                        id="title">
                    <label for="title">
                        @error('title')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Title*</span>
                        @enderror
                    </label>
                </div>
            </div>

            {{-- Release Date --}}
            <div class="form__row">
                <div class="form__group">
                    <input 
                        type="month" 
                        name="release_date" 
                        id="release_date"
                        value="{{ old('release_date') ?: '' }}">
                    <label for="release_date">
                        @error('release_date')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Release Date</span>
                        @enderror
                    </label>
                </div>
            </div>

            {{-- Genre --}}
            <div class="form__row">
                <div class="form__group">
                    <label for="genre_id">Genre</label>
                    <select name="genre_id" id="genre_id">
                        @foreach ($data['genres'] as $genre)
                            <option value="{{ $genre->id}}">{{ $genre->genre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Country --}}
            <div class="form__row">
                <div class="form__group">
                    <label for="country_id">Country</label>
                    <select name="country_id" id="country_id">
                        @foreach ($data['countries'] as $country)
                            <option value="{{ $country->id}}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Editors Pick --}}
            <div class="form__row">
                <div class="form__group">
                    <input 
                        type="checkbox"
                        name="editors_pick"
                        id="editors_pick"
                        {{ old('editors_pick') ? 'checked' : '' }}>
                    <label for="editors_pick">Editors Pick</label>
                </div>
            </div>

            {{-- NEW Flag --}}
            <div class="form__row">
                <div class="form__group">
                    <input 
                        type="radio"
                        name="new"
                        id="new"
                        value="new"
                        {{ old('new') == 'new' ? 'checked' : '' }}>
                    <label for="new">New</label>
                </div>
            </div>

            {{-- Editors Pick --}}
            <div class="form__row">
                <div class="form__group">
                    <input 
                        type="radio"
                        name="new"
                        id="throwback"
                        {{ old('new') == 'throwback' ? 'checked' : '' }}>
                    <label for="throwback">Throwback</label>
                </div>
            </div>

        </form>
    </section>
@endsection