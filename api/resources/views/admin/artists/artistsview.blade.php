@extends('admin.layouts.app')

@section('content')
    <section class="artist_header">
        <a href="{{ route('admin_artists_edit', $data["artist"]->id) }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
            </svg>
        </a>
        <a 
            href="#" 
            class="delete deleteBtn"
            data-form-action="{{ route('admin_artists_delete', $data["artist"]->id) }}"
            data-text="Are you sure You want to delete Artist - {{ $data["artist"]->name }}"
            data-description="However, while the artist will be removed from the list, the record will remain in the database to prevent unexpected errors and exceptions, and may be restored later.">
                Delete
        </a>
    </section>
    <section class="artist">
        <div class="artist__main">
            <img src="{{ asset($data["artist"]->avatar) }}" alt="{{ $data["artist"]->name }}" width="150" height="150">
            <p>{{ $data["artist"]->name }}</p>
        </div>
        <div class="artist__descriptions">
            <p class="text_label">Short description</p>
            <p>{!! $data["artist"]->short_description !!}</p>
            <p class="text_label">Full description</p>
            <p>{!! $data["artist"]->full_description !!}</p>
        </div>
    </section>
@endsection