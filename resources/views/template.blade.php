<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css') 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/sweetalert2.min.css">
    <!-- Otros estilos que necesites cargar -->

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Otros scripts que necesites cargar -->

    <!-- Estilos en línea o personalizados -->
    <style>
        /* Estilos personalizados aquí si es necesario */
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-teal-600 p-2">
        <div class="container mx-auto flex justify-between items-center flex-wrap">
            <div class="logo">
                <img src="{{ asset('Imagenes/Logo2.png') }}" alt="Logo" class="h-16"/>
            </div>
            <nav class="flex space-x-4">
                @guest <!-- Mostrar solo cuando no hay sesión iniciada -->
                <a href="login" class="text-white w-auto h-10 bg-teal-500 hover:bg-teal-700 flex items-center space-x-1 rounded-3xl p-4">
                    <span>Iniciar sesión</span>
                </a>
                @else <!-- Mostrar cuando hay sesión iniciada -->
                    @if(Auth::user()->role === 'user') <!-- Mostrar cuando el rol del usuario es 'user' -->
                        <a href="/" class="text-white flex items-center space-x-1">
                            <span>Inicio</span>
                        </a>
                        <a href="{{ route('Loans.index') }}" class="text-white flex items-center space-x-1">
                            <span>Mis préstamos</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="text-white w-auto h-10 bg-teal-500 hover:bg-teal-700 flex items-center space-x-1 rounded-3xl p-4">
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    @elseif(Auth::user()->role === 'secretary') <!-- Mostrar cuando el rol del usuario es 'secretary' -->
                        <a href="/Secretary" class="text-white flex items-center space-x-1">
                            <span>Inicio</span>
                        </a>
                        <a href="{{ route('LoanSec.index') }}" class="text-white flex items-center space-x-1">
                            <span>Prestamos</span>
                        </a>
                        <a href="{{ route('LoansApproved.index') }}" class="text-white flex items-center space-x-1">
                            <span>Prestamos a aprobar</span>
                        </a>
                        <a href="/resources" class="text-white flex items-center space-x-1">
                            <span>Recursos</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="text-white w-auto h-10 bg-teal-500 hover:bg-teal-700 flex items-center space-x-1 rounded-3xl p-4">
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    @endif
                @endguest
            </nav>
        </div>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="bg-teal-600 p-4 mt-8 text-center text-white">
        <p>&copy; 2024 UT Cancún. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
