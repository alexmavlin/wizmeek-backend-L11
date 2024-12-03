<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(array_merge(['resources/scss/app.scss', 'resources/scss/popup.scss'], $data["scss"]))
    </head>
    <body>
        <header class="header">
            <div class="container">
                <div class="header__inner">
                    <a 
                        href="{{ route('admin_dashboard') }}"
                        title="Wizmeek Dashboard" 
                        class="header__logo">
                        <img 
                            src="{{ asset('img/wizmeek_logo.webp') }}" 
                            alt="Wizmeek"
                            width="150"
                            height="27">
                    </a>
                </div>
            </div>
        </header>
        <section class="content">
            <aside class="content__aside">
                <nav>
                    <ul>
                        <li>
                            <a 
                                href="{{ route('admin_dashboard')}}"
                                class="{{ Route::is('admin_dashboard') ? 'active' : '' }}">
                                    Dashboard
                                </a>
                        </li>
                        <li>
                            <a 
                                href="{{ route('admin_homepage_index') }}"
                                class="{{ Route::is('admin_homepage_index') ? 'active' : '' }}">
                                    Home
                            </a>
                        </li>
                        <li>
                            <a 
                                href="{{ route('admin_submit_youtube_video')}}"
                                class="{{ Route::is('admin_submit_youtube_video') ? 'active' : '' }}">
                                    Submit Video
                            </a>
                        </li>
                        <li>
                            <a 
                                href="{{ route('admin_youtube_video_index') }}"
                                class="{{ (
                                    Route::is('admin_youtube_video_index')
                                    || Route::is('admin_youtube_video_edit')
                                    || Route::is('admin_youtube_videos_deleted')) ? 'active' : '' }}">
                                    All Videos
                            </a>
                        </li>
                        <li>
                            <a 
                                href="{{ route('admin_genres_index') }}"
                                class="{{ (
                                    Route::is('admin_genres_index')
                                    || Route::is('admin_genres_create')
                                    || Route::is('admin_genres_view')
                                    || Route::is('admin_genres_edit')) ? 'active' : '' }}">
                                    Music Genre
                            </a>
                        </li>
                        <li>
                            <a 
                                href="{{ route('admin_artists_index') }}"
                                class="{{ (
                                    Route::is('admin_artists_index')
                                    || Route::is('admin_artists_create') 
                                    || Route::is('admin_artists_view')
                                    || Route::is('admin_artists_edit')) ? 'active' : '' }}">
                                    Artists
                            </a>
                        </li>
                        <li>
                            <a 
                                href="{{ route('admin_landing_index')}}"
                                class="{{ (
                                    Route::is('admin_landing_index')) ? 'active' : '' }}">
                                    Landing Page
                            </a>
                        </li>
                        <li><a href="#">Feedback</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Users</a></li>
                        <li>
                            <a 
                                href="{{ route('admin_subscribers_index') }}"
                                class="{{ (
                                    Route::is('admin_subscribers_index')) ? 'active' : '' }}">
                                    Subscribers
                            </a>
                        </li>
                        <li style="margin-top: 30px">
                            <a 
                                href="{{ route('admin_logout') }}">
                                    Logout
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>
            <div class="content__inner">
                @yield('content')
            </div>
        </section>
        @include('admin.layouts.popup')

        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        @vite(array_merge(['resources/js/admin/popup.js'], $data['js']))
    </body>
</html>
