<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')

    <style>
        .sidebar {
            transition: transform 0.3s ease;
            transform: translateX(-100%);
        }
        .sidebar.show {
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-gray-100">
    <button class="toggle-btn fixed left-4 top-4 bg-teal-600 text-white p-2 rounded z-10 hover:bg-teal-800" onclick="toggleSidebar()">☰</button>
    <header class="sidebar fixed left-0 top-0 h-full w-46 bg-teal-700 transform -translate-x-full transition-transform duration-300 ease-in-out" id="sidebar">
        <div class="flex flex-col items-center p-4 text-center">
            <div class="logo mb-4">
                <img src="{{ asset('Imagenes/Logo2.png') }}" alt="Logo" class="h-16"/>
            </div>
            <nav class="flex flex-col text-center items-center w-full my-7">
                <a href="Admin" class="text-white w-64 h-10 bg-teal-600 hover:bg-teal-800 flex items-center justify-center rounded-lg mb-2 p-4">
                    <span>Inicio</span>
                </a>
                <a href="CrudUser" class="text-white w-64 h-10 bg-teal-600 hover:bg-teal-800 flex items-center justify-center rounded-lg mb-2 p-4">
                    <span>Catálogo usuarios</span>
                </a>
                <a href="CrudResource" class="text-white w-64 h-10 bg-teal-600 hover:bg-teal-800 flex items-center justify-center rounded-lg mb-2 p-4">
                    <span>Catálogo recursos</span>
                </a>
                <a href="CrudLoans" class="text-white w-64 h-10 bg-teal-600 hover:bg-teal-800 flex items-center justify-center rounded-lg mb-2 p-4">
                    <span>Catálogo Prestamos</span>
                </a>
            </nav>
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="text-white text-center w-64 h-10 bg-teal-600 hover:bg-teal-800 flex items-center justify-center rounded-lg mb-2 p-4">
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
        <div class="m-5 my-20 ">
            
            <p class="text-white mt-4 text-center">&copy; 2024 UT Cancún. Todos los derechos reservados.</p>
        </div>
    </header>

    <main class="transition-all duration-300 ml-0" id="main-content">
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('ml-46'); // Agregar o quitar margen izquierdo
        }
    </script>
    
</body>

</html>
