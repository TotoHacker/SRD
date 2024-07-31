@extends('Administrador')

@section('content')
<div class="m-20">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Lista de Usuarios</h1>
        <a href="#" onclick="toggleAddUserModal()" class="bg-teal-600 hover:bg-teal-800 text-white font-bold py-2 px-4 rounded">Añadir Usuario</a>
    </div>

    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-teal-900 text-white">
            <tr>
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Correo Electrónico</th>
                <th class="px-4 py-3">Rol</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @foreach ($users as $user)
            <tr>
                <td class="border px-4 py-2">{{ $user->id }}</td>
                <td class="border px-4 py-2">{{ $user->NameUser }}</td>
                <td class="border px-4 py-2">{{ $user->email }}</td>
                <td class="border px-4 py-2">{{ $user->role }}</td>
                <td class="border px-4 py-2">
                    <a href="#" data-id="{{ $user->id }}" data-name="{{ $user->NameUser }}" data-email="{{ $user->email }}" data-role="{{ $user->role }}" onclick="openEditUserModal(this)" class="text-blue-500 hover:text-blue-700">Editar</a>
                    <form action="{{ route('CrudUser.destroy', $user->id) }}" method="POST" class="inline">
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

<!-- Modal para Añadir Usuario -->
<div id="addUserModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
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
                            Añadir Usuario
                        </h3>
                        <form method="POST" action="{{ route('CrudUser.store') }}">
                            @csrf
                            <!-- Campos del formulario -->
                            <div class="mb-4">
                                <label for="NameUser" class="block text-gray-700 font-bold mb-2">Nombre de usuario:</label>
                                <input type="text" name="NameUser" id="NameUser" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-bold mb-2">Correo electrónico:</label>
                                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña:</label>
                                <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="role" class="block text-gray-700 font-bold mb-2">Rol:</label>
                                <select name="role" id="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="user">Usuario</option>
                                    <option value="secretary">Secretaria</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                            <!-- Botones del formulario -->
                            <div class="mt-5 sm:mt-6">
                                <button type="button" onclick="toggleAddUserModal()"
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

<!-- Modal para Editar Usuario -->
<div id="editUserModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
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
                            Editar Usuario
                        </h3>
                        <form id="editUserForm" method="POST" action="#">
                            @csrf
                            @method('PUT')
                            <!-- Campos del formulario -->
                            <input type="hidden" name="id" id="editUserId">
                            <div class="mb-4">
                                <label for="editNameUser" class="block text-gray-700 font-bold mb-2">Nombre de usuario:</label>
                                <input type="text" name="NameUser" id="editNameUser" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="editEmail" class="block text-gray-700 font-bold mb-2">Correo electrónico:</label>
                                <input type="email" name="email" id="editEmail" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="mb-4">
                                <label for="editRole" class="block text-gray-700 font-bold mb-2">Rol:</label>
                                <select name="role" id="editRole" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="user">Usuario</option>
                                    <option value="secretary">Secretaria</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                            <!-- Botones del formulario -->
                            <div class="mt-5 sm:mt-6">
                                <button type="button" onclick="toggleEditUserModal()"
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
    function toggleAddUserModal() {
        const modal = document.getElementById('addUserModal');
        modal.classList.toggle('hidden');
    }

    function toggleEditUserModal() {
        const modal = document.getElementById('editUserModal');
        modal.classList.toggle('hidden');
    }

    function openEditUserModal(element) {
        const userId = element.getAttribute('data-id');
        const userName = element.getAttribute('data-name');
        const userEmail = element.getAttribute('data-email');
        const userRole = element.getAttribute('data-role');
        
        document.getElementById('editUserId').value = userId;
        document.getElementById('editNameUser').value = userName;
        document.getElementById('editEmail').value = userEmail;
        document.getElementById('editRole').value = userRole;
        
        const form = document.getElementById('editUserForm');
        form.action = `/CrudUser/${userId}`;

        toggleEditUserModal();
    }
</script>
@endsection
