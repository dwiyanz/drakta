@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10 text-white">
    <!-- Kartu Profil -->
    <div class="bg-gray-500 p-6 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-orange-500 text-white font-bold rounded-full w-14 h-14 flex items-center justify-center text-xl shadow">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm text-gray-300">Hello 👋</p>
                    <h2 class="text-2xl font-semibold text-white">{{ Auth::user()->name }}</h2>
                </div>
            </div>
            <button id="toggleEdit" class="text-gray-300 hover:text-white transition">
                ⚙️
            </button>
        </div>
        <div class="mt-4 text-sm">
            <p class="flex items-center gap-2">
                <i class="fas fa-phone-alt text-yellow-400"></i>
                {{ Auth::user()->telepon }}
            </p>
            <p class="mt-2 font-medium">
                {{ Auth::user()->jenis_rekening ?? '-' }}:
                <span class="text-gray-300">{{ Auth::user()->no_rekening ?? '-' }}</span>
            </p>
        </div>
    </div>

    <!-- Kartu Saldo -->
    <div class="bg-gray-500 p-6 rounded-2xl shadow-lg flex flex-col items-center justify-center text-center">
        <h3 class="text-sm font-medium text-white">Saldo Saya</h3>
        <p class="text-4xl font-bold text-white mt-2">{{ number_format(Auth::user()->saldo, 0, ',', '.') }} IDR</p>
    </div>
</div>

<!-- Form Edit Profil (Toggle) -->
<div id="editSection" class="hidden mt-6 bg-gray-100 p-6 rounded-lg shadow-md">
    <h1 class="text-xl font-bold mb-4 text-gray-800">Edit Profil</h1>
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
            <input type="text" class="mt-1 w-full border border-gray-300 bg-white rounded-lg shadow-sm p-2" 
                   value="{{ Auth::user()->name }}" readonly>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700">Username</label>
            <input type="text" class="mt-1 w-full border border-gray-300 bg-white rounded-lg shadow-sm p-2" 
                   value="{{ Auth::user()->username }}" readonly>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700">Email</label>
            <input type="email" class="mt-1 w-full border border-gray-300 bg-white rounded-lg shadow-sm p-2" 
                   value="{{ Auth::user()->email }}" readonly>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700">Telepon</label>
            <input type="text" class="mt-1 w-full border border-gray-300 bg-white rounded-lg shadow-sm p-2" 
                   value="{{ Auth::user()->telepon }}" readonly>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700">Alamat</label>
            <input type="text" class="mt-1 w-full border border-gray-300 bg-white rounded-lg shadow-sm p-2" 
                   value="{{ Auth::user()->alamat }}" readonly>
        </div>
    </div>
    <div class="mt-6">
        <a href="{{ route('profil.edit') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow transition duration-200">
            Edit Profil
        </a>
    </div>
</div>

<script>
    document.getElementById('toggleEdit').addEventListener('click', function() {
        document.getElementById('editSection').classList.toggle('hidden');
    });
</script>
@endsection