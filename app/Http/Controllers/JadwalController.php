<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = Jadwal::all();
        return response()->json($jadwal);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_event' => 'required|exists:events,id',
            'judul_sesi' => 'required|string|in:konser,doorprize,Meet and Greet',
            'waktu_mulai' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwal = Jadwal::create([
            'id_event' => $request->id_event,
            'judul_sesi' => $request->judul_sesi,
            'deskripsi_sesi' => $this->generateDeskripsi($request->judul_sesi),
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => date('Y-m-d H:i:s', strtotime($request->waktu_mulai . ' +1 day')),
        ]);

        return response()->json(['message' => 'Jadwal berhasil dibuat', 'data' => $jadwal], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_event' => 'required|exists:events,id',
            'judul_sesi' => 'required|string|in:konser,doorprize,Meet and Greet',
            'waktu_mulai' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        if ($request->has('judul_sesi')) {
            $jadwal->judul_sesi = $request->judul_sesi;
            $jadwal->deskripsi_sesi = $this->generateDeskripsi($request->judul_sesi); 
        }
    
        if ($request->has('waktu_mulai')) {
            $jadwal->waktu_mulai = $request->waktu_mulai;
            $jadwal->waktu_selesai = date('Y-m-d H:i:s', strtotime($request->waktu_mulai . ' +1 day'));
        }
    
        if ($request->has('id_event')) {
            $jadwal->id_event = $request->id_event;
        }
    
        $jadwal->save();

        return response()->json([
            'message' => 'Jadwal berhasil diupdate',
            'data' => $jadwal
        ], 200);
    }


    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $jadwal->delete();
        return response()->json(['message' => 'Jadwal berhasil dihapus'], 200);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_sesi' => 'nullable|in:konser,doorprize,Meet and Greet',
            'id_event' => 'nullable|exists:events,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        
        $query = Jadwal::query();

        if ($request->has('judul_sesi')) {
            $query->where('judul_sesi', $request->judul_sesi);
        }

        if ($request->has('id_event')) {
            $query->where('id_event', $request->id_event);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('waktu_mulai', [$request->start_date, $request->end_date]);
        }

        $jadwal = $query->with('event')->get();

        if ($jadwal->isEmpty()) {
            return response()->json(['message' => 'Jadwal tidak ditemukan ditemukan.'], 404);
        }

        return response()->json(['data' => $jadwal], 200);
    }

    private function generateDeskripsi($judul_sesi)
    {
        switch ($judul_sesi) {
            case 'konser':
                return 'Penampilan music dari suatu band.';
            case 'doorprize':
                return 'Pembagian hadiah dari panitia ke peserta.';
            case 'Meet and Greet':
                return 'Sesi pertemuan untuk artis dengan penggemar.';
            default:
                return null;
        }
    }
}
