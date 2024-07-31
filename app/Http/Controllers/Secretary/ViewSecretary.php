<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ViewSecretary extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el número de préstamos en proceso (status_id = 3)
        $inProcessCount = DB::table('resources_borrowed')
            ->where('status_id', 3)
            ->count();

        // Obtener el número de préstamos aprobados (status_id = 1)
        $approvedCount = DB::table('resources_borrowed')
            ->where('status_id', 1)
            ->count();

        // Obtener la fecha y hora actual
        $currentDateTime = Carbon::now();

        // Obtener los 5 préstamos más próximos a entregar
        $upcomingDeliveries = DB::table('resources_borrowed')
            ->join('resources', 'resources_borrowed.resource_id', '=', 'resources.id')
            ->join('status', 'resources_borrowed.status_id', '=', 'status.id')
            ->where('resources_borrowed.status_id', 1) // Estado aprobado
            ->whereDate('return_date', '=', $currentDateTime->toDateString()) // Fecha de retorno es hoy
            ->whereNotIn('status.id', [/* Aquí pones el ID del estado 'devoted' */]) // Excluir estado 'devoted'
            ->orderBy('return_date', 'asc')
            ->orderByRaw('ABS(TIMESTAMPDIFF(MINUTE, return_date, ?))', [$currentDateTime])
            ->select('resources.name as resource_name', 'resources_borrowed.loan_date', 'resources_borrowed.return_date', 'resources_borrowed.id')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $item->loan_date = Carbon::parse($item->loan_date);
                $item->return_date = Carbon::parse($item->return_date);
                return $item;
            });

        // Depuración: Imprime los próximos préstamos para verificar
        \Log::info('Upcoming Deliveries:', $upcomingDeliveries->toArray());

        // Obtener la cantidad de préstamos aprobados por tipo de recurso
        $resourceLoansCount = DB::table('resources_borrowed')
            ->join('resources', 'resources_borrowed.resource_id', '=', 'resources.id')
            ->where('resources_borrowed.status_id', 1)
            ->select('resources.name', DB::raw('count(resources_borrowed.id) as total'))
            ->groupBy('resources.name')
            ->get();

        // Preparar los datos para la gráfica
        $graphData = [
            'Sala Audio Visual' => 0,
            'Laboratorio' => 0,
            'Otros' => 0
        ];

        foreach ($resourceLoansCount as $resourceLoan) {
            if (array_key_exists($resourceLoan->name, $graphData)) {
                $graphData[$resourceLoan->name] = $resourceLoan->total;
            } else {
                $graphData['Otros'] += $resourceLoan->total;
            }
        }

        return view('Secretary.ViewSec', compact('inProcessCount', 'approvedCount', 'upcomingDeliveries', 'graphData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $statusId = $request->input('status_id');
        $observationSec = $request->input('observationSec', '');
    
        // Validar la observación si el préstamo se está rechazando
        if ($statusId == 0 && empty($observationSec)) {
            return redirect()->back()->withErrors(['observationSec' => 'La observación es obligatoria para rechazar.']);
        }
    
        // Actualizar el estado del préstamo
        DB::table('resources_borrowed')
            ->where('id', $id)
            ->update([
                'status_id' => $statusId,
                'observationSec' => $observationSec,
                'updated_at' => Carbon::now()
            ]);
    
        return redirect()->route('Secretary.index')->with('success', 'Préstamo actualizado con éxito.');
    }
    

    /**
     * Confirm delivery of a resource.
     */
    public function confirmDelivery(Request $request, $id)
    {
        // Actualizar el estado del préstamo a 'entregado' (status_id = 2)
        DB::table('resources_borrowed')
            ->where('id', $id)
            ->update([
                'status_id' => 2, // Assuming 2 is the status_id for "delivered"
                'updated_at' => Carbon::now()
            ]);

        return redirect()->route('Secretary.index')->with('success', 'Préstamo marcado como entregado.');
    }

    /**
     * Cancel a loan.
     */
    public function cancelLoan(Request $request, $id)
    {
        // Actualizar el estado del préstamo a 'cancelado' (status_id = 0)
        $observationSec = $request->input('observationSec', '');

        // Validar la observación si el préstamo se está cancelando
        if (empty($observationSec)) {
            return redirect()->back()->withErrors(['observationSec' => 'La observación es obligatoria para cancelar.']);
        }

        DB::table('resources_borrowed')
            ->where('id', $id)
            ->update([
                'status_id' => 0, // Assuming 0 is the status_id for "canceled"
                'observationSec' => $observationSec,
                'updated_at' => Carbon::now()
            ]);

        return redirect()->route('Secretary.index')->with('success', 'Préstamo cancelado con éxito.');
    }
}
