<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleModule;

class UserModuleController extends Controller
{
    public function getModules($id){
        // Aqui recibimos el id del rol y devolvemos los ids de los modulos asociados
        $modules = RoleModule::where("role_id", $id)->with("modules")->get();
        return response()->json([
            'status' => true,
            'modules' => $modules
        ]);
    }
}
