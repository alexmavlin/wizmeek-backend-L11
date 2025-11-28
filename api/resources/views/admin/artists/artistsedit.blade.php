@extends('admin.layouts.app')

@section('content')
    <h1>Edit Artist - {{ $data['artist']->name }}</h1>
    <section class="edit_form">
        <form action="{{ route('admin_artists_update', $data['artist']->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('post')

            {{-- Name Input --}}
            <div class="form__row">
                <div class="form__froup">
                    <input type="text" placeholder="" name="name" value="{{ old('name') ?: $data['artist']->name }}">
                    <label for="">
                        @error('name')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Name*</span>
                        @enderror
                    </label>
                </div>

                {{-- Genre Selection --}}
                <div class="form__group">
                    <div class="custom-multiselect {{ old('genres') ? 'focused' : '' }}">
                        @error('genres[]')
                            <span class="selected-options-label danger">{{ $message }}</span>
                        @else
                            <span class="selected-options-label">Genres*</span>
                        @enderror
                        <div class="selected-options" id="selected-options">
                            @if (old('genres'))
                                @foreach (old('genres') as $genreId)
                                    @php
                                        $genre = collect($data['genres'])->firstWhere('id', $genreId);
                                    @endphp
                                    @if ($genre)
                                        <div class="tag" data-id="{{ $genre['id'] }}"
                                            data-label="{{ $genre['genre'] }}">
                                            {{ $genre['genre'] }}
                                            <span class="remove" data-id="{{ $genre['id'] }}">×</span>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                @foreach ($data['artist']->genres as $item)
                                    @php
                                        $genre = collect($data['genres'])->firstWhere('id', $item->id);
                                    @endphp
                                    @if ($genre)
                                        <div class="tag" data-id="{{ $genre->id }}"
                                            data-label="{{ $genre->genre }}">
                                            {{ $genre->genre }}
                                            <span class="remove" data-id="{{ $genre->id }}">×</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="dropdown" id="dropdown">
                            <input type="text" class="search-input" placeholder="Search..." />
                            <div class="options options-list" id="options-list">
                                @foreach ($data['genres'] as $genre)
                                    <div class="option" data-id="{{ $genre['id'] }}" data-label="{{ $genre['genre'] }}">
                                        {{ $genre['genre'] }}</div>
                                @endforeach
                            </div>
                        </div>
                        <select name="genres[]" class="hidden-select" multiple hidden>
                            @if (old('genres'))
                                @foreach (old('genres') as $genreId)
                                    @php
                                        $genre = collect($data['genres'])->firstWhere('id', $genreId);
                                    @endphp
                                    @if ($genre)
                                        <option value="{{ $genre['id'] }}" selected>{{ $genre['genre'] }}</option>
                                    @endif
                                @endforeach
                            @else
                                @foreach ($data['artist']->genres as $genre)
                                    @php
                                        $genre = collect($data['genres'])->firstWhere('id', $genre->id);
                                    @endphp
                                    @if ($genre)
                                        <option value="{{ $genre->id }}" selected>{{ $genre->genre }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                {{-- // Genre Selection --}}

                {{-- Countries Selection --}}
                <div class="form__group">
                    <div class="custom-multiselect {{ old('countries') ? 'focused' : '' }}">
                        @error('countries')
                            <span class="selected-options-label danger">{{ $message }}</span>
                        @else
                            <span class="selected-options-label">Countries*</span>
                        @enderror
                        <div class="selected-options" id="selected-options">
                            @if (old('countries'))
                                @foreach (old('countries') as $countryId)
                                    @php
                                        $country = collect($data['countries'])->firstWhere('id', $countryId);
                                    @endphp
                                    @if ($country)
                                        <div class="tag" data-id="{{ $country['id'] }}"
                                            data-label="{{ $country['name'] }}">
                                            {{ $country['name'] }}
                                            <span class="remove" data-id="{{ $country['id'] }}">×</span>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                @foreach ($data['artist']->countries as $item)
                                    @php
                                        $country = collect($data['countries'])->firstWhere('id', $item->id);
                                    @endphp
                                    @if ($country)
                                        <div class="tag" data-id="{{ $country->id }}"
                                            data-label="{{ $country->name }}">
                                            {{ $country->name }}
                                            <span class="remove" data-id="{{ $country->id }}">×</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="dropdown" id="dropdown">
                            <input type="text" class="search-input" placeholder="Search..." />
                            <div class="options options-list" id="options-list">
                                @foreach ($data['countries'] as $country)
                                    <div class="option" data-id="{{ $country['id'] }}" data-label="{{ $country['name'] }}">
                                        {{ $country['name'] }}</div>
                                @endforeach
                            </div>
                        </div>
                        <select name="countries[]" class="hidden-select" multiple hidden>
                            @if (old('countries'))
                                @foreach (old('countries') as $countryId)
                                    @php
                                        $country = collect($data['countries'])->firstWhere('id', $countryId);
                                    @endphp
                                    @if ($country)
                                        <option value="{{ $country['id'] }}" selected>{{ $country['name'] }}</option>
                                    @endif
                                @endforeach
                            @else
                                @foreach ($data['artist']->countries as $country)
                                    @php
                                        $country = collect($data['countries'])->firstWhere('id', $country->id);
                                    @endphp
                                    @if ($country)
                                        <option value="{{ $country->id }}" selected>{{ $country->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                {{-- // Countries Selection --}}
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
                    <span>For better performance use images that are converted to .webp fomat, and resized to 300px
                        width.<br>
                        To ensure better images quality use <a href="https://imageresizer.com/" title="Image converter"
                            target="_blank">official converter</a>.
                    </span>
                </div>
            </div>

            {{-- Description Textareas --}}
            <div class="form__row">
                <div class="form__froup">
                    <textarea name="short_description" placeholder="">{{ old('short_description') ?: $data['artist']->short_description }}</textarea>
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
                    <textarea name="full_description" placeholder="">{{ old('full_description') ?: $data['artist']->full_description }}</textarea>
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

            {{-- Social Links --}}
            <div class="form__row labelled row" style="padding-top: 35px">
                <span class="form__row--label">Social Links</span>

                {{-- Spotify --}}
                <div class="form__froup">
                    <input type="text" placeholder="" name="spotify_link" id="spotify_link"
                        value="{{ old('spotify_link') ?: $data['artist']->spotify_link }}">
                    <label for="spotify_link">
                        @error('spotify_link')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Spotify profile link</span>
                        @enderror
                    </label>
                </div>

                {{-- Apple Music --}}
                <div class="form__froup">
                    <input type="text" placeholder="" name="apple_music_link" id="apple_music_link"
                        value="{{ old('apple_music_link') ?: $data['artist']->apple_music_link }}">
                    <label for="apple_music_link">
                        @error('apple_music_link')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Apple Music profile link</span>
                        @enderror
                    </label>
                </div>

                {{-- Instagram --}}
                <div class="form__froup">
                    <input type="text" placeholder="" name="instagram_link" id="instagram_link"
                        value="{{ old('instagram_link') ?: $data['artist']->instagram_link }}">
                    <label for="instagram_link">
                        @error('instagram_link')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Instagram profile link</span>
                        @enderror
                    </label>
                </div>
            </div>

            {{-- Additional settings --}}
            <div class="form__row labelled row">
                <span class="form__row--label">Additional Settings</span>
                <div class="form__group">
                    <input type="checkbox" name="is_visible" id="is_visible"
                        {{ old('is_visible') ? 'checked' : ($data['artist']->is_visible ? 'checked' : '') }}>
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
