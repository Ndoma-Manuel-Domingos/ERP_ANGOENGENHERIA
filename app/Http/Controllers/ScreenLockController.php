<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ScreenLockController extends Controller
{
    //
    
    public function show()
    {
        if (!Session::has('screen_locked')) {
            return redirect()->route('home'); // ou a rota principal da aplicação
        }

        $lockStartTime = Session::get('lock_start_time');
        $unlockTime = Carbon::parse($lockStartTime)->addHours(24);
        $remainingTime = $unlockTime->diffInSeconds(Carbon::now());

        return view('screen_locked', compact('remainingTime'));
    }

    public function unlock(Request $request)
    {
        $pin = $request->input('pin');
            
        // Verifique se o PIN é válido (substitua por sua lógica de validação)
        if ($pin === "1234") {
            Session::forget('screen_locked');
            Session::forget('lock_start_time');
            Session::forget('last_activity');
            return redirect()->route('pronto-venda'); // ou a rota principal da aplicação
        }

        return redirect()->route('screen.locked')->withErrors(['pin' => 'PIN inválido.']);
    }
    
}
