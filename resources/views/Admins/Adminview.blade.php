@extends('Administrador')

@section('content')
<div class="m-20">
    <div class="flex justify-center items-center h-full w-full">
        <!-- Gráfica de total de usuarios -->
        <div class="flex flex-col justify-center items-center w-2/3 h-64 bg-gray-100 shadow-lg p-10 rounded-xl m-6">
            <h1 class="text-xl mb-5">Total de usuarios</h1>
            <div class="flex justify-center items-center w-full h-full">
                <canvas id="userChart" width="300" height="300"></canvas>
            </div>
        </div>

        <!-- Gráfica de recursos -->
        <div class="flex flex-col justify-center items-center w-2/3 h-64 bg-gray-100 shadow-lg p-10 rounded-xl m-6">
            <h1 class="text-xl mb-5">Recursos</h1>
            <div class="flex justify-center items-center w-full h-full">
                <canvas id="resourceChart" width="300" height="300"></canvas>
            </div>
        </div>

        <!-- Gráfica de préstamos -->
        <div class="flex flex-col justify-center items-center w-2/3 h-64 bg-gray-100 shadow-lg p-10 rounded-xl m-6">
            <h1 class="text-xl mb-5">Total de préstamos</h1>
            <div class="flex justify-center items-center w-full h-full">
                <canvas id="loanChart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Resumen de estadísticas -->
    <div class="mt-10">
        <h2 class="text-2xl mb-5">Resumen de estadísticas</h2>
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl mb-3">Usuarios activos</h3>
                <p class="text-4xl font-bold">{{ $ActivateUser }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl mb-3">Recursos disponibles</h3>
                <p class="text-4xl font-bold">{{ array_sum(array_column($resources, 'total')) }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl mb-3">Préstamos pendientes</h3>
                <p class="text-4xl font-bold">{{ $pendingLoans }}</p>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="mt-10">
        <h2 class="text-2xl mb-5">Acciones rápidas</h2>
        <div class="grid grid-cols-2 gap-6">
            <a href="/CrudUser" class="bg-teal-600 hover:bg-teal-900 text-white font-bold py-2 px-4 rounded">Agregar usuario</a>
            <a href="/CrudResource" class="bg-teal-600 hover:bg-teal-900 text-white font-bold py-2 px-4 rounded">Agregar recurso</a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    // Datos de ejemplo para la gráfica de total de usuarios
    var userChartData = {
        labels: ['Usuarios activos', 'Usuarios inactivos'],
        datasets: [{
            data: [{{ $ActivateUser }}, {{ $totalUser - $ActivateUser }}],
            backgroundColor: ['#005954', '#338b85']
        }]
    };

    // Datos dinámicos para la gráfica de recursos
    var resourceLabels = @json(array_keys($resources));
    var resourceData = @json(array_column($resources, 'total'));

    var resourceChartData = {
        labels: resourceLabels,
        datasets: [{
            data: resourceData,
            backgroundColor: ['#ffcd56', '#ff6384', '#36a2eb']
        }]
    };

    // Datos dinámicos para la gráfica de préstamos
    var loanLabels = ['Aprobados', 'Pendientes', 'Rechazados'];
    var loanData = [{{ $loans[1]['total'] ?? 0 }}, {{ $loans[2]['total'] ?? 0 }}, {{ $loans[3]['total'] ?? 0 }}]; // Suponiendo 1, 2 y 3 son IDs para 'Aprobados', 'Pendientes' y 'Rechazados'

    var loanChartData = {
        labels: loanLabels,
        datasets: [{
            data: loanData,
            backgroundColor: ['#005954', '#338b85', '#5dc1b9']
        }]
    };

    // Configuración de las gráficas
    var chartOptions = {
        responsive: false,
        plugins: {
            legend: {
                position: 'right', // Muestra la leyenda a la derecha de la gráfica
            }
        }
    };

    // Selecciona el elemento canvas donde se renderizará cada gráfica
    var userCtx = document.getElementById('userChart').getContext('2d');
    var resourceCtx = document.getElementById('resourceChart').getContext('2d');
    var loanCtx = document.getElementById('loanChart').getContext('2d');

    // Crea las instancias de las gráficas
    var userChart = new Chart(userCtx, {
        type: 'pie',
        data: userChartData,
        options: chartOptions
    });

    var resourceChart = new Chart(resourceCtx, {
        type: 'pie',
        data: resourceChartData,
        options: chartOptions
    });

    var loanChart = new Chart(loanCtx, {
        type: 'pie',
        data: loanChartData,
        options: chartOptions
    });
</script>
@endsection
