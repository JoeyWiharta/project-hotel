<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingList;
use App\Models\HistoryList;
use App\Models\Room;
use App\Http\Requests\StoreBookingListRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingListController extends Controller
{
    public function store(StoreBookingListRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            Log::info('Storing booking with data:', $request->all());
            Log::info('Authenticated User:', ['user' => $user]);

            $validated = $request->validated();

            $room = Room::where('roomnumber', $validated['roomnumber'])->first();

            if ($room->status == 'Unavailable') {
                return response()->json(['success' => false, 'message' => 'Room is not available for booking'], 400);
            }

            $days = (new \DateTime($validated['checkout_date']))->diff(new \DateTime($validated['checkin_date']))->days;
            $total_price = $days * $room->price_per_night;

            $booking = BookingList::create([
                'user_name' => $user->name,
                'email' => $user->email,
                'phone_number' => $validated['phone_number'],
                'roomnumber' => $validated['roomnumber'],
                'type' => $room->type,
                'checkin_date' => $validated['checkin_date'],
                'checkout_date' => $validated['checkout_date'],
                'total_price' => $total_price,
                'days' => $days, // Menambahkan jumlah hari
                'price_per_night' => $room->price_per_night, // Menambahkan harga per malam
            ]);

            // Tambahkan data ke tabel history_lists dengan order_status = 'Booked'
            HistoryList::create([
                'booking_id' => $booking->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'phone_number' => $validated['phone_number'],
                'roomnumber' => $validated['roomnumber'],
                'type' => $room->type,
                'checkin_date' => $validated['checkin_date'],
                'checkout_date' => $validated['checkout_date'],
                'total_price' => $total_price,
                'days' => $days,
                'price_per_night' => $room->price_per_night,
                'order_status' => 'Booked', // Set order_status to 'Booked'
            ]);

            $room->update(['status' => 'Unavailable']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Room booked successfully',
                'data' => $booking,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing booking and history entry:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to book room and store history entry'], 500);
        }
    }

    public function storeByRoomNumber(Request $request, $roomnumber)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            Log::info('Storing booking with roomnumber:', ['roomnumber' => $roomnumber]);
            Log::info('Authenticated User:', ['user' => $user]);

            $validated = $request->validate([
                'phone_number' => 'required|string',
                'checkin_date' => 'required|date',
                'checkout_date' => 'required|date|after:checkin_date',
            ]);

            $room = Room::where('roomnumber', $roomnumber)->first();

            if (!$room) {
                return response()->json(['success' => false, 'message' => 'Room does not exist'], 404);
            }

            if ($room->status == 'Unavailable') {
                return response()->json(['success' => false, 'message' => 'Room is not available for booking'], 400);
            }

            $days = (new \DateTime($validated['checkout_date']))->diff(new \DateTime($validated['checkin_date']))->days;
            $total_price = $days * $room->price_per_night;

            $booking = BookingList::create([
                'user_name' => $user->name,
                'email' => $user->email,
                'phone_number' => $validated['phone_number'],
                'roomnumber' => $roomnumber,
                'type' => $room->type,
                'checkin_date' => $validated['checkin_date'],
                'checkout_date' => $validated['checkout_date'],
                'total_price' => $total_price,
                'days' => $days,
                'price_per_night' => $room->price_per_night,
            ]);

            // Tambahkan data ke tabel history_lists dengan order_status = 'Booked'
            HistoryList::create([
                'booking_id' => $booking->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'phone_number' => $validated['phone_number'],
                'roomnumber' => $roomnumber,
                'type' => $room->type,
                'checkin_date' => $validated['checkin_date'],
                'checkout_date' => $validated['checkout_date'],
                'total_price' => $total_price,
                'days' => $days,
                'price_per_night' => $room->price_per_night,
                'order_status' => 'Booked', // Set order_status to 'Booked'
            ]);

            $room->update(['status' => 'Unavailable']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Room booked successfully',
                'data' => $booking,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing booking and history entry:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to book room and store history entry', 'error' => $e->getMessage()], 500);
        }
    }

    public function adminIndex(Request $request)
    {
        Log::info('Fetching all bookings for admin');
        // Tampilkan semua booking untuk admin
        $bookings = BookingList::with('room')->get();

        Log::info('Bookings found:', ['bookings' => $bookings]);

        return response()->json(['data' => $bookings]);
    }

    public function userIndex(Request $request)
    {
        Log::info('Fetching bookings for user:', ['email' => $request->user()->email]);
        // Tampilkan booking milik user sendiri
        $bookings = BookingList::with('room')->where('email', $request->user()->email)->get();

        Log::info('Bookings found:', ['bookings' => $bookings]);

        return response()->json(['data' => $bookings]);
    }

    public function show(BookingList $bookingList)
    {
        Log::info('Fetching booking with id:', ['id' => $bookingList->id]);
        // Periksa apakah user memiliki akses ke booking ini
        if ($bookingList->email !== request()->user()->email && request()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        Log::info('Booking details:', ['booking' => $bookingList]);

        return response()->json(['data' => $bookingList]);
    }

    public function destroy(BookingList $bookingList)
    {
        Log::info('Deleting booking with id:', ['id' => $bookingList->id]);
        // Periksa apakah user memiliki akses ke booking ini
        if ($bookingList->email !== request()->user()->email && request()->user()->role !== 'admin') {
            Log::info('Unauthorized access attempt for booking with id:', ['id' => $bookingList->id]);
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Update status room
        $room = Room::where('roomnumber', $bookingList->roomnumber)->first();
        $room->update(['status' => 'Available']);

        // Perbarui order_status di history_lists menjadi 'Checkedout'
        HistoryList::where('booking_id', $bookingList->id)->update(['order_status' => 'Checked Out']);

        // Hapus booking
        $bookingList->delete();

        Log::info('Booking deleted successfully with id:', ['id' => $bookingList->id]);

        return response()->json(['success' => true, 'message' => 'Booking checked out successfully']);
    }

    public function search (Request $request)
    {
        $request->validate([
            'cari' => 'nullable|string',
            'start' => 'required|integer',
            'limit' => 'required|integer|min:1',
        ]);

        $cari = $request->cari;
        $start = $request->start;
        $limit = $request->limit;

        // Hitung total Hasil
        $count = DB::table('bookinglist')
                    ->where('user_name', 'like', "%".$cari."%") 
                    ->orWhere('email', 'like', "%".$cari."%") 
                    ->orWhere('phone_number', 'like', "%".$cari."%") 
                    ->orWhere('roomnumber', 'like', "%".$cari."%") 
                    ->orWhere('type', 'like', "%".$cari."%") 
                    ->count();

        $bookings = DB::table('bookinglist') 
                    ->where('user_name', 'like', "%".$cari."%") 
                    ->orWhere('email', 'like', "%".$cari."%") 
                    ->orWhere('phone_number', 'like', "%".$cari."%") 
                    ->orWhere('roomnumber', 'like', "%".$cari."%") 
                    ->orWhere('type', 'like', "%".$cari."%") 
                    ->offset($start) ->limit($limit) 
                    ->orderBy('user_name') 
                    ->get();

        $obj = new \stdClass();
        $obj->count = $count;
        $obj->bookings = $bookings;

        return response()->json($obj, 200);
    }

}