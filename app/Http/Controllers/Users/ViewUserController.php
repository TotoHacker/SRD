<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ViewUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $currentDateTime = Carbon::now();

        // Obtener los préstamos del usuario loggeado
        $loans = DB::table('resources_borrowed')
            ->join('resources', 'resources_borrowed.resource_id', '=', 'resources.id')
            ->join('status', 'resources_borrowed.status_id', '=', 'status.id')
            ->where('resources_borrowed.user_id', $userId)
            ->select('resources_borrowed.id as loan_id', 'resources.name as resource_name', 'resources_borrowed.loan_date', 'resources_borrowed.return_date', 'status.name as status_name', 'resources_borrowed.status_id', 'resources_borrowed.Observation') // Agregar Observation
            ->get();

        // Actualizar el estado de los préstamos vencidos y pendientes
        foreach ($loans as $loan) {
            $loanStartDate = Carbon::parse($loan->loan_date);
            $loanEndDate = $loan->return_date ? Carbon::parse($loan->return_date) : null;

            if ($loan->status_id == 2) { // Si el préstamo fue aceptado
               
            } elseif ($loan->status_id == 3) { // Si el préstamo está pendiente de aprobación
                // Comparar fecha y hora actual con fecha y hora inicial del préstamo
                if ($currentDateTime->gte($loanStartDate)) {
                    // Verificar si la fecha actual coincide exactamente con la fecha de inicio del préstamo
                    if ($currentDateTime->isSameDay($loanStartDate) && $currentDateTime->gte($loanStartDate)) {
                        // Comparar las horas si la fecha es la misma
                        if ($currentDateTime->gte($loanStartDate)) {
                            DB::table('resources_borrowed')
                                ->where('id', $loan->loan_id)
                                ->update(['status_id' => 4]); // Marcar como vencido

                            $loan->status_id = 4;
                            $loan->status_name = 'Vencido';
                        }
                    } elseif ($currentDateTime->gt($loanStartDate)) {
                        DB::table('resources_borrowed')
                            ->where('id', $loan->loan_id)
                            ->update(['status_id' => 4]); // Marcar como vencido

                        $loan->status_id = 4;
                        $loan->status_name = 'Vencido';
                    }
                }
            }
        }

        // Contar los préstamos pendientes
        $pendingCount = $loans->filter(function ($loan) {
            return $loan->status_id == 3;
        })->count();

        // Obtener los próximos 5 préstamos más cercanos a vencer que estén aceptados
        $upcomingLoans = $loans->filter(function ($loan) use ($currentDateTime) {
            return $loan->status_id == 2 && Carbon::parse($loan->loan_date)->gt($currentDateTime);
        })->sortBy('loan_date')->take(5);

        return view('Users.ViewUser', compact('loans', 'pendingCount', 'upcomingLoans'));
    }
}


