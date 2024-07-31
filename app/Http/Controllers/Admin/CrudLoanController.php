<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceBorrowed;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CrudLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = ResourceBorrowed::with('resource', 'user')->get();
        $resources = Resource::all();
        $users = User::all();
        return view('Admins.CrudLoans', compact('loans', 'resources', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $resources = Resource::all();
        $users = User::all();
        return view('Admin.createLoan', compact('resources', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|integer',
            'loan_date' => 'required|date',
            'return_date' => 'nullable|date',
            'Observation' => 'nullable|string',
        ]);

        // Convert dates to Carbon instances
        $validatedData['loan_date'] = Carbon::parse($validatedData['loan_date']);
        $validatedData['return_date'] = $validatedData['return_date'] ? Carbon::parse($validatedData['return_date']) : null;

        ResourceBorrowed::create($validatedData);

        return redirect()->route('CrudLoan.index')->with('success', 'Préstamo creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loan = ResourceBorrowed::findOrFail($id);
        return view('Admin.showLoan', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $loan = ResourceBorrowed::findOrFail($id);
        $resources = Resource::all();
        $users = User::all();
        return view('Admin.editLoan', compact('loan', 'resources', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'user_id' => 'required|exists:users,id',
            'status_id' => 'required|integer',
            'loan_date' => 'required|date',
            'return_date' => 'nullable|date',
            'Observation' => 'nullable|string',
        ]);

        // Convert dates to Carbon instances
        $validatedData['loan_date'] = Carbon::parse($validatedData['loan_date']);
        $validatedData['return_date'] = $validatedData['return_date'] ? Carbon::parse($validatedData['return_date']) : null;

        $loan = ResourceBorrowed::findOrFail($id);
        $loan->update($validatedData);

        return redirect()->route('CrudLoan.index')->with('success', 'Préstamo actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loan = ResourceBorrowed::findOrFail($id);
        $loan->delete();

        return redirect()->route('CrudLoan.index')->with('success', 'Préstamo eliminado con éxito.');
    }
}
