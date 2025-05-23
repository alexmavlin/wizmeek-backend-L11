@extends('admin.layouts.app');

@section('content')
    <section class="artists__header">
        <h1>Videos</h1>
        <form action="{{ route('admin_youtube_video_index') }}" method="GET" class="full_width">
            <div class="form__row">
                <div class="form__group">
                    <label for="genre">Genre: </label>
                    <select name="genre" id="genre">
                        <option value="">Select Genre</option>
                        @foreach ($data['genres'] as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->genre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__group">
                    <label for="country">Country:</label>
                    <select name="country" id="country">
                        <option value="">Select Country</option>
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
        <a href="{{ route('admin_submit_youtube_video') }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/>
            </svg>
            Submit Video
        </a>
        <a href="{{ route('admin_youtube_videos_drafts') }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M469.3 19.3l23.4 23.4c25 25 25 65.5 0 90.5l-56.4 56.4L322.3 75.7l56.4-56.4c25-25 65.5-25 90.5 0zM44.9 353.2L299.7 98.3 413.7 212.3 158.8 467.1c-6.7 6.7-15.1 11.6-24.2 14.2l-104 29.7c-8.4 2.4-17.4 .1-23.6-6.1s-8.5-15.2-6.1-23.6l29.7-104c2.6-9.2 7.5-17.5 14.2-24.2zM249.4 103.4L103.4 249.4 16 161.9c-18.7-18.7-18.7-49.1 0-67.9L94.1 16c18.7-18.7 49.1-18.7 67.9 0l19.8 19.8c-.3 .3-.7 .6-1 .9l-64 64c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0l64-64c.3-.3 .6-.7 .9-1l45.1 45.1zM408.6 262.6l45.1 45.1c-.3 .3-.7 .6-1 .9l-64 64c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0l64-64c.3-.3 .6-.7 .9-1L496 350.1c18.7 18.7 18.7 49.1 0 67.9L417.9 496c-18.7 18.7-49.1 18.7-67.9 0l-87.4-87.4L408.6 262.6z"/>
            </svg>
            Drafts
        </a>
        <a href="{{ route('admin_youtube_videos_deleted') }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                <path d="M170.5 51.6L151.5 80l145 0-19-28.4c-1.5-2.2-4-3.6-6.7-3.6l-93.7 0c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80 368 80l48 0 8 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-8 0 0 304c0 44.2-35.8 80-80 80l-224 0c-44.2 0-80-35.8-80-80l0-304-8 0c-13.3 0-24-10.7-24-24S10.7 80 24 80l8 0 48 0 13.8 0 36.7-55.1C140.9 9.4 158.4 0 177.1 0l93.7 0c18.7 0 36.2 9.4 46.6 24.9zM80 128l0 304c0 17.7 14.3 32 32 32l224 0c17.7 0 32-14.3 32-32l0-304L80 128zm80 64l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16z"/>
            </svg>
            Deleted Videos
        </a>
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
                    <a href="{{ route('admin_youtube_video_edit', $video->id) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin_show_youtube_video', $video->youtube_id) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/>
                        </svg>
                    </a>
                    <a 
                        class="delete deleteBtn" 
                        href="#" 
                        data-form-action="{{ route('admin_youtube_video_delete', $video->id) }}"
                        data-text="Are you sure You want to delete Video - {{ $video->title }} by {{ $video->artist->name }}"
                        data-description="However, while the video will be removed from the list, the record will remain in the database to prevent unexpected errors and exceptions, and may be restored later.">
                            Delete
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
                    <p>Draft: <span class="{{ $video->is_draft ? 'active' : '' }}">
                        <svg class="checkbox__check" width="24" height="24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>    
                    </span></p>
                </div>
            </div>
        @endforeach
    </section>
    <section class="pagination">
        {{ $data["videos"]->onEachSide(1)->links('vendor.pagination.default') }}
    </section>
@endsection