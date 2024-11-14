<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'nama_event' => 'required',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'lokasi' => 'required',
        ]);

        $user = Auth::user();

        $event = Event::create([
            'nama_event' => $request->nama_event,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi' => $request->lokasi,
            'id_user' => $user->id,
        ]);

        return response()->json([
            'message' => 'Event created successfully.',
            'event' => $event,
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $validator = $request->validate([
            'nama_event' => 'required',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'lokasi' => 'required',
        ]);

        $user = Auth::user();
        $event = Event::find($id);

        if (!$event || $event->id_user !== $user->id) {
            return response()->json(['message' => 'Event tidak ditemukan atau tidak log-in sebagai user'], 403);
        }

        $event->update($request->all());

        return response()->json($event);
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        $event = Event::find($id);

        if (!$event || $event->id_user !== $user->id) {
            return response()->json(['message' => 'Event tidak ditemukan atau tidak log-in sebagai user'], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Event berhasil dihapus.']);
    }
}
