<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Resource; // Importa el modelo Resource
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resources = Resource::all(); // Obtener todos los recursos
        return view('Secretary.Resources', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No se necesita en este caso ya que usamos un modal para la creación
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
        ]);

        Resource::create($validatedData);

        return redirect()->route('CResource.index')->with('success', 'Resource created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // No se necesita en este caso ya que no mostramos un recurso individualmente en detalle
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resource = Resource::findOrFail($id);
        return response()->json($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
        ]);

        $resource = Resource::findOrFail($id);
        $resource->update($validatedData);

        return redirect()->route('CResource.index')->with('success', 'Resource updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resource = Resource::findOrFail($id);
        $resource->delete();

        return redirect()->route('CResource.index')->with('success', 'Resource deleted successfully.');
    }
}
