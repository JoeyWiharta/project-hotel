<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryList;
use App\Models\BookingList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HistoryListController extends Controller
{
    public function storeFromBooking(BookingList $bookingList)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            Log::info('Creating history entry for booking with id:', ['id' => $bookingList->id]);

            if ($bookingList->email !== $user->email && $user->role !== 'admin') {
                Log::info('Unauthorized access attempt for booking with id:', ['id' => $bookingList->id]);
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $days = (new \DateTime($bookingList->checkout_date))->diff(new \DateTime($bookingList->checkin_date))->days;
            $price_per_night = $bookingList->total_price / $days;

            // Gunakan Query Builder untuk Insert Data
            DB::table('history_lists')->insert([
                'booking_id' => $bookingList->id,
                'user_name' => $bookingList->user_name,
                'email' => $bookingList->email,
                'phone_number' => $bookingList->phone_number,
                'roomnumber' => $bookingList->roomnumber,
                'type' => $bookingList->type,
                'checkin_date' => $bookingList->checkin_date,
                'checkout_date' => $bookingList->checkout_date,
                'total_price' => $bookingList->total_price,
                'days' => $days,
                'price_per_night' => $price_per_night,
                'order_status' => 'Booked', // Set order_status to 'Booked'
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            // Verifikasi data tersimpan
            $confirmedHistory = DB::table('history_lists')->where('booking_id', $bookingList->id)->first();
            Log::info('Confirmed history entry in database:', ['confirmedHistory' => $confirmedHistory]);

            return response()->json(['success' => true, 'message' => 'History entry created successfully', 'data' => $confirmedHistory]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating history entry for booking with id:', ['id' => $bookingList->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to create history entry', 'error' => $e->getMessage()], 500);
        }
    }

    // Method untuk admin melihat semua history
    public function adminIndex(Request $request)
    {
        Log::info('Fetching all history entries for admin');

        // Tampilkan semua history list untuk admin
        $historyList = HistoryList::all();

        Log::info('History entries found:', ['historyList' => $historyList]);

        return response()->json(['data' => $historyList]);
    }

    // Method untuk user melihat history mereka sendiri
    public function userIndex(Request $request)
    {
        Log::info('Fetching history entries for user:', ['email' => $request->user()->email]);

        // Tampilkan history list milik user sendiri
        $historyList = HistoryList::where('email', $request->user()->email)->get();

        Log::info('History entries found:', ['historyList' => $historyList]);

        return response()->json(['data' => $historyList]);
    }

    // Method untuk user melihat detail booking berdasarkan ID
    public function show($id)
    {
        $user = Auth::user();
        Log::info('Fetching booking detail for user:', ['email' => $user->email, 'id' => $id]);

        // Cari history list berdasarkan ID dan email user
        $history = HistoryList::where('id', $id)->where('email', $user->email)->first();

        if (!$history) {
            Log::info('No booking found for user with id:', ['id' => $id, 'email' => $user->email]);
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        Log::info('Booking details found:', ['history' => $history]);

        return response()->json(['data' => $history]);
    }

    // Metode baru untuk searching dengan pagination 
    public function search(Request $request) 
    { 
        $request->validate([ 
            'cari' => 'nullable|string', 
            'start' => 'required|integer', 
            'limit' => 'required|integer|min:1', 
        ]); 
        
        $cari = $request->cari; 
        $start = $request->start; 
        $limit = $request->limit; 
        
        // Hitung total hasil 
        $count = DB::table('history_lists') 
                    ->where('user_name', 'like', "%".$cari."%") 
                    ->orWhere('email', 'like', "%".$cari."%") 
                    ->orWhere('phone_number', 'like', "%".$cari."%") 
                    ->orWhere('roomnumber', 'like', "%".$cari."%") 
                    ->orWhere('type', 'like', "%".$cari."%") 
                    ->count(); 
        
        // Ambil hasil dengan pagination 
        $historyList = DB::table('history_lists') 
                        ->where('user_name', 'like', "%".$cari."%") 
                        ->orWhere('email', 'like', "%".$cari."%") 
                        ->orWhere('phone_number', 'like', "%".$cari."%") 
                        ->orWhere('roomnumber', 'like', "%".$cari."%") 
                        ->orWhere('type', 'like', "%".$cari."%") 
                        ->offset($start) ->limit($limit) 
                        ->orderBy('checkin_date') 
                        ->get(); 
                        
        $obj = new \stdClass(); 
        $obj->count = $count; 
        $obj->historyList = $historyList; 
        
        return response()->json($obj, 200); 
    
    }

}