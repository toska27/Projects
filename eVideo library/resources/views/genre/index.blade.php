@extends('layouts.app')
@section('content')
<div class="row mb-2">
    <div class="col-12">
        <a href="{{ route('genre.create') }}" class="btn btn-primary float-end">{{ __('Add') }}</a>
    </div>
</div>
<div class="card">
    <div class="card-header">{{ __('Genres') }}</div>

    <div class="card-body">
    <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">{{ __('No.') }}</th>
            <th scope="col">{{ __('Name EN') }}</th>
            <th scope="col">{{ __('Name SR') }}</th>
            <th scope="col">#</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($data as $g)
            <tr>
                <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration  }}</td>
                <td>{{ $g->name_en }}</td>
                <td>{{ $g->name_sr }}</td>
                <td>
                    <form method="POST" action="{{ route('genre.destroy', ['genre'=>$g->id]) }}">
                        @method('delete') 
                        @csrf
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{ route('genre.edit', ['genre'=>$g->id]) }}" class="btn btn-success btn-sm">{{ __('Edit') }}</a>
                            <button type="sumbit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
        
        </tbody>
    </table>
    {{ $data->links() }}

    </div>
</div>
@endsection