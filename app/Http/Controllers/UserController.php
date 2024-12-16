<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::all();

        if ($users) {
            return response()->json($users);
        } else {
            return response()->json('Aucun utilisateur trouvé !');
        }
    }
    // récupérer un utilisateur avec son id 
    public function show($user)
    {
        $user_id = User::find($user);
        if (!$user_id) {
            return response()->json("Utilisateur non trouvé ");
        } else {
            return response()->json($user_id);
        }
    }
    // ajouter un utilisateur
    public function store(Request $request)
    {
        try {
            $validateUser = $request->validate([
                'email' => 'required|email',
                'name' => 'required',
                'password' => 'required|min:6'
            ]);
            $user = User::create([
                "email" => $request->email,
                "name" => $request->name,
                'password' => bcrypt($request->password)
            ]);
            if ($user) {
                $card = Card::create([
                    "user_id" => $user->id
                ]);
                if ($card) {
                    return response()->json("Utilisateur est  ajouté");
                }
            } else {
                return response()->json("Utilisateur n'est pas ajouté");
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function update(Request $request, $user)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => "email|required",
                "name" => "required",
                "password" => "required",
            ]);
            if ($validateUser->fails()) {
                return response()->json($validateUser->errors());
            };
            $id_user = User::find($user);
            $updated_user = $id_user->update([
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "name" => $request->name
            ]);
            if ($updated_user) {
                return  response()->json('Utilisateur modifié avec sucssé !');
            } else {
                return response()->json("Utilisateur n'est pas ajouté");
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    // supprimé l'utilisateur 
    public function destroy(Request $request, $user)
    {
        try {
            $deleted_user = User::destroy($user);
            if ($deleted_user) {
                return response()->json("l'utilisateur est supprimé avec sucssé ");
            } else {
                return response()->json("l'utilisateur n' est pas  supprimé avec sucssé ");
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    // search avec le nom  d'utilisateur
    public function getNom(Request $request)
    {
        try {
            $user = User::where('name', $request->name);
            if ($user) {
                return response()->json($user);
            } else {
                return response()->json('accaun utilisateur introvable !');
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    // authentification 
    public function login(Request $request)
    {
        try {
            $formField = $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);
            if (Auth::attempt($formField)) {
                return response()->json('Utilisateur est authentifié avec sucsse !');
            } else {
                return response()->json('email ou password incorrect');
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
