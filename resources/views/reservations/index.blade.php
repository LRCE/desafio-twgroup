@extends('layouts.admin', ['loadCalendar' => 1])

@section('main-content')
    <h1>Reservaciones</h1>
    <div class="row">
        <div class="col-12">
            @if (count($errors) || $errors->any())
                <div class="alert alert-danger">
                    <strong>¡Vaya!</strong> Hubo algunos problemas con los datos.<br><br>
                    <ul>
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @if (!\Auth::user()->is_admin)
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reservationModal">
                            Crear Nueva Reservación
                        </button>
                        <hr>
                    @endif

                    <form action="{{ route('reservations.index') }}" method="GET">
                        @csrf
                        <div class="form-group">
                            <label for="room_id">Sala</label>
                            <select name="room_id" id="room_id" class="form-control">
                                <option value="">Seleccione una sala</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" @if(old('room_id') == $room->id) selected @endif>{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reservation_start">Fecha de Inicio</label>
                            <input type="date" name="reservation_start" id="reservation_start" class="form-control" value="{{ old('reservation_start') }}">
                        </div>
                        <div class="form-group">
                            <label for="reservation_end">Fecha de Fin</label>
                            <input type="date" name="reservation_end" id="reservation_end" class="form-control" value="{{ old('reservation_end') }}">
                        </div>
                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Seleccione un estado</option>
                                <option value="Pendiente" @if(old('status') == 'Pendiente') selected @endif>Pendiente</option>
                                <option value="Aceptada" @if(old('status') == 'Aceptada') selected @endif>Aceptada</option>
                                <option value="Rechazado" @if(old('status') == 'Rechazada') selected @endif>Rechazado</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sala</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    @if(\Auth::user()->is_admin)
                                        <th>Reservado por</th>
                                    @endif
                                    <th style="width: 15%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $i=>$reservation)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $reservation->room->name }}</td>
                                        <td>{{ $reservation->reservation_start }} <br>-<br> {{ $reservation->reservation_end}}</td>
                                        <td>{{ $reservation->status }}</td>
                                        @if (\Auth::user()->is_admin)
                                            <td>
                                                {{ $reservation->user->full_name }}
                                            </td>
                                        @endif
                                        <td>
                                            @if (\Auth::user()->is_admin && $reservation->status == 'Pendiente')
                                                <form action="{{ route('reservations.approve', $reservation) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success">Aceptar</button>
                                                </form>
                                                <form action="{{ route('reservations.reject', $reservation) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-danger">Rechazar</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-warning"  type="submit">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $reservations->links() }}
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">Nueva Reservación</h5>
                    <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('reservations.store') }}" method="POST" class="needs-validation">
                        @csrf

                        <div class="mb-3">
                            <label for="room" class="form-label">Sala:</label>
                            <select type="text" name="room" id="room" class="form-control" required>
                                <option value="-1" selected disabled> Seleccione una sala </option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Por favor, proporcione una sala.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Fecha:</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                            <div class="invalid-feedback">
                                Por favor, proporcione una fecha.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Hora:</label>
                            <select name="time" id="time" class="form-control" required>
                                <option value="-1" disabled selected> Seleccione una hora</option>
                                @for( $i=0; $i <= 23; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                            </select>
                            <div class="invalid-feedback">
                                Por favor, proporcione una hora.
                            </div>
                        </div>
                        <!-- ...otros campos... -->
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            allDaySlot: false,
            expandRows: true,
            nowIndicator: true,
            startParam: 'reservation_start',
            endParam: 'reservation_end',
            slotDuration: '01:00:00',
            eventSources: [
                @if(old('status'))
                    {
                        url: '{{ route('reservations.calendarFeed') }}',
                        method: 'GET',
                        extraParams: {
                            room_id: document.getElementById('room_id').value,
                            reservation_start: document.getElementById('reservation_start').value,
                            reservation_end: document.getElementById('reservation_end').value,
                            user_id: '{{ \Auth::user()->id }}',
                            status: '{{ old('status') }}'
                        },
                        failure: function() {
                            alert('Hubo un error al cargar las reservaciones en el calendario');
                        }
                    }
                @else
                    {
                        url: '{{ route('reservations.calendarFeed') }}',
                        method: 'GET',
                        extraParams: {
                            room_id: document.getElementById('room_id').value,
                            reservation_start: document.getElementById('reservation_start').value,
                            reservation_end: document.getElementById('reservation_end').value,
                            user_id: '{{ \Auth::user()->id }}',
                            status: 'Pendiente'
                        },
                        failure: function() {
                            alert('Hubo un error al cargar las reservaciones en el calendario');
                        }
                    },
                    {
                        url: '{{ route('reservations.calendarFeed') }}',
                        method: 'GET',
                        extraParams: {
                            room_id: document.getElementById('room_id').value,
                            reservation_start: document.getElementById('reservation_start').value,
                            reservation_end: document.getElementById('reservation_end').value,
                            user_id: '{{ \Auth::user()->id }}',
                            status: 'Aceptada'
                        },
                        backgroundColor: 'green',
                        failure: function() {
                            alert('Hubo un error al cargar las reservaciones en el calendario');
                        }
                    }
                    @if (\Auth::user()->is_admin)
                    ,{
                        url: '{{ route('reservations.calendarFeed') }}',
                        method: 'GET',
                        extraParams: {
                            room_id: document.getElementById('room_id').value,
                            reservation_start: document.getElementById('reservation_start').value,
                            reservation_end: document.getElementById('reservation_end').value,
                            user_id: '{{ \Auth::user()->id }}',
                            status: 'Rechazada'
                        },
                        backgroundColor: 'red',
                        failure: function() {
                            alert('Hubo un error al cargar las reservaciones en el calendario');
                        }
                    }
                    @endif
                @endif
            ],
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'timeGridDay,timeGridWeek' // el usuario puede cambiar entre las dos vistas
            }
        })
        calendar.render();
      });
    </script>
@endsection
