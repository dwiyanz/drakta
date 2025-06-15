@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-6 text-center border-b border-gray-200">Manajemen Pengguna</h1>

    @if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm mb-6" role="alert">
        <p class="font-bold">Berhasil!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-xl shadow-md">
        <!-- Show entries and role filter container -->
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <!-- Show entries dropdown -->
            <div class="flex items-center w-full sm:w-auto">
                <label for="entries" class="text-gray-700 mr-2 font-medium">Tampilkan:</label>
                <select id="entries" class="block w-full sm:w-auto px-4 py-2 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                    @foreach([10, 25, 50, 100] as $pageSize)
                    <option value="{{ $pageSize }}" {{ request()->input('entries') == $pageSize ? 'selected' : '' }}>
                        {{ $pageSize }}
                    </option>
                    @endforeach
                </select>
                <span class="ml-2 text-gray-700">entri</span>
            </div>

            <!-- Role filter dropdown -->
            <div class="flex items-center w-full sm:w-auto">
                <label for="role_filter" class="text-gray-700 mr-2 font-medium">Filter Role:</label>
                <select id="role_filter" class="block w-full sm:w-auto px-4 py-2 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                    <option value="all" {{ request()->input('role_filter') == 'all' ? 'selected' : '' }}>Semua Role</option>
                    <option value="admin" {{ request()->input('role_filter') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pelanggan" {{ request()->input('role_filter') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                </select>
            </div>
        </div>

        <!-- Search form -->
        <form action="{{ route('admin.users-index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="hidden" name="entries" value="{{ request()->input('entries', 10) }}">
            <input type="hidden" name="role_filter" value="{{ request()->input('role_filter', 'all') }}">
            <input type="text" name="search" value="{{ request()->input('search') }}"
                class="flex-grow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 shadow-sm"
                placeholder="Cari pengguna...">
            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-150 ease-in-out shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Cari
            </button>
        </form>
    </div>

    <div class="relative overflow-x-auto shadow-xl rounded-lg border border-gray-200">
        <table class="w-full text-sm text-left text-gray-800">
            <thead class="text-xs text-gray-800 uppercase bg-white">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                    <th scope="col" class="px-6 py-3">Username</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Telepon</th>
                    <th scope="col" class="px-6 py-3">Alamat</th>
                    <th scope="col" class="px-6 py-3">Role</th>
                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr class="bg-white border-b hover:bg-gray-50 transition duration-150 ease-in-out
                           {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $user->id }}
                    </td>
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->username }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">{{ $user->telepon }}</td>
                    <td class="px-6 py-4">{{ $user->alamat }}</td>
                    <td class="px-6 py-4 capitalize">{{ $user->role }}</td>
                    <td class="px-6 py-4 flex flex-col sm:flex-row items-center justify-center gap-2">
                        <a href="{{ route('admin.users-edit', $user->id) }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-300 transition duration-150 ease-in-out shadow-sm w-full sm:w-auto justify-center">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-7l4 4m-4-4l4 4" />
                           </svg>
                           Edit
                        </a>
                        <form action="{{ route('admin.users-destroy', $user->id) }}" method="POST" class="inline-block w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 transition duration-150 ease-in-out shadow-sm w-full sm:w-auto justify-center">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                               </svg>
                               Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-6 text-center text-gray-500 italic bg-white">Tidak ada data pengguna yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $users->links() }} {{-- Asumsi kamu menggunakan pagination view tailwind --}}
    </div>
</div>

<script>
    // Handle entries dropdown change
    document.getElementById('entries').addEventListener('change', function() {
        updateFilters();
    });

    // Handle role filter dropdown change
    document.getElementById('role_filter').addEventListener('change', function() {
        updateFilters();
    });

    function updateFilters() {
        const currentUrl = new URL(window.location.href);
        const entries = document.getElementById('entries').value;
        const roleFilter = document.getElementById('role_filter').value;

        currentUrl.searchParams.set('entries', entries);
        currentUrl.searchParams.set('role_filter', roleFilter);
        currentUrl.searchParams.delete('page'); // Reset page to 1 when filters change

        window.location.href = currentUrl.toString();
    }
</script>
@endsection
