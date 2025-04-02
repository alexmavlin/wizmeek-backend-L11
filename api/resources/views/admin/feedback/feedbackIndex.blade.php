@extends('admin.layouts.app');

@section('content')
    <section class="artists__header">
        <h1>Feedback List</h1>
        <form action="{{ route('admin_feedback_index') }}" method="GET" class="full_width">
            <div class="form__row">
                <div class="form__group">
                    <input type="text" name="search_string" value="{{ request('search_string') }}" placeholder="">
                    <label for="search_string">Search</label>
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
        @foreach ($data['feedbacks'] as $feedback)
            <div class="artists__list--row">
                <img src="{{ asset('img/avatars/' . $feedback->user->avatar) }}" class="quadrilateral" alt="{{ $feedback->user->name }}" width="100" height="100">
                <div class="artists__name">
                    <p>{{ $feedback->user->name }}</p>
                </div>
                <div class="artists__genre">
                    <p>Subject: {{ $feedback->subject ?? 'N/A' }}</p>
                </div>
                <div class="artists__list--actions">
                    <a href="{{ route('admin_feedback_view', $feedback->id) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/>
                        </svg>
                    </a>
                    <a 
                        class="delete deleteBtn" 
                        href="#" 
                        data-form-action="{{ route('admin_feedback_delete', $feedback->id) }}"
                        data-text='Are you sure You want to delete "{{ $feedback->subject }}" feedback by {{ $feedback->user->name }}'
                        data-description="However, while the feedback will be removed from the list, the record will remain in the database to prevent unexpected errors and exceptions, and may be restored later.">
                            Delete
                    </a>
                </div>
                <div class="artists__flags">
                    <p>Details</p>
                    <p>Stored: {{ date('d M Y', strtotime($feedback->created_at)) }}</p>
                </div>
            </div>
        @endforeach
    </section>
    <section class="pagination">
        {{ $data["feedbacks"]->links('vendor.pagination.default') }}
    </section>
@endsection