@extends('layouts.admin')

@section('main-content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="h2 mb-4 text-gray-800">Salas</h2>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('rooms.create') }}"> Crear Nueva Sala</a>
            </div>
        </div>
        <div class="card-body border-bottom-none">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th width="15px">Acción</th>
                    </tr>
                    @foreach ($rooms as $i=>$room)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->description }}</td>
                        <td style="width: 15%">
                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST">
                                <a class="btn btn-primary" href="{{ route('rooms.edit', $room->id) }}">Editar</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
<div class="container mt-3 bg-white">
    <div class="row">
    </div>
</div>
@endsection
