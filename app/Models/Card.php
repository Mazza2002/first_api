<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable=[
        "user_id"
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function productAdd(){
       return $this->belongsToMany(Product::class,'cardItems',"card_id","product_id")->withPivot("qte")->withTimestamps();
    }
}


