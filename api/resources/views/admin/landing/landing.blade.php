@extends('admin.layouts.app')

@section('content')
    <section class="addVideo">
        <form action="{{ route('admin_landing_save_videos') }}" class="addvideo__form" method="POST">
            @csrf
            @method('POST')

            {{-- @dd($data); --}}

            <input type="hidden" name="video_id_1" id="video_id_1" value="{{ isset($data['videos'][0]) ? $data['videos'][0]->videos->id : '' }}">
            <input type="hidden" name="video_id_2" id="video_id_2" value="{{ isset($data['videos'][1]) ? $data['videos'][1]->videos->id : '' }}">
            <input type="hidden" name="video_id_3" id="video_id_3" value="{{ isset($data['videos'][2]) ? $data['videos'][2]->videos->id : '' }}">

            {{-- Video Name Input --}}
            <div class="form__row">
                <h3>Video #1</h3>
                <div class="form__group">
                    <input 
                        type="text" 
                        placeholder=""
                        id="name1"
                        value="{{ isset($data['videos'][0]) ? $data['videos'][0]->videos->artist->name . ' - ' . $data['videos'][0]->videos->title : '' }}">
                    <label for="name1">
                        @error('name1')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Start typing video name or artists name...</span>
                        @enderror
                    </label>
                    <div 
                        class="form__group--loader"
                        data-hidden-input-id="video_id_1">
                        @include('admin.parts.linearPreloader')
                    </div>
                </div>
            </div>

            {{-- Video Name Input --}}
            <div class="form__row">
                <h3>Video #2</h3>
                <div class="form__group">
                    <input 
                        type="text" 
                        placeholder=""
                        id="name1"
                        value="{{ isset($data['videos'][1]) ? $data['videos'][1]->videos->artist->name . ' - ' . $data['videos'][1]->videos->title : '' }}">
                    <label for="name1">
                        @error('name1')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Start typing video name or artists name...</span>
                        @enderror
                    </label>
                    <div 
                        class="form__group--loader"
                        data-hidden-input-id="video_id_2">
                        @include('admin.parts.linearPreloader')
                    </div>
                </div>
            </div>

            {{-- Video Name Input --}}
            <div class="form__row">
                <h3>Video #3</h3>
                <div class="form__group">
                    <input 
                        type="text" 
                        placeholder=""
                        id="name1"
                        value="{{ isset($data['videos'][2]) ? $data['videos'][2]->videos->artist->name . ' - ' . $data['videos'][2]->videos->title : '' }}">
                    <label for="name1">
                        @error('name1')
                            <span class="danger">{{ $message }}</span>
                        @else
                            <span>Start typing video name or artists name...</span>
                        @enderror
                    </label>
                    <div 
                        class="form__group--loader"
                        data-hidden-input-id="video_id_3">
                        @include('admin.parts.linearPreloader')
                    </div>
                </div>
            </div>

            <div class="form__row">
                <div class="form__group">
                    <button type="submit">Submit</button>
                </div>
            </div>
        </form>
    </section>
    
@endsection