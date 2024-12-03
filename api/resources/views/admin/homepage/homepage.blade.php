@extends('admin.layouts.app')

@section('content')
    <section class="addVideo">
        <form action="{{ route('admin_homepage_save_videos') }}" class="addvideo__form" method="POST">
            @csrf
            @method('POST')

            {{-- @dd($data); --}}

            @for ($i = 0; $i < 5; $i++)
                <input type="hidden" name="editors_pick_video_id_{{$i + 1}}" id="editors_pick_video_id_{{$i + 1}}" value="{{ isset($data['editors_pick'][$i]) ? $data['editors_pick'][$i]['id'] : '' }}">
                <input type="hidden" name="new_video_id_{{$i + 1}}" id="new_video_id_{{$i + 1}}" value="{{ isset($data['new'][$i]) ? $data['new'][$i]['id'] : '' }}">
                <input type="hidden" name="throwback_video_id_{{$i + 1}}" id="throwback_video_id_{{$i + 1}}" value="{{ isset($data['throwback'][$i]) ? $data['throwback'][$i]['id'] : '' }}">
            @endfor

            <h2>Editor's Pick Videos Selection</h2>

            @for ($i = 0; $i < 5; $i++)

                {{-- Video Name Input --}}
                <div class="form__row">
                    <h3>Video #{{$i + 1}}</h3>
                    <div class="form__group">
                        <input 
                            type="text" 
                            placeholder=""
                            id="editors_pick_{{$i}}"
                            value="{{ isset($data['editors_pick'][$i]) ? $data['editors_pick'][$i]['artist'] . ' - ' . $data['editors_pick'][$i]['title'] : '' }}">
                        <label for="editors_pick_{{$i}}">
                            @error("editors_pick_$i")
                                <span class="danger">{{ $message }}</span>
                            @else
                                <span>Start typing video name or artists name...</span>
                            @enderror
                        </label>
                        <div 
                            class="form__group--loader"
                            data-hidden-input-id="editors_pick_video_id_{{$i + 1}}">
                            @include('admin.parts.linearPreloader')
                        </div>
                    </div>
                </div> 

            @endfor

            <br><br><br>
            <h2>New Videos Selection</h2>

            @for ($i = 0; $i < 5; $i++)
                {{-- Video Name Input --}}
                <div class="form__row">
                    <h3>Video #{{$i + 1}}</h3>
                    <div class="form__group">
                        <input 
                            type="text" 
                            placeholder=""
                            id="new_{{$i}}"
                            value="{{ isset($data['new'][$i]) ? $data['new'][$i]['artist'] . ' - ' . $data['new'][$i]['title'] : '' }}">
                        <label for="new_{{$i}}">
                            @error("new_$i")
                                <span class="danger">{{ $message }}</span>
                            @else
                                <span>Start typing video name or artists name...</span>
                            @enderror
                        </label>
                        <div 
                            class="form__group--loader"
                            data-hidden-input-id="new_video_id_{{$i + 1}}">
                            @include('admin.parts.linearPreloader')
                        </div>
                    </div>
                </div>                
            @endfor

            <br>
            <br>
            <br>
            <h2>Throwback Videos Selection</h2>

            @for ($i = 0; $i < 5; $i++)
                {{-- Video Name Input --}}
                <div class="form__row">
                    <h3>Video #{{$i + 1}}</h3>
                    <div class="form__group">
                        <input 
                            type="text" 
                            placeholder=""
                            id="throwback_{{$i}}"
                            value="{{ isset($data['throwback'][$i]) ? $data['throwback'][$i]['artist'] . ' - ' . $data['throwback'][$i]['title'] : '' }}">
                        <label for="throwback_{{$i}}">
                            @error("throwback_$i")
                                <span class="danger">{{ $message }}</span>
                            @else
                                <span>Start typing video name or artists name...</span>
                            @enderror
                        </label>
                        <div 
                            class="form__group--loader"
                            data-hidden-input-id="throwback_video_id_{{$i + 1}}">
                            @include('admin.parts.linearPreloader')
                        </div>
                    </div>
                </div>                
            @endfor

            <div class="form__row">
                <div class="form__group">
                    <button type="submit">Submit</button>
                </div>
            </div>
        </form>
    </section>
    
@endsection