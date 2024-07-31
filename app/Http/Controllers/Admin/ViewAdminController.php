<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ResourceBorrowed;
use App\Models\Resource;

class ViewAdminController extends Controller
{
    public function index()
    {
        $totalUser = User::count();
        $ActivateUser = User::where('StatusSesion', '5')->count();
        $pendingLoans = ResourceBorrowed::where('status_id', 3)->count(); // Suponiendo que 3 es el ID para 'Pendientes'

        // Obtener recursos y contarlos según su tipo
        $resources = Resource::select('description', \DB::raw('count(*) as total'))
                             ->groupBy('description')
                             ->get()
                             ->keyBy('description')
                             ->toArray();

        // Obtener préstamos y contarlos según su estado
        $loans = ResourceBorrowed::select('status_id', \DB::raw('count(*) as total'))
                                 ->groupBy('status_id')
                                 ->get()
                                 ->keyBy('status_id')
                                 ->toArray();

        return view('Admins.Adminview', compact('totalUser', 'ActivateUser', 'resources', 'pendingLoans', 'loans'));
    }
}
