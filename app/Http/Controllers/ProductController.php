<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function store(Request $request)
  {

    $product = Product::create([
      "title" => $request->title,
      "price" => $request->price,
    ]);
    if ($product) {
      return response()->json("product est créé avec sucsse");
    }
    return response()->json("product n'est pas créé avec sucsse");
  }

  public function addToCard(Request $request)
  {
    try {
      $card_id = $request->cardId;
      $product_id = $request->productId;
      $card = Card::findOrFail($card_id);
      $product = Product::findOrFail($product_id);
      $qte = 1;
      if ($card->productAdd()->where('product_id', $product_id)->exists()) {
        $existingProduct=$product->cardfill()->first();
        $pivotProduct = $product->cardfill()->first()->pivot;
        
        $qte=$pivotProduct->qte+1;
        $card->productAdd()->updateExistingPivot($product_id,['qte'=>$qte]);
        // $card->update
        $total=$card->sum(function($item){
          return $item->price =$item->pivot->qte;
        });
        // return response()->json("product is updated succssffully !");
      } else {
        $card->productAdd()->attach($product_id, ['qte' => $qte]);
        return response()->json("product is created succeffully !");
      }
    } catch (\Throwable $th) {
      return response()->json($th->getMessage());
    }
  }
  public function getAddtocard(){
    return view('addtocard');
  }

}
