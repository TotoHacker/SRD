<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SingInController extends Controller
{
    /**
     * Display the login form.
     */
    public function index()
    {
        return view('SingIn');
    }

    /**
     * Handle the login request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Obtener el rol del usuario autenticado
            $role = Auth::user()->role;

            // Actualizar el campo StatusSesion a 5
            User::where('id', Auth::id())->update(['StatusSesion' => 5]);

            // Redirigir según el rol
            if ($role === 'admin') {
                return redirect()->intended('/Admin');
            } elseif ($role === 'secretary') {
                return redirect()->intended('/Secretary');
            } else {
                return redirect()->intended('/');
            }
        }

        // Usuario no está registrado
        return redirect()->back()->withErrors([
            'message' => 'Credenciales incorrectas',
        ]);
    }

    /**
     * Handle the logout request.
     */
    public function destroy(Request $request)
    {
        // Actualizar el campo StatusSesion a 6
        User::where('id', Auth::id())->update(['StatusSesion' => 6]);

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
