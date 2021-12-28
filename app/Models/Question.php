<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'status',
        'spam',
        'latest_reply_date',
        'customer_id',
    ];



    public function room()
    {
        return $this->hasOne(Room::class);
    }


    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    


}
