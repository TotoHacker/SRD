@extends('template')

@section('content')
<img src="{{ asset('Imagenes/FondoIS.jpg') }}" class="absolute top-0 z-[-1] w-full h-screen opacity-85" alt="Background Image"/>
<!--Inicio de sesión-->
<div class="h-[75vh] flex items-center justify-center px-4 md:px-0">
    <div class="relative left-1/3 bg-white w-full max-w-md md:w-96 md:max-w-lg h-auto md:h-[70vh] flex flex-col items-center rounded-xl shadow-lg px-4 md:px-16 pt-8">
        <img src="{{asset('Imagenes/Logo.png')}}" alt="Logo" class="w-32 md:w-44 mb-6">
        <form method="POST" action="{{ route('login.store') }}" class="w-full flex flex-col space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Nombre de usuario:</label>
                <input type="email" placeholder="2192392@utcancun.edu.mx" id="username" name="username" class="bg-gray-300 shadow appearance-none border rounded-2xl w-full h-11 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" placeholder="123Ut" id="password" name="password" class="bg-gray-300 h-11 shadow appearance-none border rounded-2xl w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="text-center">
                
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-3xl focus:outline-none focus:shadow-outline mt-4" type="submit">
                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
