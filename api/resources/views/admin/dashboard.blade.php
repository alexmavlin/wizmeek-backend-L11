@extends('admin.layouts.app')

@section('content')
    <h1>Welcome to Wizmeek Dashboard!</h1>
    <section class="userStats">
        <div class="userStats_item">
            <p>Registered Users</p>
            <span>{{ $data['user_stats']['total_users'] }}</span>
        </div>
        <div class="userStats_item">
            <p>Registered Today</p>
            <span>{{ $data['user_stats']['today_registered_users'] }}</span>
        </div>
        <div class="userStats_item">
            <p>Active Users Now</p>
            <span>0</span>
        </div>
    </section>

    <section class="media-statistics">
        <h2>All Media Statistics</h2>
        <div class="stats-table">
            @php
                $i = 1;
            @endphp
            @foreach ($data['media_stats'] as $stat)
                <div class="stats-table-row">
                    <div class="media-column">
                        @if ($i === 1)
                            <div class="section-label">Videos</div>
                        @else
                            <div class="section-splitter">
                            </div>
                        @endif
                        <div class="media-row" data-genre="{{ $stat['video']['genre'] }}">
                            <span class="label">{{ $stat['video']['genre'] }}</span>
                            <span>Today: {{ $stat['video']['stats']['today'] }}</span>
                            <span>{{ $stat['current_month'] }}: {{ $stat['video']['stats']['month'] }}</span>
                            <span>All Time: {{ $stat['video']['stats']['all_time'] }}</span>
                        </div>
                    </div>
                    <div class="media-column">
                        @if ($i === 1)
                            <div class="section-label">Audios</div>
                        @else
                            <div class="section-splitter">
                            </div>
                        @endif
                        <div class="media-row" data-genre="{{ $stat['audio']['genre'] }}">
                            <span class="label">{{ $stat['audio']['genre'] }}</span>
                            <span>Today: {{ $stat['audio']['stats']['today'] }}</span>
                            <span>{{ $stat['current_month'] }}: {{ $stat['audio']['stats']['month'] }}</span>
                            <span>All Time: {{ $stat['audio']['stats']['all_time'] }}</span>
                        </div>
                    </div>
                </div>
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
    </section>
@endsection
