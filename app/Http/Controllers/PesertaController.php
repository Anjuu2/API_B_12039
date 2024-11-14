<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    public function index()
    {
        $peserta = Peserta::all();
        return response()->json($peserta);
    }

    public function show($id)
    {
        $peserta = Peserta::find($id);
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }
        return response()->json($peserta);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_event' => 'required|exists:events,id',
            'nama' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
        ]);

        $user = Auth::user();

        $peserta = Peserta::create([
            'id_user' => $user->id,
            'id_event' => $request->id_event,
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
        ]);

        return response()->json([
            'message' => 'Peserta berhasil ditambahkan',
            'post' => $peserta,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $peserta = Peserta::find($id);
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $peserta->update($request->all());

        return response()->json([
            'message' => 'Peserta berhasil diperbarui',
            'post' => $peserta,
        ]);
    }

    public function destroy($id)
    {
        $peserta = Peserta::find($id);
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $peserta->delete();

        return response()->json(['message' => 'Peserta berhasil dihapus']);
    }
}
