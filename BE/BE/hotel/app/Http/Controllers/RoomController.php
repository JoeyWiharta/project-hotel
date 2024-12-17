<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Http\Controllers\MyUtil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Tambahkan parameter $request
    {
        $status = $request->query('status');
        $type = $request->query('type');

        $query = Room::query();


        if($status) {
            $query->where('status', $status);
        } 

        if($type) {
            $query->where('type', $type);
        }

        $rooms = $query->get();

        return MyUtil::sendResponse($rooms, 'OK');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roomnumber' => 'required|string|max:255|unique:rooms,roomnumber',
            'type' => 'required|string|in:Single Room,Double Room,Deluxe Room,Standard Room',
            'price_per_night' => 'required|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:Available,Unavailable',
        ]);

        if ($validator->fails()) {
            return MyUtil::sendError('Validation Error.', $validator->errors());
        }

        $capacities = Room::getCapacityByType($request->type);

        $room = new Room();
        $room->roomnumber = $request->roomnumber;
        $room->type = $request->type;
        $room->price_per_night = $request->price_per_night;
        $room->description = $request->description;
        $room->status = $request->status;
        $room->adult_capacity = $capacities['adult_capacity'];
        $room->child_capacity = $capacities['child_capacity'];
        $room->save();

        return $this->index(new Request());
    }

    /**
     * Display the specified resource.
     */
    public function show($roomnumber)
    {
        try {
            return MyUtil::sendResponse(Room::findOrFail($roomnumber), 'OK');
        } catch (ModelNotFoundException $ex) {
            return MyUtil::sendError('NOT FOUND', 'NOT FOUND');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $roomnumber)
    {
        $validator = Validator::make($request->all(), [
            'roomnumber' => 'required|string|max:255',
            'type' => 'required|string|in:Single Room,Double Room,Deluxe Room,Standard Room',
            'price_per_night' => 'required|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:Available,Unavailable',
        ]);

        if ($validator->fails()) {
            return MyUtil::sendError('Validation Error.', $validator->errors());
        }

        $capacities = Room::getCapacityByType($request->type);

        if ($request->adult_capacity > $capacities['adult_capacity']) {
            return MyUtil::sendError('Validation Error.', ['adult_capacity' => 'Adult capacity exceeds the limit for this room type.']);
        }

        if ($request->child_capacity > $capacities['child_capacity']) {
            return MyUtil::sendError('Validation Error.', ['child_capacity' => 'Child capacity exceeds the limit for this room type.']);
        }

        try {
            $room = Room::where('roomnumber', $roomnumber)->firstOrFail();
            $room->type = $request->type;
            $room->price_per_night = $request->price_per_night;
            $room->description = $request->description;
            $room->status = $request->status;
            $room->adult_capacity = $capacities['adult_capacity'];
            $room->child_capacity = $capacities['child_capacity'];
            $room->save();

            return $this->index(new Request());
        } catch (ModelNotFoundException $ex) {
            return MyUtil::sendError('NOT FOUND', 'NOT FOUND');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($roomnumber)
    {
        Room::where('roomnumber', $roomnumber)->delete();
        return $this->index(new Request());
    }
}
