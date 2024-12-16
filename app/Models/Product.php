<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // $table->id();
    // $table->string("title");
    // $table->foreignId("card_id")->constrained("cards")->onDelete("cascade");
    // $table->integer("price");
    // $table->timestamps();
    protected $fillable=[
        "title",
        "card_id",
        "price"  
    ];
    public function cardfill(){
       return $this->belongsToMany(Card::class,'cardItems',"product_id","card_id")->withPivot('qte')->withTimestamps();
    }
}
