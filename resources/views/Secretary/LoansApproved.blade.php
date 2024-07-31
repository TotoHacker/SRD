@extends('template')

@section('content')
<div class="h-screen">
    <div class="relative w-full h-auto bg-gray-100 mx-auto my-4 shadow-lg text-center py-6 px-6 rounded-2xl max-w-6xl">
        <div class="flex justify-end mb-4">
            <a href="{{ route('generate.pdf') }}" target="_blank" class="flex justify-center items-center w-28 h-10 sm:w-36 sm:h-12 rounded-lg text-sm sm:text-lg text-white bg-teal-500 hover:bg-teal-600 hover:shadow-lg transition-transform transform hover:scale-105">
                Generar Reporte
            </a>
        </div>
        <div class="flex justify-center mb-6">
            <h1 class="text-2xl font-bold">Préstamos a aprobar</h1>
        </div>
        <div class="flex justify-around border-b pb-3 mb-4">
            <h2 class="text-lg flex-1 text-center font-semibold">Recurso</h2>
            <h2 class="text-lg flex-1 text-center font-semibold">Usuario</h2>
            <h2 class="text-lg flex-1 text-center font-semibold">Motivo</h2>
            <h2 class="text-lg flex-1 text-center font-semibold">Fecha</h2>
            <h2 class="text-lg flex-1 text-center font-semibold">Hora</h2>
            <h2 class="text-lg flex-1 text-center font-semibold">Aprobar - Rechazar</h2>
        </div>
        @if($resources->isEmpty())
        <p class="text-gray-500">No hay datos que mostrar.</p>
        @else
        @foreach ($resources as $resource)
        <div class="flex text-lg justify-around items-center py-2 border-t">
            <p class="flex-1 text-center">{{ $resource->name ?? 'Recurso no encontrado' }}</p>
            <p class="flex-1 text-center">{{ $resource->user->NameUser }}</p>
            <p class="flex-1 text-center">{{ $resource->Observation }}</p>
            <p class="flex-1 text-center">{{ $resource->loan_date->format('d M') }}</p>
            <p class="flex-1 text-center">{{ $resource->return_date->format('H:i') }} - {{ $resource->return_date->addHour()->format('H:i') }}</p>
            <div class="flex-1 flex justify-center items-center">
                <button onclick="openModal('approve', {{ $resource->id }})" class="mx-2">
                    <img src="{{ asset('Imagenes/Check.png') }}" alt="Check" class="w-6 h-6">
                </button>
                <button onclick="openModal('reject', {{ $resource->id }})">
                    <img src="{{ asset('Imagenes/equis.png') }}" alt="Cancel" class="w-6 h-6">
                </button>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- Modal -->
    <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden transition my-auto p-4">
        <div class="w-full bg-white rounded-xl overflow-hidden max-w-lg transform transition-all duration-300 scale-95 opacity-0">
            <div class="relative">
                <button onclick="closeModal()" type="button" class="absolute top-2 right-2">
                    <svg title="Close" class="h-4 w-4 cursor-pointer text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close</span>
                </button>
                <div class="p-4 space-y-2 text-center">
                    <h2 class="text-xl font-bold tracking-tight" id="modalTitle">Confirmar Acción</h2>
                    <p class="text-gray-500" id="modalMessage">¿Estás seguro que deseas realizar esta acción?</p>
                </div>
                <div class="px-6 py-4">
                    <form id="modalForm" method="POST">
                        @csrf
                        <input type="hidden" name="action" id="modalAction">
                        <div class="space-y-4">
                            <textarea name="observationSec" id="observationSec" rows="3" placeholder="Añadir Observacion" class="block p-3 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            <p class="text-xs text-gray-500 hidden" id="observationSecMessage">* La observación es obligatoria para rechazar.</p>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="closeModal()" class="py-2 px-4 mr-2 inline-flex justify-center items-center gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600">
                                Cancelar
                            </button>
                            <button type="submit" class="py-2 px-4 inline-flex justify-center items-center gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-red-600 hover:bg-red-500 focus:bg-red-700 focus:ring-offset-red-700">
                                Confirmar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(action, id) {
        const modal = document.getElementById('confirmationModal');
        const modalForm = document.getElementById('modalForm');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const observationSecMessage = document.getElementById('observationSecMessage');
        const modalContent = modal.querySelector('.bg-white');

        modalForm.action = action === 'approve' ? `{{ route('approve.resource', '') }}/${id}` : `{{ route('reject.resource', '') }}/${id}`;
        modalTitle.innerText = action === 'approve' ? 'Confirmar Aprobación' : 'Confirmar Rechazo';
        modalMessage.innerText = action === 'approve' ? '¿Estás seguro que deseas aprobar este préstamo?' : '¿Estás seguro que deseas rechazar este préstamo?';

        if (action === 'reject') {
            observationSecMessage.classList.remove('hidden');
        } else {
            observationSecMessage.classList.add('hidden');
        }

        document.getElementById('modalAction').value = action;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 100);
    }

    function closeModal() {
        const modal = document.getElementById('confirmationModal');
        const modalContent = modal.querySelector('.bg-white');

        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
