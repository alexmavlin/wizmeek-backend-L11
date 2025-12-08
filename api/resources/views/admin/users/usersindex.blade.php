@extends('admin.layouts.app')

@section('content')
    <section class="artist_header">
        <h1>Users</h1>
        {{-- <a href="{{ route('admin_youtube_video_edit', $data['video']['id']) }}">
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
        </a> --}}
    </section>

    <section class="comments">
        <div class="comments_search">
            <form action="{{ route('admin_users_index') }}">
                <input type="text" placeholder="Search..." name="search_user"
                    value="{{ request('search_user') ?? '' }}">
                <button>Go</button>
            </form>
        </div>
        <table class="comments_table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['users'] as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <img src="{{ $user->avatar ? asset('img/avatars/' . $user->avatar) : asset('img/avatars/noAvatar.webp') }}"
                                alt="{{ $user->name }}" width="30" height="30">
                            
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            {{ date('d F Y', strtotime($user->created_at)) }}
                        </td>
                        <td>
                            <a href="#" class="delete deleteBtn"
                                data-form-action="{{ route('admin_users_destroy', $user->id) }}"
                                data-text="Are you sure You want to delete user - {{ $user->name }}"
                                data-description="This action will remove a user from system forever without a possibility to restore it later.">
                                Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    <section class="pagination">
        {{ $data['users']->onEachSide(1)->links('vendor.pagination.default') }}
    </section>

@endsection