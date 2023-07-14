@extends('layouts.app')

@section('content')
<div class="row mb-2">
	<div class="card">
		<div class="card-header">{{ __('Search') . ": " . __('Film') }}</div>
		<div class="card-body">
			<form method="POST" action="{{ route('film.index') }}">
				@csrf
				<div class="row">
					<div class="col-3 mb-3">
						<label for="rating" class="form-label">{{__('Rating')}}</label>
    					<select class="form-select @error('rating') is-invalid @enderror" name="rating">
							<option value="">--</option>
							<option value="10" @selected(old('rating', ($populateData['rating']??''))==10)>10</option>
							@for ($i = 9; $i >= 1; $i--)
								<option value="{{ $i }}" @selected(old('rating', ($populateData['rating']??''))==$i)>{{ $i }}+</option>
							@endfor						
						</select>
						@error('rating')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="col-3 mb-3">
						<label for="year_from" class="form-label">{{__('Year')}}</label>
						<div class="input-group @error('year_from') is-invalid @enderror @error('year_to') is-invalid @enderror">
							<input type="text" class="form-control" name="year_from" value="{{ old('year_from', ($populateData['year_from']??'')) }}">
							<input type="text" class="form-control" name="year_to" value="{{ old('year_to', ($populateData['year_to']??'')) }}">
						</div>

						@error('year_from')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
						@error('year_to')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="col-3 mb-3">
						<label for="time_from" class="form-label">{{__('Running time')}}</label>
						<div class="input-group @error('time_from') is-invalid @enderror @error('time_to') is-invalid @enderror">
							<input type="text" class="form-control" name="time_from" value="{{ old('time_from', ($populateData['time_from']??'')) }}">
							<input type="text" class="form-control" name="time_to" value="{{ old('time_to', ($populateData['time_to']??'')) }}">
						</div>

						@error('time_from')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
						@error('time_to')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="col-3 mb-3">
						<label for="name" class="form-label">{{__('Name')}}</label>
						<input type="text" class="form-control" name="name" value="{{ $populateData['name']??'' }}">							
					</div>
				</div>
				<div class="row">
					<div class="col-3 mb-3">
						<label for="genre" class="form-label">{{__('Genre')}}</label>
    					<select class="form-select" name="genre">
							<option value="">--</option>
							@foreach ($genres as $g)
								<option value="{{ $g->id }}" @selected(($populateData['genre']??'')==$g->id)>{{ $g->name }}</option>
							@endforeach						
						</select>
					</div>
					<div class="col-3 mb-3">
						<label for="star" class="form-label">{{__('Star')}}</label>
    					<select class="form-select" name="star">
							<option value="">--</option>
							@foreach ($people as $p)
								<option value="{{ $p->id }}" @selected(($populateData['star']??'')==$p->id)>{{ $p->full_name }}</option>
							@endforeach						
						</select>
					</div>
				</div>
					


				<div class="row mb-0 float-end">
					<div class="col-12">
						<button type="submit" class="btn btn-success">
							{{ __('Search') }}
						</button>

						<a class="btn btn-secondary" href="{{ route('film.index') }}">
							{{ __('Cancel') }}
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="col-12">
	<div class="row mb-2">
		<div class="col-12">
			<a type="button"
				class="btn btn-primary float-end"
				href="{{ route('film.create') }}"> {{ __('Add') }}
			</a>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered mb-0">
			<thead>
				<tr>
					<th>#</th>
					<th>{{ __('Image') }}</th>
					<th>{{ __('Name') }}</th>
					<th>{{ __('Running time') }}</th>
					<th>{{ __('Year') }}</th>
					<th>{{ __('Rating') }}</th>
					<th>{{ __('Genres') }}</th>
					<th>#</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($datas as $film)
				<tr>
					<th scope="row">{{ $loop->iteration }}</th>
					<td><img src="{{ $film->imgSrc }}" alt="{{ $film->name }}" class="mb-2" style="width: 100px;" /></td>
					<td><a href="{{route('film.show',$film)}}" >{{ $film->name }}</a>
					
						<p class="m-0"><strong>{{ __('Directors')}}:</strong>
						@foreach($film->directors as $p)
							{{  $p->full_name}}
						@endforeach	
						</p>

						<p class="m-0"><strong>{{ __('Writers')}}:</strong>	
						@foreach($film->writers as $p)
							{{  $p->full_name}}
						@endforeach		
						</p>

						<p class="m-0"><strong>{{ __('Stars')}}:</strong>	
						@foreach($film->stars as $p)
							{{  $p->full_name}}
						@endforeach			
						</p>
				
					</td>
					<td>{{ $film->running_time }}</td>
					<td>{{ $film->year }}</td>
					<td>{{ $film->rating }}</td>
					<td>
						@foreach($film->genres as $g)
							{{  $g->name}}
						@endforeach
					</td>
					<td>
					<div class="btn-group" role="group">
						<form method="post" action="{{route('film.destroy',$film)}}">
							@method('delete')
							@csrf
								<a href="{{route('film.edit',$film)}}" type="button" class="btn btn-info btn-sm">{{ __('Edit') }}</a>
								<button type="submit" class="btn btn-danger btn-sm delete-button">{{ __('Delete') }}</button>
						</form>
					</div>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection