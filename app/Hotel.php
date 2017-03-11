<?php namespace App;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model {

    protected $table = 'hotels';

    protected $fillable = [
        'id',
        'checkIn',
        'checkOut',
        'location',
        'note',
        'vote'
    ];

    public function poll()
    {
        return $this->belongsTo('App\Poll');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote', 'hotel_id', 'id');
    }
}