@extends('template')

@section('content')
<div class="p-4 sm:p-10 text-center h-screen">
    <div class="relative w-full h-auto bg-gray-100 m-4 shadow-lg p-4 sm:p-5">
        
        <div class="flex p-5">
        <h1 class="text-xl sm:text-2xl my-4 w-4/5">Total de préstamos</h1>
        <div class="flex justify-end mb-4">
            <button onclick="openModal()" class="flex justify-center items-center w-28 h-10 sm:w-36 sm:h-12 rounded-lg text-sm sm:text-lg text-white bg-teal-500 hover:bg-teal-600 hover:shadow-lg transition-transform transform hover:scale-105">
                Reservar Recurso
            </button>
        </div>
        </div>
        <!-- Encabezados visibles solo en pantallas grandes -->
        <div class="hidden sm:grid grid-cols-4 gap-4 text-lg font-bold my-4">
            <h1 class="text-center">Recurso</h1>
            <h1 class="text-center">Horario</h1>
            <h1 class="text-center">Fecha</h1>
            <h1 class="text-center">Estatus</h1>
        </div>

        <!-- Tarjetas visibles solo en pantallas pequeñas -->
        <div class="block sm:hidden">
            @foreach ($recentLoans as $loan)
            @php
                $statusColor = 'text-orange-600'; // Valor por defecto
                if (strtolower($loan->status_name) === 'aceptado') {
                    $statusColor = 'text-green-500';
                } elseif (strtolower($loan->status_name) === 'rechazado') {
                    $statusColor = 'text-red-800';
                } elseif (strtolower($loan->status_name) === 'vencido') {
                    $statusColor = 'text-red-600';
                }
            @endphp
            <div class="bg-white shadow-md p-4 mb-4 rounded-lg">
                <div class="mb-2 font-bold">Recurso:</div>
                <div class="mb-4">{{ $loan->resource_name }}</div>
                
                <div class="mb-2 font-bold">Horario:</div>
                <div class="mb-4">{{ \Carbon\Carbon::parse($loan->loan_date)->format('H:i') }} - {{ \Carbon\Carbon::parse($loan->return_date)->format('H:i') ?? 'No devuelto' }}</div>
                
                <div class="mb-2 font-bold">Fecha:</div>
                <div class="mb-4">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d-M-Y') }}</div>
                
                <div class="mb-2 font-bold">Estatus:</div>
                <div class="{{ $statusColor }}">
                    {{ ucfirst($loan->status_name) }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tabla visible solo en pantallas grandes -->
        <div class="hidden sm:block text-base">
            @foreach ($recentLoans as $loan)
            @php
                $statusColor = 'text-orange-600'; // Valor por defecto
                if (strtolower($loan->status_name) === 'aceptado') {
                    $statusColor = 'text-green-500';
                } elseif (strtolower($loan->status_name) === 'rechazado') {
                    $statusColor = 'text-red-800';
                } elseif (strtolower($loan->status_name) === 'vencido') {
                    $statusColor = 'text-red-600';
                }
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-center my-5">
                <p class="flex-1">{{ $loan->resource_name }}</p>
                <p class="flex-1">{{ \Carbon\Carbon::parse($loan->loan_date)->format('H:i') }} - {{ \Carbon\Carbon::parse($loan->return_date)->format('H:i') ?? 'No devuelto' }}</p>
                <p class="flex-1">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d-M-Y') }}</p>
                <p class="flex-1 {{ $statusColor }}">
                    {{ ucfirst($loan->status_name) }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para añadir un préstamo -->
    <div id="loanModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Añadir Préstamo
                            </h3>

                            <!-- SweetAlert para mostrar errores generales -->
                            <script>
                                @if ($errors->any())
                                    swal("Error", "Completa todos los campos correctamente", "error");
                                @endif
                            </script>

                            <form method="POST" action="{{ route('LoanSec.store') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="resource_id" class="block text-gray-700 font-bold mb-2">Recurso:</label>
                                    <select name="resource_id" id="resource_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @foreach ($resources as $resource)
                                            <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('resource_id')
                                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="loan_date" class="block text-gray-700 font-bold mb-2">Fecha de Préstamo:</label>
                                    <input type="datetime-local" name="loan_date" id="loan_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('loan_date')
                                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="return_date" class="block text-gray-700 font-bold mb-2">Fecha de Devolución:</label>
                                    <input type="datetime-local" name="return_date" id="return_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('return_date')
                                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="observation" class="block text-gray-700 font-bold mb-2">Observación:</label>
                                    <textarea name="observation" id="observation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                    @error('observation')
                                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-5 sm:mt-6 sm:flex sm:justify-between">
                                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-500 text-base font-medium text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                                        Guardar Préstamo
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('loanModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('loanModal').classList.add('hidden');
        }
    </script>
</div>
@endsection
