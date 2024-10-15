@extends('admin.layouts.app')

@section('content')
    <h1>Welcome to Wizmeek Dashboard!</h1>
    <section class="userStats">
        <div class="userStats_item">
            <p>Registered Users</p>
            <span>{{ $data["user_stats"]["total_users"] }}</span>
        </div>
        <div class="userStats_item">
            <p>Registered Today</p>
            <span>{{ $data["user_stats"]["today_registered_users"] }}</span>
        </div>
        <div class="userStats_item">
            <p>Active Users Now</p>
            <span>0</span>
        </div>
    </section>
@endsection