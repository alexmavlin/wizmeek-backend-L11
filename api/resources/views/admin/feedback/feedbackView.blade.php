@extends('admin.layouts.app')

@section('content')
    <section class="artist_header">
        <a class="delete deleteBtn" href="#" data-form-action="{{ route('admin_feedback_delete', $data['feedback']->id) }}"
            data-text='Are you sure You want to delete "{{ $data['feedback']->subject }}" feedback by {{ $data['feedback']->user->name }}'
            data-description="However, while the feedback will be removed from the list, the record will remain in the database to prevent unexpected errors and exceptions, and may be restored later.">
            Delete
        </a>
    </section>
    <section class="artist">
        <div class="artist__main">
            <img src="{{ asset('img/avatars/' . $data['feedback']->user->avatar) }}" alt="{{ $data['feedback']->user->name }}" width="150" height="150">
            <p>{{ $data['feedback']->user->name }}</p>
            <p>Respond to: <a href="mailTo:{{$data['feedback']->user->email}}">{{ $data['feedback']->user->email }}</a></p>
        </div>
        <div class="artist__descriptions">
            <p class="text_label">Subject</p>
            <p>{!! $data['feedback']->subject !!}</p>
            <p class="text_label">Message</p>
            <p>{!! $data['feedback']->message !!}</p>
        </div>
        <div class="">
            @foreach (json_decode($data['feedback']->files) as $file)
                <a href="{{ asset($file) }}" download>File</a>
            @endforeach
        </div>
    </section>
@endsection
