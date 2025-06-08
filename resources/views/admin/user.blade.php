@extends('layouts.app')

@section('content')
<div class="w-full mx-auto mt-4">
    <h1 class="text-2xl font-bold mb-4 text-black m-center">Manajemen User</h1>

    @if (session('success'))
    <div class="alert alert-success bg-green-100 text-green-800 px-4 py-2 rounded-md mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Show entries and role filter container -->
        <div class="flex items-center gap-4">
            <!-- Show entries dropdown -->
            <div class="flex items-center">
                <label class="text-black mr-2">Show entries:</label>
                <select id="entries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 p-2.5">
                    @foreach([10, 25, 50, 100] as $pageSize)
                    <option value="{{ $pageSize }}" {{ request()->input('entries') == $pageSize ? 'selected' : '' }}>
                        {{ $pageSize }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Role filter dropdown -->
            <div class="flex items-center">
                <label class="text-black mr-2">Filter Role:</label>
                <select id="role_filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 p-2.5">
                    <option value="all" {{ request()->input('role_filter') == 'all' ? 'selected' : '' }}>Semua Role</option>
                    <option value="admin" {{ request()->input('role_filter') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pelanggan" {{ request()->input('role_filter') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                </select>
            </div>
        </div>

        <!-- Search form -->
        <form action="{{ route('admin.users-index') }}" method="GET" class="flex gap-2">
            <input type="hidden" name="entries" value="{{ request()->input('entries', 10) }}">
            <input type="hidden" name="role_filter" value="{{ request()->input('role_filter', 'all') }}">
            <input type="text" name="search" value="{{ request()->input('search') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block p-2.5"
                placeholder="Cari user...">
            <button type="submit" class="text-white bg-teal-700 hover:bg-teal-800 focus:ring-4 focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Cari
            </button>
        </form>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-white">
            <thead class="text-xs text-white uppercase bg-teal-600">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                    <th scope="col" class="px-6 py-3">Username</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Telepon</th>
                    <th scope="col" class="px-6 py-3">Alamat</th>
                    <th scope="col" class="px-6 py-3">Role</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr class="odd:bg-teal-200 text-black even:bg-teal-100 border-b dark:border-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $user->id }}
                    </td>
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->username }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">{{ $user->telepon }}</td>
                    <td class="px-6 py-4">{{ $user->alamat }}</td>
                    <td class="px-6 py-4">{{ ucfirst($user->role) }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.users-edit', $user->id) }}" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">Edit</a>
                        {{-- PERBAIKAN DI SINI: Mengganti route('user-destroy') menjadi route('admin.users.destroy') --}}
                        <form action="{{ route('admin.users-destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="return confirm('Hapus user ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
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

        window.location.href = currentUrl.toString();
    }
</script>
@endsection