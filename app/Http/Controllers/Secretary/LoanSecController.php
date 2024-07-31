<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanSecController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener los préstamos recientes desde la base de datos
        $recentLoans = DB::table('resources_borrowed')
        ->join('resources', 'resources_borrowed.resource_id', '=', 'resources.id')
        ->join('status', 'resources_borrowed.status_id', '=', 'status.id')
        ->select('resources.name as resource_name', 'resources_borrowed.loan_date', 'resources_borrowed.return_date', 'status.name as status_name')
        ->orderBy('resources_borrowed.loan_date', 'desc') // Ordenar de más reciente a más antiguo
        ->get();


        // Obtener todos los recursos disponibles para el formulario de reserva
        $resources = DB::table('resources')->get();

        // Pasar los datos a la vista
        return view('Secretary.LoanSec', [
            'recentLoans' => $recentLoans,
            'resources' => $resources,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'loan_date' => 'required|date|after:now',
            'return_date' => 'required|date|after:loan_date',
            'observation' => 'required|string',
        ]);

        $resource_id = $request->input('resource_id');
        $loan_date = $request->input('loan_date');
        $return_date = $request->input('return_date');

        // Comprobar disponibilidad del recurso
        $conflictLoans = DB::table('resources_borrowed')
            ->where('resource_id', $resource_id)
            ->where(function ($query) use ($loan_date, $return_date) {
                $query->whereBetween('loan_date', [$loan_date, $return_date])
                      ->orWhereBetween('return_date', [$loan_date, $return_date])
                      ->orWhere(function ($query) use ($loan_date, $return_date) {
                          $query->where('loan_date', '<=', $loan_date)
                                ->where('return_date', '>=', $return_date);
                      });
            })
            ->count();

        // Obtener el recurso
        $resource = DB::table('resources')->where('id', $resource_id)->first();

        // Validar si el recurso está disponible
        if ($conflictLoans >= $resource->quantity) {
            return back()->withErrors(['msg' => 'El recurso no está disponible en las fechas seleccionadas.']);
        }

        // Guardar el préstamo
        DB::table('resources_borrowed')->insert([
            'resource_id' => $resource_id,
            'user_id' => auth()->id(), // Ajustar según sea necesario
            'status_id' => 1, // ID de estado 'aceptado'
            'observation' => $request->input('observation'), // Cambiar a minúsculas
            'loan_date' => $loan_date,
            'return_date' => $return_date,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        

        // Redirigir con un mensaje de éxito
        return redirect()->route('LoanSec.index')->with('success', 'Préstamo añadido correctamente.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Dejar vacío o implementar según sea necesario
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Dejar vacío o implementar según sea necesario
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Dejar vacío o implementar según sea necesario
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Dejar vacío o implementar según sea necesario
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Dejar vacío o implementar según sea necesario
    }
}
