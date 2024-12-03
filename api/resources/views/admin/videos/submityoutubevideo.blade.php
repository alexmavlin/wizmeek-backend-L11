@extends('admin.layouts.app')

@section('content')
    <section class="addVideo">
        <form action="{{ route('admin_store_youtube_video') }}" class="addvideo__form" method="POST">
            @csrf
            @method('POST')

            <input
                type="hidden" 
                name="youtube_id"
                value="{{ old('youtube_id') ?: '' }}"
                id="youtube_id">
            <input 
                type="hidden"
                name="thumbnail"
                value="{{ old('thumbnail') ?: '' }}"
                id="thumbnail">

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
                    @error('content_type_id')
                        <span class="danger">{{ $message }}</span>
                    @enderror
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
                    @error('genre_id')
                        <span class="danger">{{ $message }}</span>
                    @enderror
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
                    @error('country_id')
                        <span class="danger">{{ $message }}</span>
                    @enderror
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
                    <label for="editors_pick">
                        <div class="">
                            <svg class="checkbox__check" width="24" height="24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Editors Pick
                    </label>
                    @error('editors_pick')
                        <span class="danger">{{ $message }}</span>
                    @enderror
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
                    <label for="new">
                        <div class="">
                            <svg class="checkbox__check" width="24" height="24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        New
                    </label>
                    @error('new')
                        <span class="danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Editors Pick --}}
            <div class="form__row">
                <div class="form__group">
                    <input 
                        type="radio"
                        name="new"
                        value="throwback"
                        id="throwback"
                        {{ old('new') == 'throwback' ? 'checked' : '' }}>
                    <label for="throwback">
                        <div class="">
                            <svg class="checkbox__check" width="24" height="24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Throwback
                    </label>
                    @error('throwback')
                        <span class="danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form__row">
                <div class="form__group">
                    <button type="submit">Submit</button>
                </div>
            </div>
        </form>
    </section>
@endsection