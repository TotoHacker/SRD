@extends('template')

@section('content')
<div class="p-10 text-center relative">
 

    <div class="relative w-full h-auto bg-gray-100 m-4 shadow-lg text-center p-5 rounded-lg">
        <h1 class="text-2xl my-4 font-bold">Pendientes a aceptación</h1>
        <div class="flex justify-end mb-4">
            <button onclick="toggleAddLoanModal()" class="flex justify-end items-center w-28 h-10 sm:w-36 sm:h-12 rounded-lg text-sm sm:text-lg text-white bg-teal-500 hover:bg-teal-600 hover:shadow-lg transition-transform transform hover:scale-105 p-2">
                Añadir préstamo 
            </button>
        </div>
        <div class="hidden md:flex justify-center my-4 text-lg ">
            <div class="w-1/5">Recurso</div>
            <div class="w-1/5">Horario</div>
            <div class="w-1/5">Fecha</div>
            <div class="w-1/5">Estatus</div>
            <div class="w-1/5">Motivo</div>
        </div>

        <div class="mx-auto text-base">
            @if($pendientes->isEmpty())
                <p class="text-gray-500">No hay datos que mostrar.</p>
            @else
                @foreach ($pendientes as $loan)
                    <div class="flex flex-col md:flex-row justify-center my-3 text-center bg-white p-3 rounded-lg shadow-sm">
                        <div class="w-full md:w-1/5">{{ $loan->resource_name }}</div>
                        <div class="w-full md:w-1/5">{{ \Carbon\Carbon::parse($loan->loan_date)->format('H:i') }} - {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('H:i') : 'No devuelto' }}</div>
                        <div class="w-full md:w-1/5">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d-M') }}</div>
                        <div class="w-full md:w-1/5 text-orange-500">
                            Pendiente a aceptación
                        </div>
                        <div class="w-full md:w-1/5">{{ $loan->Observation }}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="relative w-full h-auto bg-gray-100 m-4 shadow-lg text-center p-5 rounded-lg">
        <h1 class="text-2xl my-4 font-bold">Otros</h1>

        <div class="hidden md:flex justify-center my-4 text-lg">
            <div class="w-1/5">Recurso</div>
            <div class="w-1/5">Horario</div>
            <div class="w-1/5">Fecha</div>
            <div class="w-1/5">Estatus</div>
            <div class="w-1/5">Observaciones</div>
        </div>

        <div class="mx-auto text-base">
            @if($aceptados->isEmpty() && $rechazados->isEmpty() && $vencidos->isEmpty())
                <p class="text-gray-500">No hay datos que mostrar.</p>
            @else
                @foreach ($aceptados as $loan)
                    <div class="flex flex-col md:flex-row justify-center my-3 text-center bg-white p-3 rounded-lg shadow-sm">
                        <div class="w-full md:w-1/5">{{ $loan->resource_name }}</div>
                        <div class="w-full md:w-1/5">{{ \Carbon\Carbon::parse($loan->loan_date)->format('H:i') }} - {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('H:i') : 'No devuelto' }}</div>
                        <div class="w-full md:w-1/5">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d-M') }}</div>
                        <div class="w-full md:w-1/5 text-green-500">
                            Aceptado
                        </div>
                        <div class="w-full md:w-1/5">{{ $loan->observationSec ?? 'Sin observaciones' }}</div>
                    </div>
                @endforeach

                @foreach ($rechazados as $loan)
                    <div class="flex flex-col md:flex-row justify-center my-3 text-center bg-white p-3 rounded-lg shadow-sm">
                        <div class="w-full md:w-1/5">{{ $loan->resource_name }}</div>
                        <div class="w-full md:w-1/5">{{ \Carbon\Carbon::parse($loan->loan_date)->format('H:i') }} - {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('H:i') : 'No devuelto' }}</div>
                        <div class="w-full md:w-1/5">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d-M') }}</div>
                        <div class="w-full md:w-1/5 text-red-700">
                            Rechazado
                        </div>
                        <div class="w-full md:w-1/5">{{ $loan->observationSec ?? 'Sin observaciones' }}</div>
                    </div>
                @endforeach

                @foreach ($vencidos as $loan)
                    <div class="flex flex-col md:flex-row justify-center my-3 text-center bg-white p-3 rounded-lg shadow-sm">
                        <div class="w-full md:w-1/5">{{ $loan->resource_name }}</div>
                        <div class="w-full md:w-1/5">{{ \Carbon\Carbon::parse($loan->loan_date)->format('H:i') }} - {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('H:i') : 'No devuelto' }}</div>
                        <div class="w-full md:w-1/5">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d-M') }}</div>
                        <div class="w-full md:w-1/5 text-red-900">
                            Vencido
                        </div>
                        <div class="w-full md:w-1/5">{{ $loan->observationSec ?? 'Sin observaciones' }}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addLoanModal" class="fixed inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
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

<form method="POST" action="{{ route('Loans.store') }}">
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
        <label for="Observation" class="block text-gray-700 font-bold mb-2">Observaciones:</label>
        <input type="text" name="Observation" id="Observation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        @error('Observation')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
    <div class="mt-5 sm:mt-6">
        <button type="submit" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-500 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:text-sm">
            Añadir
        </button>
        <button type="button" onclick="toggleAddLoanModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:text-sm">
            Cancelar
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
function toggleAddLoanModal() {
    var modal = document.getElementById('addLoanModal');
    modal.classList.toggle('hidden');
}
</script>

@endsection
