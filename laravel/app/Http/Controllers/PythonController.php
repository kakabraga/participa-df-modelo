<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Integrations\Python\PythonRunner;

class PythonController extends Controller
{
    public function testePython(PythonRunner $python)
    {
        $saida = $python->run();

        return json_encode($saida);
    }
}
