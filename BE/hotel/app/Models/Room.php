<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'roomnumber';
    public $incrementing = false; // Primary key bukan auto-increment
    protected $keyType = 'string'; // Tipe data primary key adalah string

    protected $fillable = [
        'roomnumber',
        'type',
        'price_per_night',
        'description',
        'status',
        'adult_capacity',
        'child_capacity',
    ];

    // Method untuk menentukan kapasitas berdasarkan tipe room
    public static function getCapacityByType($type)
    {
        $capacities = [
            'Single Room' => ['adult_capacity' => 1, 'child_capacity' => 0],
            'Double Room' => ['adult_capacity' => 2, 'child_capacity' => 0],
            'Deluxe Room' => ['adult_capacity' => 2, 'child_capacity' => 1],
            'Standard Room' => ['adult_capacity' => 2, 'child_capacity' => 0],
        ];

        return $capacities[$type];
    }
}
