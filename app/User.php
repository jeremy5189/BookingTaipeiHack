<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
    ];
    
    public function polls() {
        return $this->hasMany('App\Poll', 'author', 'id');
    }
   
}