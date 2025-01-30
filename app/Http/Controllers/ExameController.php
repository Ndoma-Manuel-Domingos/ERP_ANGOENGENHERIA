<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ExameController extends Controller
{
    //
        
    public function marcar_exame(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        dd($cliente);
    }
    
}
