<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Firma;
use Illuminate\Support\Facades\Auth;

class FirmaController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function update(Request $request)
    {
        $user = Auth::user(); 

        // Validar los datos enviados por el formulario
        $request->validate([
            'elaboro' => 'required|string|max:255',
            'elaboroNombre' => 'required|string|max:255',
            'valido' => 'required|string|max:255',
            'validoNombre' => 'required|string|max:255',
            'autorizo' => 'required|string|max:255',
            'autorizoNombre' => 'required|string|max:255',
        ]);

        $reviso = !empty($request->reviso) ? $request->reviso : '';
        $revisoNombre = !empty($request->revisoNombre) ? $request->revisoNombre : '';

        // Actualizar o crear el registro en la tabla firmas
        Firma::updateOrCreate(
            ['id' => $user->almcnt], // Asumiendo que solo tienes un registro para actualizar, puedes ajustar según tu necesidad
            [
                'elaboro' => $request->elaboro,
                'elaboroNombre' => $request->elaboroNombre,
                'valido' => $request->valido,
                'validoNombre' => $request->validoNombre,
                'autorizo' => $request->autorizo,
                'autorizoNombre' => $request->autorizoNombre,
                'reviso' => $reviso,
                'revisoNombre' => $revisoNombre,
            ]
        );

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Firmas actualizadas correctamente.');
    }    


    public function create()
    {

        $user = Auth::user();  

        $firma = Firma::where('almcnt', $user->almcnt)->first();         

        return view('firmas.firmas', compact('firma') );
    }


} // class
