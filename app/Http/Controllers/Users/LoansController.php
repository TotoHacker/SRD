<?php
namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert; // Importa la fachada de SweetAlert
use Carbon\Carbon;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
    
        $loans = DB::table('resources_borrowed')
            ->join('resources', 'resources_borrowed.resource_id', '=', 'resources.id')
            ->join('status', 'resources_borrowed.status_id', '=', 'status.id')
            ->where('resources_borrowed.user_id', $userId)
            ->select('resources.name as resource_name', 'resources_borrowed.loan_date', 'resources_borrowed.return_date', 'status.name as status_name', 'resources_borrowed.Observation', 'resources_borrowed.observationSec') // Agregar observationSec
            ->get();
    
        // Dividir los préstamos según su estatus
        $pendientes = $loans->where('status_name', 'pendiente');
        $aceptados = $loans->where('status_name', 'aceptado');
        $rechazados = $loans->where('status_name', 'rechazado');
        $vencidos = $loans->where('status_name', 'vencido');
    
        $resources = DB::table('resources')->get();
    
        return view('Users.Loans', compact('pendientes', 'aceptados', 'rechazados', 'vencidos', 'resources')); // Pasar las colecciones a la vista
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validación de campos
    $request->validate([
        'resource_id' => 'required|exists:resources,id',
        'loan_date' => 'required|date',
        'return_date' => 'nullable|date',
        'Observation' => 'required|string|max:200',
        'observationSec' => 'nullable|string|max:200',
    ]);

    // Lógica para almacenar el préstamo en la base de datos
    DB::table('resources_borrowed')->insert([
        'resource_id' => $request->resource_id,
        'user_id' => Auth::id(),
        'status_id' => DB::table('status')->where('name', 'pendiente')->first()->id,
        'loan_date' => $request->loan_date,
        'return_date' => $request->return_date,
        'Observation' => $request->Observation,
        'observationSec' => $request->observationSec,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Mostrar alerta de éxito usando SweetAlert2
    Alert::success('Éxito', 'Préstamo añadido con éxito')->showConfirmButton('Ok', '#3085d6');

    return redirect()->route('Loans.index');
}

}
