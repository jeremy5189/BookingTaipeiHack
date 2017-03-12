<?php namespace App;
use Illuminate\Database\Eloquent\Model;
class Vote extends Model {
    protected $table = 'votes';
    
    protected $fillable = [
        'name',
        'hotel_id',
        'reaction',
        'note',
        'author',
    ];
    
    public function author()
    {
        return $this->belongsTo('App\User');
    }
   
}