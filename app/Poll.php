<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model 
{
    protected $table = 'polls';

    protected $fillable = [
        'id',
        'title',
        'startDate',
        'endDate',
        'personAmount',
        'author'
    ];
    
    public function author()
    {
        return $this->belongsTo('App\User', 'author');
    }

    public function hotels()
    {
        return $this->hasMany('App\Hotel', 'hotel', 'id');
    }
    
}