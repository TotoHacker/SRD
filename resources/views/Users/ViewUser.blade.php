@extends('template')

@section('content')
<div class="px-4 md:px-10">
    <div class="flex flex-col md:flex-row justify-center items-center">
        <!-- Mis préstamos -->
        <div class="w-full md:w-2/3 h-auto bg-gray-100 m-4 shadow-lg text-center p-5 rounded-xl">
            <h1 class="text-xl mb-5 font-bold">Ultimos 5 prestamos</h1>
            <div class="max-w-screen-md mx-auto">
                @php
                    $recentLoans = $loans->take(4); // Obtener solo los últimos 4 préstamos
                @endphp
                @foreach ($recentLoans as $loan)
                    <div class="flex flex-col md:flex-row text-base justify-between my-5 text-center">
                        <p class="flex-1 truncate">{{ $loan->resource_name }}</p>
                        <p class="flex-1 truncate">{{ \Carbon\Carbon::parse($loan->loan_date)->format('H:i') }} - {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('H:i') : 'No devuelto' }}</p>
                        <p class="flex-1 truncate
                            @if ($loan->status_name === 'Aceptado')
                                text-green-500
                            @elseif ($loan->status_name === 'Rechazado')
                                text-red-800
                            @elseif ($loan->status_name === 'Vencido')
                                text-red-600
                            @else
                                text-orange-600
                            @endif
                            ">
                            {{ ucfirst($loan->status_name) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- En proceso -->
        <div class="w-full md:w-1/3 bg-gray-100 shadow-lg text-center p-10 rounded-xl m-4">
            <h1 class="text-xl mb-5 font-bold">En proceso</h1>
            <h1 class="text-7xl mb-5">{{ $pendingCount }}</h1>
            <a href="{{ url('/Loans') }}" class="text-white text-base border w-24 h-10 bg-green-500 rounded-2xl hover:bg-green-700 inline-flex justify-center items-center">
                Ver más
            </a>
        </div>
    </div>

    <!-- Próximos préstamos -->
    <div class="w-full h-64 bg-gray-100 mx-4 my-4 shadow-lg text-center py-3 px-5 md:px-10 rounded-2xl">
        <div class="flex justify-center">
            <h1 class="text-xl mx-4 md:mx-40 font-bold">Próximos préstamos</h1>
        </div>
        <div class="flex justify-end px-2 md:px-12">
            <h1 class="text-base mx-2 md:mx-20">Enterado - Cancelar</h1>
        </div>

        @foreach ($upcomingLoans as $loan)
            <div class="flex flex-col md:flex-row text-lg justify-between my-4 items-center">
                <p class="flex-1 truncate">{{ $loan->resource_name }}</p>
                <p class="flex-1 text-center truncate">{{ \Carbon\Carbon::parse($loan->loan_date)->format('H:i') }} - {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('H:i') : 'No devuelto' }}</p>
                <div class="flex-1 flex justify-center items-center">
                    <a href="#" class="mx-2 md:mx-4">
                        <img src="{{ asset('Imagenes/Check.png') }}" alt="Check" class="w-7">
                    </a>
                    <a href="#">
                        <img src="{{ asset('Imagenes/equis.png') }}" alt="Cancel" class="w-7">
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
