<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PosteController extends Controller
{
  public function index()
  {
    // try {
    Carbon::setLocale('fr');
    $postes = Poste::all();

    if (!$postes->isEmpty()) {
      $postes->transform(function ($poste) {
        $poste->duree = Carbon::parse($poste->created_at)->diffForHumans();
          $poste->likes = count($poste->userLiked()->get());
       return $poste;
      });
      return response()->json($postes);
    } else {
      return response()->json("Aucun poste trouvé !");
    }
  }
  public function  store(Request $request)
  {
    try {
      $validation = Validator::make($request->all(), [
        "title" => "required",
        "desc" => "required",
        "img" => "required",
        "user_id" => "required|exists:users,id"
      ]);
      if ($validation->fails()) {
        return response()->json($validation->errors());
      }
      $poste = Poste::create([
        "title" => $request->title,
        "desc" => $request->desc,
        "img" => $request->img,
        "user_id" => $request->user_id
      ]);
      if ($poste) {
        return response()->json('poste est créé avec sucsse !');
      } else {
        return response()->json("poste n'est pas créé");
      }
    } catch (\Throwable $th) {
      return response()->json($th->getMessage());
    }
  }

  //get les poste par son id 
  public function getPostByUserId(Request $request, $userid)
  {
    $postes = Poste::where('user_id', $userid)->get();
    // $user_name=User::with('post')->where('id',$userid)->get();
    // return response()->json($user_name);
    if (!$postes->isEmpty()) {
      return response()->json($postes);
    } else {
      return response()->json("Aucun post pour  cet utilisateur");
    }
  }
  // delete poste 
  public function destroy(Request $request, $poste)
  {
    try {
      $deletePost = Poste::destroy($poste);
      if ($deletePost) {
        return response()->json('poste est supprimé avec sucsse !');
      } else
        return response()->json("post n'est pas supprime !");
    } catch (\Throwable $th) {
      return response()->json($th->getMessage());
    }
  }
  // update poste
  public function update(Request $request)
  {
    $validationPoste = Validator::make($request->all(), [
      "title" => "required",
      "desc" => "required",
      "img" => $request->img,
      "user_id" => $request->id
    ]);
    if ($validationPoste->fails()) {
      return response()->json($validationPoste->errors());
    }
    $updatedPost = Poste::find($request->id);
    return response()->json($updatedPost);
  }
  // récupérer les poste d'aujaurdui 

  public function getTodayPostes()
  {
    try {
      $today = Carbon::now("Africa/Casablanca")->toDateString();
      $todayPost = Poste::whereDate('created_at', $today)->get();
      if ($todayPost->isNotEmpty()) {
        $todayPost->transform(function ($poste) {
          $poste->duration = Carbon::parse($poste->created_at)->diffForHumans();
          return $poste;
        });
        return response()->json($todayPost);
      } else {
        return response()->json("aucun postes pour aujourd'hui !");
      }
    } catch (\Throwable $th) {
      return response()->json($th->getMessage());
    }
  }
  // user va faire like a un post
  public function likePost(Request $request)
  {
    try {
      $user_id = $request->user_id;
      $post_id = $request->post_id;
      $user = User::findOrFail($user_id);
      $post = Poste::findOrFail($post_id);
      if ($user->postesLiked()->where('poste_id', $post_id)->exists()) {
        return response()->json("vous aves déja fait j'aime");
      }
      $user->postesLiked()->attach($post_id);
      return response()->json("vous aves fait j'aime");
    } catch (\Throwable $th) {
      return  response()->json($th->getMessage());
    }
  }
}
