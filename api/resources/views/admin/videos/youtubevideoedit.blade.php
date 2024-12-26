@extends('admin.layouts.app')

@section('content')
    <section class="addVideo">
        <form action="{{ route('admin_youtube_video_update', $data['video']->id) }}" class="addvideo__form" method="POST">
            @csrf
            @method('PUT')

            <input
                type="hidden" 
                name="youtube_id"
                value="{{ old('youtube_id') ?: $data['video']->youtube_id }}"
                id="youtube_id">
            <input 
                type="hidden"
                name="thumbnail"
                value="{{ old('thumbnail') ?: $data['video']->thumbnail }}"
                id="thumbnail">

            {{-- Video Link Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input 
                        type="text" 
                        placeholder="" 
                        name="original_link" 
                        value="{{ old('original_link') ?: $data['video']->original_link }}" 
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
                        src="https://www.youtube.com/embed/{{ $data['video']->youtube_id }}" 
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
                            <option value="{{ $content_type->id}}" {{ $data['video']->content_type_id == $content_type->id ? 'selected' : '' }}>{{ $content_type->name }}</option>
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
                        value="{{ old('artist_name') ?: $data['video']->artist->name }}" 
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
                        value="{{ old('title') ?: $data['video']->title }}" 
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
                        value="{{ old('release_date') ?: \Carbon\Carbon::parse($data['video']->release_date)->format('Y-m') }}">
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
                            <option 
                                value="{{ $genre->id}}" 
                                {{ ( old('genre_id') == $genre->id
                                || $data['video']->genre_id == $genre->id 
                                ) ? 'selected' : '' }}>{{ $genre->genre }}</option>
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
                            <option 
                                value="{{ $country->id}}"
                                {{ (
                                    old('country_id') == $country->id
                                    || $data['video']->country_id == $country->id
                                ) ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Editors Pick --}}
            <div class="form__row labelled row">
                <span class="form__row--label">Video Flags</span>
                <div class="form__group">
                    <input 
                        type="checkbox"
                        name="editors_pick"
                        id="editors_pick"
                        {{ old('editors_pick') ? 'checked' : ($data['video']->editors_pick ? 'checked' : '') }}>
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
                <div class="form__group">
                    <input 
                        type="checkbox"
                        name="new"
                        id="new"
                        value="new"
                        {{ old('new') == 'new' ? 'checked' : ($data['video']->new ? 'checked' : '') }}>
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
                <div class="form__group">
                    <input 
                        type="checkbox"
                        name="new"
                        value="throwback"
                        id="throwback"
                        {{ old('new') == 'throwback' ? 'checked' : ($data['video']->throwback ? 'checked' : '') }}>
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