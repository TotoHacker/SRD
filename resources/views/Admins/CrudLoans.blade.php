@extends('Administrador')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">Gestión de Préstamos</h1>
        <button onclick="openModal('addLoanModal')" class="bg-teal-600 text-white px-4 py-2 rounded">Añadir Préstamo</button>
    </div>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2">ID</th>
                <th class="py-2">Recurso</th>
                <th class="py-2">Usuario</th>
                <th class="py-2">Estado</th>
                <th class="py-2">Fecha de Préstamo</th>
                <th class="py-2">Fecha de Devolución</th>
                <th class="py-2">Observaciones</th>
                <th class="py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
                <tr>
                    <td class="py-2">{{ $loan->id }}</td>
                    <td class="py-2">{{ $loan->resource->name }}</td>
                    <td class="py-2">{{ $loan->user->name }}</td>
                    <td class="py-2">{{ $loan->status_id }}</td>
                    <td class="py-2">{{ $loan->loan_date->format('Y-m-d') }}</td>
                    <td class="py-2">{{ $loan->return_date ? $loan->return_date->format('Y-m-d') : 'N/A' }}</td>
                    <td class="py-2">{{ $loan->Observation }}</td>
                    <td class="py-2">
                        <button onclick="openEditModal({{ $loan }})" class="bg-blue-500 text-white px-2 py-1 rounded">Editar</button>
                        <form action="{{ route('CrudLoan.destroy', $loan->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal for Adding Loan -->
<div id="addLoanModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-1/2">
        <h2 class="text-xl font-bold mb-4">Añadir Préstamo</h2>
        <form action="{{ route('CrudLoan.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="resource_id" class="block text-gray-700">Recurso</label>
                <select name="resource_id" id="resource_id" class="w-full border p-2 rounded">
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="user_id" class="block text-gray-700">Usuario</label>
                <select name="user_id" id="user_id" class="w-full border p-2 rounded">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="status_id" class="block text-gray-700">Estado</label>
                <input type="number" name="status_id" id="status_id" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="loan_date" class="block text-gray-700">Fecha de Préstamo</label>
                <input type="date" name="loan_date" id="loan_date" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="return_date" class="block text-gray-700">Fecha de Devolución</label>
                <input type="date" name="return_date" id="return_date" class="w-full border p-2 rounded">
            </div>
            <div class="mb-4">
                <label for="Observation" class="block text-gray-700">Observaciones</label>
                <textarea name="Observation" id="Observation" class="w-full border p-2 rounded"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('addLoanModal')" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancelar</button>
                <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Editing Loan -->
<div id="editLoanModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-1/2">
        <h2 class="text-xl font-bold mb-4">Editar Préstamo</h2>
        <form id="editLoanForm" action="#" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_resource_id" class="block text-gray-700">Recurso</label>
                <select name="resource_id" id="edit_resource_id" class="w-full border p-2 rounded">
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="edit_user_id" class="block text-gray-700">Usuario</label>
                <select name="user_id" id="edit_user_id" class="w-full border p-2 rounded">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="edit_status_id" class="block text-gray-700">Estado</label>
                <input type="number" name="status_id" id="edit_status_id" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="edit_loan_date" class="block text-gray-700">Fecha de Préstamo</label>
                <input type="date" name="loan_date" id="edit_loan_date" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="edit_return_date" class="block text-gray-700">Fecha de Devolución</label>
                <input type="date" name="return_date" id="edit_return_date" class="w-full border p-2 rounded">
            </div>
            <div class="mb-4">
                <label for="edit_Observation" class="block text-gray-700">Observaciones</label>
                <textarea name="Observation" id="edit_Observation" class="w-full border p-2 rounded"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('editLoanModal')" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancelar</button>
                <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

    function openEditLoanModal(element) {
        const id = element.getAttribute('data-id');
        const resourceId = element.getAttribute('data-resource');
        const userId = element.getAttribute('data-user');
        const loanDate = element.getAttribute('data-loan_date');
        const returnDate = element.getAttribute('data-return_date');
        const observation = element.getAttribute('data-observation');

        document.getElementById('editLoanId').value = id;
        document.getElementById('editResource').value = resourceId;
        document.getElementById('editUser').value = userId;
        document.getElementById('editLoanDate').value = loanDate;
        document.getElementById('editReturnDate').value = returnDate;
        document.getElementById('editObservation').value = observation;

        document.getElementById('editLoanForm').action = `/CrudLoans/${id}`;

        document.getElementById('editLoanModal').classList.remove('hidden');
    }

    function toggleEditLoanModal() {
        document.getElementById('editLoanModal').classList.toggle('hidden');
    }
</script>
@endsection
