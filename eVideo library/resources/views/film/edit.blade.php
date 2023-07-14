@extends('layouts.app')
  
@section('content')      
    <div class="card">
        <div class="card-header">{{ __('Edit') . ": " . __('Film') }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('film.update', $film) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="image_remove" id="image_remove" value="no">
                <div class="row mb-3">
                    <label for="name" class="col-2 col-form-label text-end">{{ __('Name') }}</label>
                    <div class="col-10">
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                        id="name" name="name" value="{{ old('name', $film->name) }}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="running_time" class="col-2 col-form-label text-end">{{ __('Running time') }}</label>
                    <div class="col-4">
                        <div class="input-group @error('running_h') is-invalid @enderror @error('running_m') is-invalid @enderror">
                            <input type="text" aria-label="First name" class="form-control" id="running_h" name="running_h" value="{{ old('running_h', $film->running_h) }}">
                            <input type="text" aria-label="Last name" class="form-control" id="running_m" name="running_m" value="{{ old('running_m', $film->running_m) }}">
                        </div>
                        @error('running_h')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('running_m')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label for="year" class="col-1 col-form-label text-end">{{ __('Year') }}</label>
                    <div class="col-2">
                        <input type="text" class="form-control @error('year') is-invalid @enderror"
                        id="year" name="year" value="{{ old('year', $film->year) }}">
                        @error('year')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <label for="rating" class="col-1 col-form-label text-end">{{ __('Rating') }}</label>
                    <div class="col-2">
                        <input type="text" class="form-control @error('rating') is-invalid @enderror"
                        id="rating" name="rating" value="{{ old('rating', $film->rating) }}">
                        @error('rating')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    
                </div>

                <div class="row mb-3 border border-start-0 border-top-0 border-end-0">
                    <label for="director" class="col-2 col-form-label text-end">{{ __('Directors') }}</label>
                    <div class="col-10">
                        @foreach($people as $p)
                        <div class="form-check form-check-inline col-3 @error('directors') is-invalid @enderror">
                            <input class="form-check-input" type="checkbox" 
                            name="directors[]" id="iChbDirectors{{$p->id}}" 
                            value="{{$p->id}}"
                            @checked(in_array($p->id, old('directors',$film->directors()->pluck('id')->toArray())))
                            >
                            <label class="form-check-label" for="iChbDirectors{{$p->id}}">{{$p->full_name}}</label>
                        </div>
                        @endforeach   


                        @error('directors')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3 border border-start-0 border-top-0 border-end-0">
                    <label for="director" class="col-2 col-form-label text-end">{{ __('Writers') }}</label>
                    <div class="col-10">
                        @foreach($people as $p)
                        <div class="form-check form-check-inline col-3 @error('writers') is-invalid @enderror">
                            <input class="form-check-input" type="checkbox" 
                            name="writers[]" id="iChbWriters{{$p->id}}" 
                            value="{{$p->id}}"
                            @checked(in_array($p->id, old('writers',$film->writers()->pluck('id')->toArray())))
                            >
                            <label class="form-check-label" for="iChbWriters{{$p->id}}">{{$p->full_name}}</label>
                        </div>
                        @endforeach 
                        @error('writers')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3 border border-start-0 border-top-0 border-end-0">
                    <label for="director" class="col-2 col-form-label text-end">{{ __('Stars') }}</label>
                    <div class="col-10">
                        @foreach($people as $p)
                        <div class="form-check form-check-inline col-3 @error('stars') is-invalid @enderror">
                            <input class="form-check-input" type="checkbox" 
                            name="stars[]" id="iChbStars{{$p->id}}" 
                            value="{{$p->id}}"
                            @checked(in_array($p->id, old('stars',$film->stars()->pluck('id')->toArray())))
                            >
                            <label class="form-check-label" for="iChbStars{{$p->id}}">{{$p->full_name}}</label>
                        </div>
                        @endforeach 
                        @error('stars')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3  border border-start-0 border-top-0 border-end-0">
                    <label for="genre" class="col-2 col-form-label text-end">{{ __('Genres') }}</label>
                    <div class="col-10">
                        @foreach($genres as $g)
                        <div class="form-check form-check-inline @error('genres') is-invalid @enderror">
                            <input class="form-check-input" type="checkbox" 
                            name="genres[]" id="iChbGenres{{$g->id}}" 
                            value="{{$g->id}}"
                            @checked(in_array($g->id, old('genres', $film->genres()->pluck('id')->toArray()))) 
                            >
                            <label class="form-check-label" for="iChbGenres{{$g->id}}">{{$g->name}}</label>
                        </div>
                        @endforeach
                        @error('genre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>


                <div class="row mb-3">
                    <label for="image" class="col-2 col-form-label text-end">{{ __('Image') }}</label>

                    <div class="col-4 text-center">
                        <img id="showImage" src="{{ $film->imgSrc }}" alt="{{ $film->name }}" class="mb-2" style="width: 100px;" />
                    </div>

                    <div class="col-6">
                        <div class="input-group col-6">
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                            id="image" name="image" value="{{ old('image', $film->image) }}" aria-describedby="btnRemoveImg">
                            <button class="btn btn-outline-secondary" type="button" id="btnRemoveImg">{{ __('Remove') }}</button>
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mb-0 float-end">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save') }}
                        </button>

                        <a class="btn btn-secondary" href="{{ route('film.index') }}">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#image').change(function(e){
			var reader = new FileReader();
			reader.onload = function(e){
				$('#showImage').attr('src',e.target.result);
			}
			reader.readAsDataURL(e.target.files['0']);
		});

        $('#btnRemoveImg').click(function(e){
			$('#image').val("");
            $('#showImage').attr('src', '/storage/film_images/no_image.png');
            $('#image_remove').val('yes')
		});
	});
</script>

@endsection