<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResourceBorrowed;
use App\Models\Resource;
use Carbon\Carbon;
use PDF;

class LoansApprovedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los recursos que tienen status_id = 3 (por aprobar)
        $resources = ResourceBorrowed::where('status_id', 3)->get();

        // Iterar sobre los recursos para obtener el nombre del recurso
        foreach ($resources as $resource) {
            // Obtener el nombre del recurso usando la relación en ResourceBorrowed
            $resourceName = Resource::find($resource->resource_id)->name ?? 'Recurso no encontrado';
            $resource->name = $resourceName; // Asignar el nombre del recurso al objeto $resource

            // Convertir las fechas a objetos Carbon para formatearlas en la vista
            $resource->loan_date = Carbon::parse($resource->loan_date);
            $resource->return_date = Carbon::parse($resource->return_date);
        }

        return view('Secretary.LoansApproved', compact('resources'));
    }

    /**
     * Update the resource status to approved (status_id = 1).
     */
    public function approve(Request $request, $id)
    {
        $resource = ResourceBorrowed::findOrFail($id);
        $resource->status_id = 1; // Cambiar estado a aprobado

        if ($request->has('observationSec')) {
            $resource->observationSec = $request->input('observationSec');
        }

        $resource->save();

        return redirect()->back()->with('success', 'Préstamo aprobado correctamente.');
    }

    /**
     * Update the resource status to rejected (status_id = 2).
     */
    public function reject(Request $request, $id)
    {
        $resource = ResourceBorrowed::findOrFail($id);
        $resource->status_id = 2; // Cambiar estado a rechazado

        if ($request->has('observationSec')) {
            $resource->observationSec = $request->input('observationSec');
        }

        $resource->save();

        return redirect()->back()->with('success', 'Préstamo rechazado correctamente.');
    }

    /**
     * Generate a PDF with today's loans.
     */
    public function generatePDF()
    {
        $today = Carbon::today();
        $loans = ResourceBorrowed::where('status_id', 1)
            ->whereDate('loan_date', $today)
            ->with(['resource', 'user'])
            ->get();
    
        // Convertir las fechas a objetos Carbon en caso de que no se haya hecho automáticamente
        foreach ($loans as $loan) {
            $loan->loan_date = Carbon::parse($loan->loan_date);
            $loan->return_date = Carbon::parse($loan->return_date);
        }
    
        $totalLoans = $loans->count();
        $resources = Resource::all();
    
        $resourceLoans = $resources->map(function ($resource) use ($loans) {
            $count = $loans->where('resource_id', $resource->id)->count();
            return [
                'name' => $resource->name,
                'count' => $count,
            ];
        });
    
        // Filtrar préstamos con recursos válidos
        $loans = $loans->filter(function ($loan) {
            return $loan->resource !== null;
        });
    
        $data = [
            'totalLoans' => $totalLoans,
            'resourceLoans' => $resourceLoans,
            'loans' => $loans,
        ];
    
        $pdf = PDF::loadView('pdf.loans', $data);
    
        return $pdf->download('loans.pdf');
    }
    
}
