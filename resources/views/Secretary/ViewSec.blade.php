@extends('template')

@section('content')
<div class="px-4 sm:px-10 h-screen">
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start">
        <!-- Total de préstamos -->
        <div class="flex flex-col justify-center items-center w-full lg:w-2/3 h-64 bg-gray-100 shadow-lg p-5 lg:p-10 rounded-xl m-4 lg:m-6">
            <h1 class="text-xl mb-5">Total de préstamos</h1>
            <div class="flex justify-center items-center w-full h-full">
                <canvas id="pieChart" width="300" height="300"></canvas>
            </div>
        </div>

        <!-- Conteos de préstamos -->
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start w-full lg:w-1/3">
            <!-- En proceso -->
            <div class="w-full lg:w-1/2 h-64 bg-gray-100 shadow-lg text-center p-5 lg:p-10 rounded-xl m-4 lg:m-6">
                <h1 class="text-xl mb-5">En proceso</h1>
                <h1 class="text-4xl lg:text-7xl mb-5">{{ $inProcessCount }}</h1>
                <a href="/LoansApproved" class="relative text-white text-base border w-full lg:w-24 h-10 flex p-2 px-4 bg-green-500 rounded-2xl hover:bg-green-700">
                    Ver más
                </a>
            </div>

            <!-- Préstamos aprobados -->
            <div class="w-full lg:w-1/2 h-64 bg-gray-100 shadow-lg text-center p-5 lg:p-10 rounded-xl m-4 lg:m-6">
                <h1 class="text-xl mb-5">Préstamos aprobados</h1>
                <h1 class="text-4xl lg:text-7xl mb-5">{{ $approvedCount }}</h1>
                <a href="/LoanSec" class="relative text-white text-base border w-full lg:w-24 h-10 flex p-2 px-4 bg-green-500 rounded-2xl hover:bg-green-700">
                    Ver más
                </a>
            </div>
        </div>
    </div>

    <!-- Préstamos a entregar -->
    <div class="w-full h-auto bg-gray-100 mx-4 my-4 shadow-lg text-center py-3 px-4 sm:px-10 rounded-2xl">
        <div class="flex justify-center">
            <h1 class="text-xl mx-4 sm:mx-40">Préstamos a entregar</h1>
        </div>
        <div class="flex justify-end px-4 sm:px-16">
            <h1 class="text-base mx-2 sm:mx-20">¿Entregado? </h1>
        </div>

        @forelse ($upcomingDeliveries as $delivery)
        <div class="flex flex-col sm:flex-row text-lg justify-center my-4 items-center">
            <p class="flex-1">{{ $delivery->resource_name }}</p>
            <p class="flex-1 text-center">{{ $delivery->loan_date->format('H:i') }} - {{ $delivery->return_date->format('H:i') }}</p>
            <div class="flex-1 flex justify-center items-center">
                <a href="#" onclick="openModal(1, {{ $delivery->id }})" class="mx-2 sm:mx-4">
                    <img src="{{ asset('Imagenes/Check.png') }}" alt="Check" class="w-6 sm:w-7"/>
                </a>
                <a href="#" onclick="openModal(0, {{ $delivery->id }})">
                    <img src="{{ asset('Imagenes/equis.png') }}" alt="Cancel" class="w-6 sm:w-7"/>
                </a>
            </div>
        </div>
        @empty
        <p class="text-lg text-gray-500">No hay préstamos próximos a entregar.</p>
        @endforelse
    </div>
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
                    @method('PATCH')
                    <input type="hidden" name="status_id" id="modalStatusId">
                    <input type="hidden" name="loan_id" id="modalLoanId">
                    <input type="hidden" name="new_status" id="modalNewStatus">
                    <div class="space-y-4">
                        <textarea name="observationSec" id="observationSec" rows="3" placeholder="Añadir Observacion" class="block p-3 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden"></textarea>
                        <p class="text-xs text-gray-500 hidden" id="observationSecMessage">* La observación es obligatoria para rechazar.</p>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="button" onclick="closeModal()" class="py-2 px-4 mr-2 inline-flex justify-center items-center gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-gray-600 hover:bg-gray-500 focus:bg-gray-700 focus:ring-offset-gray-700">
                            Cancelar
                        </button>
                        <button type="submit" class="py-2 px-4 inline-flex justify-center items-center gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-green-600 hover:bg-green-500 focus:bg-green-700 focus:ring-offset-green-700">
                            Confirmar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(statusId, loanId, newStatus) {
        const modal = document.getElementById('confirmationModal');
        const modalForm = document.getElementById('modalForm');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const observationSec = document.getElementById('observationSec');
        const observationSecMessage = document.getElementById('observationSecMessage');
        const statusInput = document.getElementById('modalStatusId');
        const loanInput = document.getElementById('modalLoanId');
        const newStatusInput = document.getElementById('modalNewStatus');
        const modalContent = modal.querySelector('.bg-white');

        statusInput.value = statusId;
        loanInput.value = loanId;
        newStatusInput.value = newStatus;

        if (newStatus === 'devoted') {
            modalTitle.innerText = 'Confirmar Entrega';
            modalMessage.innerText = '¿Estás seguro que deseas marcar este préstamo como entregado?';
            observationSec.classList.add('hidden');
            observationSecMessage.classList.add('hidden');
        } else if (newStatus === 'cancel') {
            modalTitle.innerText = 'Confirmar Cancelación';
            modalMessage.innerText = '¿Estás seguro que deseas rechazar este préstamo?';
            observationSec.classList.remove('hidden');
            observationSecMessage.classList.remove('hidden');
        } else if (newStatus === 'pending') {
            modalTitle.innerText = 'Confirmar Estado Pendiente';
            modalMessage.innerText = '¿Estás seguro que deseas marcar este préstamo como pendiente?';
            observationSec.classList.add('hidden');
            observationSecMessage.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 50);
    }

    function closeModal() {
        const modal = document.getElementById('confirmationModal');
        const modalContent = modal.querySelector('.bg-white');

        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    // Datos para la gráfica de pastel
    var pieChartData = {
        labels: ['Sala Audio Visual', 'Laboratorio', 'Otros'],
        datasets: [{
            data: [{{ $graphData['Sala Audio Visual'] }}, {{ $graphData['Laboratorio'] }}, {{ $graphData['Otros'] }}], // Datos dinámicos desde el controlador
            backgroundColor: ['#005954', '#338b85', '#5dc1b9']
        }]
    };

    // Configuración de la gráfica
    var pieChartOptions = {
        responsive: false,
        plugins: {
            legend: {
                position: 'right', // Muestra la leyenda a la derecha de la gráfica
            }
        }
    };

    // Selecciona el elemento canvas donde se renderizará la gráfica
    var ctx = document.getElementById('pieChart').getContext('2d');

    // Crea la instancia de la gráfica de pastel
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: pieChartData,
        options: pieChartOptions
    });

</script>
@endsection
