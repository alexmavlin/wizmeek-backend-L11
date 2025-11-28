@extends('admin.layouts.app');

@section('content')
    <section class="artists__header">
        <h1>Deleted Videos</h1>
        <form action="{{ route('admin_youtube_video_index') }}" method="GET" class="full_width">
            <div class="form__row">
                <div class="form__group">
                    <label for="genre">Genre: </label>
                    <select name="genre" id="genre">
                        @foreach ($data['genres'] as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->genre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__group">
                    <label for="country">Country:</label>
                    <select name="country" id="country">
                        @foreach ($data['countries'] as $country)
                            <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="form__group">
                    <label for="flags">Flags:</label>
                    <select name="flags" id="flags">
                        <option value="new" {{ request('flags') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="editors_pick" {{ request('flags') == 'editors_pick' ? 'selected' : '' }}>Editor's Pick</option>
                        <option value="throwback" {{ request('flags') == 'throwback' ? 'selected' : '' }}>Throwback</option>
                    </select>
                </div> --}}
                <div class="form__group">
                    <input type="text" name="filter_expression" value="{{ request('filter_expression') }}" placeholder="">
                    <label for="">Search by song title</label>
                    <button class="inside_input" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </section>  
    <section class="artists__list">
        @foreach ($data["videos"] as $video)
            <div class="artists__list--row">
                <img src="{{ asset($video->thumbnail) }}" class="quadrilateral" alt="{{ $video->title }}" width="100" height="100">
                <div class="artists__name">
                    <p>{{ $video->title }}</p>
                    <p>{{ $video->artist->name }}</p>
                </div>
                <div class="artists__genre">
                    <p>Genre: {{ $video->genre?->genre ?? 'N/A' }}</p>
                </div>
                <div class="artists__country">
                    <p>Country:</p>
                    <img src="{{ asset($video->country->flag) }}" alt="{{ $video->country->name }}">
                </div>
                <div class="artists__list--actions">
                    <a 
                        class="button restoreBtn"
                        href="#"
                        data-form-action="{{ route('admin_restore_youtube_video', $video->id) }}"
                        data-text="Are you sure You want to restore Video - {{ $video->title }} by {{ $video->artist->name }}"
                        data-description="This action will restore the video along with any associated artists, genres, or countries that were deleted previously.">
                        Restore Video
                    </a>
                    <a 
                        class="delete deleteBtn" 
                        href="#" 
                        data-form-action="{{ route('admin_destroy_youtube_video', $video->id) }}"
                        data-text="Are you sure You want to delete Video - {{ $video->title }} by {{ $video->artist->name }}"
                        data-description="Once deleted, this video cannot be restored.">
                        Delete Forever
                    </a>
                </div>
                <div class="artists__flags">
                    <p>Flags</p>
                    <p>New: <span class="{{ $video->new ? 'active' : '' }}">
                        <svg class="checkbox__check" width="24" height="24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </span></p>
                    <p>Editor's Pick: <span class="{{ $video->editors_pick ? 'active' : '' }}">
                        <svg class="checkbox__check" width="24" height="24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>    
                    </span></p>
                    <p>Throwback: <span class="{{ $video->throwback ? 'active' : '' }}">
                        <svg class="checkbox__check" width="24" height="24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>    
                    </span></p>
                </div>
            </div>
        @endforeach
    </section>
    <section class="pagination">
        {{ $data["videos"]->links('vendor.pagination.default') }}
    </section>
@endsection