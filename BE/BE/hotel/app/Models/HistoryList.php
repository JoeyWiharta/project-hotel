<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryList extends Model
{
    use HasFactory;

    protected $table = 'history_lists';

    protected $fillable = [
        'booking_id', 'user_name', 'email', 'phone_number', 'roomnumber', 'type', 'checkin_date', 'checkout_date', 'total_price', 'days', 'price_per_night',
    ];

    public function booking()
    {
        return $this->belongsTo(BookingList::class, 'booking_id', 'id');
    }
}
