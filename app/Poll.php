<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model {
    protected $table = 'polls';
    protected $fillable = [
        'title',
        'startDate',
        'endDate',
        'personAmount',
        'author',
        'hotel'
    ];
    
    public function author()
    {
        return $this->belongsTo('App\User');
    }
    public function hotels()
    {
        return $this->hasMany('App\Hotel');
    }
    
}