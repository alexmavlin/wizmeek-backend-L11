@extends('admin.layouts.app')

@section('content')
    <h1>Create new Genre</h1>
    <section class="create_form">
        <form action="{{ route('admin_subscribers_sendglobalemail') }}" method="POST">
            @csrf
            @method('post')

            {{-- Message Title --}}
            <div class="form__group" style="margin-bottom: 20px">
                <div class="form__row">
                    <div class="form__froup">
                        <input type="text" placeholder="" name="title" value="{{ old('title') ?: '' }}">
                        <label for="">
                            @error('title')
                                <span class="danger">{{ $message }}</span>
                            @else
                                <span>Title*</span>
                            @enderror
                        </label>
                    </div>
                </div>
            </div>

            {{-- Message Text --}}
            <div class="form__group">
                <textarea name="message" placeholder="">{{ old('message') ?: '' }}</textarea>
                <label for="message">
                    @error('message')
                        <span class="danger">{{ $message }}</span>
                    @else
                        <span>Message*</span>
                    @enderror
                </label>
                <span>Supports basic HTML tags (h1-h6, span, p, br strong) and inline styles*</span>
            </div>

            {{-- Submit Button --}}
            <div class="form__row">
                <button class="submit">Submit</button>
            </div>

        </form>
    </section>
@endsection