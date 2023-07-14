@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row mb-2">
                <div class="col-12">
                    <a href="{{ route('person.create') }}" class="btn btn-primary float-end">{{ __('Add') }}</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">{{ __('People') }}</div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Surname') }}</th>
                                <th scope="col">{{ __('Birth date') }}</th>
                                <th scope="col">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $p)
                                <tr>
                                    <td>{{ $p->id }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->surname }}</td>
                                    <td>{{ $p->b_date }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('person.destroy', ['person'=>$p->id]) }}">
                                            @method('delete')
                                            @csrf
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('person.edit', ['person'=>$p->id]) }}" class="btn btn-success btn-sm">{{ __('Edit') }}</a>
                                                <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
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
        </div>
    </div>
</div>


@endsection