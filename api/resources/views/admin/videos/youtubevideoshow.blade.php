@extends('admin.layouts.app')

@section('content')
    <section class="artist_header">
        <h1>{{ $data['video']['artist'] }} - {{ $data['video']['title'] }}</h1>
        <a href="{{ route('admin_youtube_video_edit', $data['video']['id']) }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path
                    d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
            </svg>
        </a>
        <a href="#" class="delete deleteBtn"
            data-form-action="{{ route('admin_youtube_video_delete', $data['video']['id']) }}"
            data-text="Are you sure You want to delete Video - {{ $data['video']['title'] }} by {{ $data['video']['artist'] }}"
            data-description="However, while the video will be removed from the list, the record will remain in the database to prevent unexpected errors and exceptions, and may be restored later.">
            Delete
        </a>
    </section>

    <section class="video">
        {{-- Iframe --}}
        <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $data['video']['youtube_id'] }}"
            title="YouTube video player" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen id="iframe">
        </iframe>

        <div class="video_stats">
            <div class="video_stats_section">
                <div class="video_stats_section_row">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path
                            d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                    </svg>
                    <span>{{ $data['video']['views'] }}</span>
                    <span>views.</span>
                </div>
                <div class="video_stats_section_row">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z" />
                    </svg>
                    <span>{{ $data['video']['nLikes'] }}</span>
                    <span>likes.</span>
                </div>
                <div class="video_stats_section_row">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M500.3 7.3C507.7 13.3 512 22.4 512 32l0 144c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48l0-57L352 90.2 352 208c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48l0-96c0-15.3 10.8-28.4 25.7-31.4l160-32c9.4-1.9 19.1 .6 26.6 6.6zM74.7 304l11.8-17.8c5.9-8.9 15.9-14.2 26.6-14.2l61.7 0c10.7 0 20.7 5.3 26.6 14.2L213.3 304l26.7 0c26.5 0 48 21.5 48 48l0 112c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 352c0-26.5 21.5-48 48-48l26.7 0zM192 408a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM478.7 278.3L440.3 368l55.7 0c6.7 0 12.6 4.1 15 10.4s.6 13.3-4.4 17.7l-128 112c-5.6 4.9-13.9 5.3-19.9 .9s-8.2-12.4-5.3-19.2L391.7 400 336 400c-6.7 0-12.6-4.1-15-10.4s-.6-13.3 4.4-17.7l128-112c5.6-4.9 13.9-5.3 19.9-.9s8.2 12.4 5.3 19.2zm-339-59.2c-6.5 6.5-17 6.5-23 0L19.9 119.2c-28-29-26.5-76.9 5-103.9c27-23.5 68.4-19 93.4 6.5l10 10.5 9.5-10.5c25-25.5 65.9-30 93.9-6.5c31 27 32.5 74.9 4.5 103.9l-96.4 99.9z" />
                    </svg>
                    <span>Genre:</span>
                    <span>{{ $data['video']['genre'] }}</span>
                </div>
            </div>
            <div class="video_stats_section">
                <div class="video_stats_section_row">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path
                            d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z" />
                    </svg>
                    <span>{{ $data['video']['favorite_by_user_count'] }}</span>
                    <span>users added this video to favorites.</span>
                </div>
                <div class="video_stats_section_row">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path
                            d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                    </svg>
                    <span>{{ $data['video']['in_user_profile_count'] }}</span>
                    <span>users added this video to their profile.</span>
                </div>
                <div class="video_stats_section_row">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path
                            d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zM329 305c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-95 95-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L329 305z" />
                    </svg>
                    <span>Release year:</span>
                    <span>{{ $data['video']['release_year'] }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="comments">
        <div class="comments_search">
            <form action="{{ route('admin_show_youtube_video', $data['video']['youtube_id']) }}">
                <input type="text" placeholder="Search..." name="comment_search"
                    value="{{ request('comment_search') ?? '' }}">
                <button>Go</button>
            </form>
        </div>
        <table class="comments_table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['comments'] as $comment)
                    <tr>
                        <td>{{ $comment->id }}</td>
                        <td>
                            <img src="{{ $comment->user->avatar ? asset('img/avatars/' . $comment->user->avatar) : asset('img/avatars/noAvatar.webp') }}"
                                alt="{{ $comment->user->name }}" width="30" height="30">
                            <p>{{ $comment->user->name }}</p>
                        </td>
                        <td>
                            {{ $comment->content }}
                        </td>
                        <td>
                            {{ date('d F Y', strtotime($comment->created_at)) }}
                        </td>
                        <td>
                            <a href="#" class="delete deleteBtn"
                                data-form-action="{{ route('admin_delete_comment', $comment->id) }}"
                                data-text="Are you sure You want to delete comment from {{ $comment->user->name }}"
                                data-description="This action will delete the comment forever without a possibility to restore it later.">
                                Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    <section class="pagination">
        {{ $data['comments']->onEachSide(1)->links('vendor.pagination.default') }}
    </section>
@endsection
