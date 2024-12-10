<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingList;
use App\Models\Room;
use App\Http\Requests\StoreBookingListRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingListController extends Controller
{
    public function store(StoreBookingListRequest $request)
    {
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

        $room->update(['status' => 'Unavailable']);

        return response()->json([
            'success' => true,
            'message' => 'Room booked successfully',
            'data' => $booking,
        ]);
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
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Update status room
        $room = Room::where('roomnumber', $bookingList->roomnumber)->first();
        $room->update(['status' => 'Available']);

        // Hapus booking
        $bookingList->delete();

        return response()->json(['success' => true, 'message' => 'Booking checked out successfully']);
    }
}
