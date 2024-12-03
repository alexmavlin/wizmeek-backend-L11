@extends('admin.layouts.app');

@section('content')
    <section class="artists__header">
        <h1>Artists</h1>
        <form action="{{ route('admin_artists_index') }}" method="GET">
            @csrf
            @method('GET')
            <div class="form__froup">
                <input type="text" name="filter_expression" value="{{ request('filter_expression') }}" placeholder="">
                <label for="">Search by name</label>
                <button class="inside_input" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                    </svg>
                </button>
            </div>
        </form>
        <a href="{{ route('admin_artists_create') }}">Add New Artist</a>
    </section>  
    <section class="artists__list">
        @foreach ($data["artists"] as $artist)
            <div class="artists__list--row">
                <img src="{{ asset($artist->avatar) }}" alt="{{ $artist->name }}" width="100" height="100">
                <p>{{ $artist->name }}</p>
                <div class="artists__list--actions">
                    <a href="{{ route('admin_artists_edit', $artist->id) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin_artists_view', $artist->id) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/>
                        </svg>
                    </a>
                    <a 
                        class="delete deleteBtn" 
                        href="#" 
                        data-form-action="{{ route('admin_artists_delete', $artist->id) }}"
                        data-text="Are you sure You want to delete Artist - {{ $artist->name }}"
                        data-description="However, while the artist will be removed from the list, the record will remain in the database to prevent unexpected errors and exceptions, and may be restored later.">
                            Delete
                    </a>
                </div>
            </div>
        @endforeach
    </section>
    <section class="pagination">
        {{ $data["artists"]->links('vendor.pagination.default') }}
    </section>
@endsection