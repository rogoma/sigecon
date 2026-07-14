<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Viatico;
use Illuminate\Support\Facades\Validator;


class ViaticoController extends Controller
{
    public function index()
    {
        $viaticos = Viatico::orderBy('fecha')->get();
        return view('viaticos.index', compact('viaticos'));
    }

    public function create()
    {
        $ultimo = Viatico::orderBy('id', 'desc')->first();
        $saldo_inicial = $ultimo ? $ultimo->saldo_final : 0;


        return view('viaticos.create', compact('saldo_inicial'));
    }


    public function store(Request $request)
    {
    $request->validate([
        'saldo_inicial' => 'required|numeric|min:0',
        'fecha' => 'required|date',
        'motivo' => 'required|string|max:255',
        'monto_viatico' => 'string|required|max:9223372036854775807',       
    ]);
 
    $saldo_inicial = str_replace('.', '',($request->input('saldo_inicial')));
    $monto_viatico = str_replace('.', '',($request->input('monto_viatico')));        
   
    $saldo_final = $saldo_inicial - $monto_viatico;


    // Validación extra
    if ($saldo_final < 0) {
        return back()->withErrors(['monto_viatico' => 'El monto excede el saldo disponible'])
                     ->withInput();
    }


    Viatico::create([
        'fecha' => $request->fecha,
        'saldo_inicial' => $request->saldo_inicial,
        'motivo' => $request->motivo,
        'monto_viatico' => $monto_viatico,
        'saldo_final' => $saldo_final
    ]);


    return redirect()->route('viaticos.index')->with('success', 'Viático ingresado correctamente.');
    }

    public function tablero()
    {
        // $viaticos = Viatico::orderBy('fecha')->get();
        return view('tablero');
    }


}
