<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    // Mostrar una lista de habitaciones
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    // Mostrar el formulario para crear una nueva habitación
    public function create()
    {
        return view('rooms.create');
    }

    // Almacenar una nueva habitación en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')
                         ->with('success', 'Habitación creada con éxito.');
    }

    // Mostrar una habitación específica
    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    // Mostrar el formulario para editar una habitación existente
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    // Actualizar una habitación existente en la base de datos
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $room->update($request->all());

        return redirect()->route('rooms.index')
                         ->with('success', 'Habitación actualizada con éxito.');
    }

    // Eliminar una habitación de la base de datos
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')
                         ->with('success', 'Habitación eliminada con éxito.');
    }
}
