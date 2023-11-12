<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Agenda;
use App\Models\Room;

class AgendaController extends Controller
{
    public function list(Request $request) {
        $agendas = Agenda::query();

        if (isset($request->date)) {
            $agendas->whereDate('time', $request->date);
        }

        if (isset($request->room_id)) {
            $agendas->where('room_id', $request->roomId);
        }

        if (isset($request->is_booked)) {
            $agendas->where('is_booked', $request->is_booked);
        }

        $agendas = $agendas->orderBy('time', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => $agendas
        ]);
    }

    public function show($id) {
        $agenda = Agenda::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => $agenda
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'time' => 'required',
            'name' => 'required',
            'attendant_count' => 'required|numeric',
            'pic' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation fails.',
                'payload' => [
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        if (isset($request->room_id)) {
            // validate room id
            $room = Room::find($request->room_id);
            if (!isset($room)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found.',
                    'payload' => []
                ], 422);
            }
        } else {
            if (!isset($request->custom_room_name)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Isikan nama ruangan jika tidak memilih ruangan dalam agenda',
                    'payload' => []
                ], 422);
            }
        }

        $agenda = Agenda::create([
            'time' => $request->time,
            'name' => $request->name,
            'attendant_count' => $request->attendant_count,
            'pic' => $request->pic,
            'room_id' => $request->room_id,
            'custom_room_name' => $request->custom_room_name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => $agenda
        ]);
    }

    public function delete($id) {
        $agenda = Agenda::findOrFail($id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => []
        ]);
    }

    public function listRoom() {
        $rooms = Room::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => $rooms
        ]);
    }

    public function detailRoom($id) {
        $room = Room::with('agendas')->findOrFail($id);


        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => $room
        ]);
    }

    public function storeBooking($id, Request $request) {
        $room = Room::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'agenda_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation fails.',
                'payload' => [
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        $agenda = Agenda::findOrFail($id)->update([ 'is_booked' => true ]);

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'payload' => []
        ]);
    }
}
