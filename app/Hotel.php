<?php namespace App;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model {

    protected $table = 'hotels';

    protected $fillable = [   
        "id",     
        'booking_id',
        'checkIn',
        'checkOut',
        'location',
        'note',
        'poll_id'
    ];

    public function poll()
    {
        return $this->belongsTo('App\Poll', 'poll_id');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote', 'hotel_id', 'id');
    }
}