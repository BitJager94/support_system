<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'customer_id',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'room_id');
    }


    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
