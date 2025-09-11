<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Config\Sucursale;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get("search");
        $users = User::where("name", "ilike", "%" . $search . "%")->orderBy("id", "desc")->get();

        return response()->json([
            "users" => $users->map(function ($user) {
                return [
                    "id" => $user->id,
                    "name" => $user->name,
                    "surname" => $user->surname,
                    "full_name" => $user->name . ' ' . $user->surname,
                    "email" => $user->email,
                    "role_id" => $user->role_id,
                    "role" => $user->role->name,                    
                    "phone" => $user->phone,
                    "state" => $user->state,
                    "sucursale_id" => $user->sucursale_id,
                    "sucursale" => $user->sucursale->name,
                    "avatar" => $user->avatar ? env("APP_URL")."storage/".$user->avatar : NULL,
                    "type_document" => $user->type_document,
                    "n_document" => $user->n_document,
                    "gender" => $user->gender,
                    "created_at" => $user->created_at->format("Y-m-d h:i A"),
                ];
            }),
        ]);
    }

    public function config(){
        $sucursales = Sucursale::all();
        $roles = Role::all();

        return response()->json([
            "sucursales" => $sucursales->map(function($sucursal){
                return [
                    "id" => $sucursal->id,
                    "name" => $sucursal->name,
                ];
            }),
            "roles" => $roles->map(function($rol){
                return [
                    "id" => $rol->id,
                    "name" => $rol->name,
                ];
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_user_exist = User::where("email",$request->email)->first();
        if($is_user_exist){
            return response()->json([
                "message" => 403,
                "messge_text" => "EL USUARIO YA EXISTE"
            ]);
        }

        if($request->hasFile("imagen")){
            $path = Storage::putFile("users",$request->file("imagen"));
            $request->request->add(["avatar" => $path]);
        }

        if($request->password){
            $request->request->add(["password" => bcrypt($request->password)]);
        }

        $user = User::create($request->all());
        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role);

        return response()->json([
            "message" => 200,
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "surname" => $user->surname,
                "full_name" => $user->name . ' ' . $user->surname,
                "email" => $user->email,
                "role_id" => $user->role_id,
                "state" => $user->state,
                "role" => [
                    "name" => $user->role->name,
                ],
                "phone" => $user->Phone,
                "sucursale_id" => $user->sucursale_id,
                "sucursale" => [
                    "name" => $user->sucursale->name,
                ],
                "avatar" => $user->avatar ? env("APP_URL")."storage/".$user->avatar : NULL,
                "type_document" => $user->type_document,
                "n_document" => $user->n_document,
                "gender" => $user->gender,
                "created_at" => $user->created_at->format("Y-m-d h:i A"),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $is_user_exist = User::where("email",$request->email)->where("id","<>",$id)->first();
        if($is_user_exist){
            return response()->json([
                "message" => 403,
                "messge_text" => "EL USUSARIO YA EXISTE"
            ]);
        }

        $user = User::findOrFail($id);

        if($request->hasFile("imagen")){
            if($user->avatar){
                Storage::delete($user->avatar);
            }
            $path = Storage::putFile("users",$request->file("imagen"));
            $request->request->add(["avatar" => $path]);
        }

        if($request->password){
            $request->request->add(["password" => bcrypt($request->password)]);
        }

        $user->update($request->all());

        if($request->role_id != $user->role_id){
            $role_old = Role::findOrFail($user->role_id);
            $user->removeRole($role_old);
            $role_new = Role::findOrFail($request->role_id);
            $user->assignRole($role_new);
        }

        return response()->json([
            "message" => 200,
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "surname" => $user->surname,
                "full_name" => $user->name . ' ' . $user->surname,
                "email" => $user->email,
                "role_id" => $user->role_id,
                "role" => [
                    "name" => $user->role->name,
                ],
                "phone" => $user->Phone,
                "state" => $user->state,
                "sucursale_id" => $user->sucursale_id,
                "sucursale" => [
                    "name" => $user->sucursale->name,
                ],
                "avatar" => $user->avatar ? env("APP_URL")."storage/".$user->avatar : NULL,
                "type_document" => $user->type_document,
                "n_document" => $user->n_document,
                "gender" => $user->gender,
                "created_at" => $user->created_at->format("Y-m-d h:i A"),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            "message" => 200
        ]);
    }
}
