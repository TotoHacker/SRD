@extends('Administrador')

@section('content')
<div class="m-20">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Lista de Recursos</h1>
        <a href="#" onclick="toggleAddResourceModal()" class="bg-teal-600 hover:bg-teal-800 text-white font-bold py-2 px-4 rounded">Añadir Recurso</a>
    </div>

    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-teal-900 text-white">
            <tr>
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Descripción</th>
                <th class="px-4 py-3">Cantidad</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @foreach ($resources as $resource)
            <tr>
                <td class="border px-4 py-2">{{ $resource->id }}</td>
                <td class="border px-4 py-2">{{ $resource->name }}</td>
                <td class="border px-4 py-2">{{ $resource->description }}</td>
                <td class="border px-4 py-2">{{ $resource->quantity }}</td>
                <td class="border px-4 py-2">
                    <a href="#" data-id="{{ $resource->id }}" data-name="{{ $resource->name }}" data-description="{{ $resource->description }}" data-quantity="{{ $resource->quantity }}" onclick="openEditResourceModal(this)" class="text-blue-500 hover:text-blue-700">Editar</a>
                    <form action="{{ route('CrudResource.destroy', $resource->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 flex justify-between items-center">
        <button class="bg-teal-600 hover:bg-teal-800 text-white font-bold py-2 px-4 rounded" onclick="previousPage()">Anterior</button>
        <button class="bg-teal-600 hover:bg-teal-800 text-white font-bold py-2 px-4 rounded" onclick="nextPage()">Siguiente</button>
    </div>
</div>

<!-- Modal para Añadir Recurso -->
<div id="addResourceModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
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
                            Añadir Recurso
                        </h3>
                        <form method="POST" action="{{ route('CrudResource.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-bold mb-2">Nombre:</label>
                                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block text-gray-700 font-bold mb-2">Descripción:</label>
                                <input type="text" name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="quantity" class="block text-gray-700 font-bold mb-2">Cantidad:</label>
                                <input type="number" name="quantity" id="quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <button type="button" onclick="toggleAddResourceModal()"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-400 text-base font-medium text-white hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 sm:text-sm">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:text-sm">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Recurso -->
<div id="editResourceModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
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
                            Editar Recurso
                        </h3>
                        <form id="editResourceForm" method="POST" action="#">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="editResourceId">
                            <div class="mb-4">
                                <label for="editName" class="block text-gray-700 font-bold mb-2">Nombre:</label>
                                <input type="text" name="name" id="editName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="editDescription" class="block text-gray-700 font-bold mb-2">Descripción:</label>
                                <input type="text" name="description" id="editDescription" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="editQuantity" class="block text-gray-700 font-bold mb-2">Cantidad:</label>
                                <input type="number" name="quantity" id="editQuantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <button type="button" onclick="toggleEditResourceModal()"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-400 text-base font-medium text-white hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 sm:text-sm">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:text-sm">
                                    Guardar
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
    function toggleAddResourceModal() {
        document.getElementById('addResourceModal').classList.toggle('hidden');
    }

    function openEditResourceModal(element) {
        const resourceId = element.getAttribute('data-id');
        const resourceName = element.getAttribute('data-name');
        const resourceDescription = element.getAttribute('data-description');
        const resourceQuantity = element.getAttribute('data-quantity');

        document.getElementById('editResourceId').value = resourceId;
        document.getElementById('editName').value = resourceName;
        document.getElementById('editDescription').value = resourceDescription;
        document.getElementById('editQuantity').value = resourceQuantity;

        document.getElementById('editResourceForm').action = `/CrudResource/${resourceId}`;
        toggleEditResourceModal();
    }

    function toggleEditResourceModal() {
        document.getElementById('editResourceModal').classList.toggle('hidden');
    }

    function previousPage() {
        // Implement pagination logic if needed
    }

    function nextPage() {
        // Implement pagination logic if needed
    }
</script>
@endsection
