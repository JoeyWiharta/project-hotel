<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingList extends Model
{
    use HasFactory;

    protected $table = 'bookinglist';  // Pastikan nama tabel sesuai

    protected $fillable = [
        'user_name', 'email', 'phone_number', 'roomnumber', 'type', 'checkin_date', 'checkout_date', 'status', 'total_price',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'roomnumber', 'roomnumber');
    }
}
