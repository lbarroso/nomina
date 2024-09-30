<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{

   /**
     * Mostrar el formulario para cambiar la contraseña.
     */
    public function changePasswordForm()
    {
        return view('auth.passwords.change');
    }

    /**
     * Actualizar la contraseña del usuario.
     */
    public function updatePassword(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Verificar si la contraseña actual es correcta
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        // Actualizar la contraseña del usuario
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', '¡Contraseña actualizada correctamente!');
    }    

} // class
