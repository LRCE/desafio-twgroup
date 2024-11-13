<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use \Carbon\Carbon;

class ReservationController extends Controller
{
    // Mostrar una lista de reservaciones
    public function index(Request $request)
    {
        $request->flash();
        $is_admin = auth()->user()->is_admin;
        $is_client = !$is_admin;
        \Log::info(Carbon::parse($request->reservation_start)->endOfDay()->toDateTimeString());
        $reservations = Reservation::
        when($request->room_id, function($query) use($request) {
            return $query->where('room_id', $request->room_id);
        })
        ->when($is_client, function($query) {
            return $query->where('user_id', auth()->id());
        })
        ->when($request->status, function($query) use($request) {
            return $query->where('status', $request->status);
        })
        ->when($request->reservation_start, function($query) use($request) {
            return $query->where('reservation_start', '>=', Carbon::parse($request->reservation_start)->toDateTimeString());
        })
        ->when($request->reservation_end, function($query) use($request) {
            return $query->where('reservation_start', '<=', Carbon::parse($request->reservation_end)->endOfDay()->toDateTimeString());
        })
        ->when($request->status, function($query) use($request) {
            return $query->where('status', $request->status);
        })
        ->when(!$request->reservation_start && !$request->reservation_end, function($query) {
            return $query->where('reservation_start', '>=', Carbon::now());
        })
        ->orderBy('reservation_start', 'asc')
        ->paginate(10);
        $rooms = Room::all();
        return view('reservations.index', compact('reservations', 'rooms'));
    }

    // Mostrar el formulario para crear una nueva reservación
    public function create()
    {
        return view('reservations.create');
    }

    // Almacenar una nueva reservación
    public function store(Request $request)
    {
        $request->validate([
            'room' => 'required',
            'date' => 'required|date',
            'time' => 'required|integer',
            // ...otros campos de validación...
        ]);

        $room = $request->room;
        $reservation_start = Carbon::parse($request->date)->addHours($request->time);
        $reservation_end = $reservation_start->copy()->addHours(1);

        $existing_reservation = Reservation::where('room_id', $room)
            ->where(function($query) use ($reservation_start, $reservation_end) {
                $query->where('reservation_start', '<', $reservation_end)
                    ->where('reservation_start', '>=', $reservation_start);
            })
            ->where(function($query) use ($reservation_start, $reservation_end) {
                $query->where('reservation_end', '<=', $reservation_end)
                    ->where('reservation_end', '>', $reservation_start);
            })
            ->whereNot('status', 'Rechazada')
            ->exists();
        if ($existing_reservation) {
            return redirect()->route('reservations.index')->with('errors', ['La sala ya está reservada en ese horario.']);
        }

        Reservation::create([
            "room_id" => $room,
            "reservation_start" => $reservation_start,
            "reservation_end" => $reservation_end,
            "user_id" => auth()->id(),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservación creada exitosamente.');
    }

    // Mostrar una reservación específica
    public function show(Reservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }

    // Mostrar el formulario para editar una reservación
    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    // Actualizar una reservación específica
    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required|date',
            // ...otros campos de validación...
        ]);

        $reservation->update($request->all());
        return redirect()->route('reservations.index')->with('success', 'Reservación actualizada exitosamente.');
    }

    // Eliminar una reservación específica
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservación eliminada exitosamente.');
    }

    // Aprobar una reservación específica
    public function approve(Reservation $reservation)
    {
        \Log::info('Aprobando reservación: ' . $reservation->id);
        $reservation->update(['status' => 'Aceptada']);
        $reservation->save();
        \Log::info(print_r($reservation, true));
        return redirect()->route('reservations.index')->with('success', 'Reservación Aceptada exitosamente.');
    }

    // Rechazar una reservación específica
    public function reject(Reservation $reservation)
    {
        $reservation->update(['status' => 'Rechazada']);
        $reservation->save();
        return redirect()->route('reservations.index')->with('success', 'Reservación rechazada exitosamente.');
    }

    // Obtener todas las reservaciones con sus relaciones en formato de array
    public function getAllReservations(Request $request)
    {
        $query = Reservation::with(['room', 'user'])
        ->when($request->room_id, function($query) use($request) {
            return $query->where('room_id', $request->room_id);
        })
        ->when($request->status, function($query) use($request) {
            return $query->where('status', $request->status);
        })
        ->when($request->reservation_start, function($query) use($request) {
            return $query->where('reservation_start', '>=', Carbon::parse($request->reservation_start)->toDateTimeString());
        })
        ->when($request->reservation_end, function($query) use($request) {
            return $query->where('reservation_start', '<=', Carbon::parse($request->reservation_end)->endOfDay()->toDateTimeString());
        });
        $reservations = $query->get();
        foreach($reservations as $reservation) {
            $reservation->title = $reservation->room->name;
            $reservation->start = $reservation->reservation_start;
            $reservation->end = $reservation->reservation_end;
        }
        return response()->json($reservations);
    }
}
