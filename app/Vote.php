<?php namespace App;
use Illuminate\Database\Eloquent\Model;
class Vote extends Model {
    protected $table = 'votes';
    
    protected $fillable = [
        'reaction',
        'author',
    ];
    
    public function author()
    {
        return $this->belongsTo('App\User');
    }
   
}