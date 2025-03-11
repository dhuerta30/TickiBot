<?php

namespace App\Controllers;

use App\core\DB;
use App\core\View;
use App\core\Request;

class UserController
{
    public function show(Request $request) {

        $param1 = $request->get('param1');
        $param2 = $request->get('param2');
        $param3 = $request->get('param3');
        $all = $request->all();

        if ($param1 && $param2 && $param3) {
            echo "Usuario Valor1: " . htmlspecialchars($param1) . "<br>";
            echo "Usuario Valor2: " . htmlspecialchars($param2) . "<br>";
            echo "Usuario Valor3: " . htmlspecialchars($param3) . "<br>";
        } else {
            echo "No se proporcionó un usuario.";
        }
    }

    public function edit()
    {
        View::render('product');
        
    }
}
