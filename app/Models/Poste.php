<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    // public $timestamps = false;

    protected $table="postes";
    protected $fillable=[
        "title",
        "desc",
        "img",
        "user_id"
    ];
   public function user(){
    return $this->belongsTo(User::class);
   }
//    many to many likes 
public function userLiked(){
    return $this->belongsToMany(User::class,"likes","poste_id","user_id")->withTimestamps();
}
}
