@extends('layouts.app') {{-- Sesuaikan dengan layout Anda --}}

@section('content')

<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Modern Alert Section (Akan ditampilkan di kedua mode: daftar dan edit) --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 shadow-md" role="alert">
        <strong class="font-bold">Sukses!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15L6.342 7.344a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
    </div>
    @endif
    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 shadow-md" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
         <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15L6.342 7.344a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
    </div>
    @endif
    {{-- End Modern Alert Section --}}


    {{-- Logika Kondisional untuk Menampilkan Form Edit atau Tabel Daftar --}}
    {{-- Cek apakah variabel $user ada (berarti sedang dalam mode edit) --}}
    @if(isset($user) && $user)
        {{-- TAMPILAN FORM EDIT USER --}}
        {{-- Main container for the form with a clean, bright background and subtle shadow --}}
        {{-- Using a very light background with a subtle gradient and increased shadow depth --}}
        <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-3xl mx-auto">

            {{-- Title of the form --}}
            <h1 class="text-center text-3xl font-extrabold text-gray-800 mb-8 drop-shadow-sm">Edit Pengguna</h1>

            {{-- The form for editing user details --}}
            <form action="{{ route('admin.users-update', $user->id) }}" method="post" class="space-y-6 border-t border-gray-200 pt-6">
                @csrf
                @method('PUT')

                {{-- Select field for Role --}}
                <div class="form-group">
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                    <select name="role" id="role" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" required>
                        {{-- Mengganti value="user" menjadi value="pelanggan" jika role pelanggan disimpan sebagai 'pelanggan' --}}
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pelanggan" {{ old('role', $user->role) === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                    </select>
                     @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input field for Full Name --}}
                <div class="form-group">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" required>
                     @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input field for Username (Tambahkan field username jika ada di model User) --}}
                 @if(isset($user->username)) {{-- Cek apakah field username ada di model --}}
                 <div class="form-group">
                     <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                     <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" required>
                      @error('username')
                         <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                     @enderror
                 </div>
                 @endif


                {{-- Textarea for Address --}}
                <div class="form-group">
                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md">{{ old('alamat', $user->alamat) }}</textarea>
                     @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input field for Email --}}
                <div class="form-group">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" value="{{ old('email', $user->email) }}" required>
                     @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input field for Phone Number --}}
                <div class="form-group">
                    <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                    <input type="text" name="telepon" id="telepon" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md" value="{{ old('telepon', $user->telepon) }}" required>
                     @error('telepon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-center space-x-4 mt-8">
                    {{-- Submit Button for saving changes --}}
                    <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-xl text-lg font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl">
                        Simpan Perubahan
                    </button>
                    {{-- Cancel Button (Link) --}}
                    <a href="{{ route('admin.users-index') }}" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-lg text-lg font-bold rounded-xl text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-xl">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    @else
        {{-- TAMPILAN TABEL DAFTAR USER --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Manajemen Pengguna</h1>

        {{-- Contoh Struktur Tabel User (sesuaikan dengan kode Anda) --}}
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                             <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'admin')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Admin</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Pelanggan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.users-edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                {{-- Tombol Hapus yang akan memicu modal konfirmasi --}}
                                <button onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')" class="text-red-600 hover:text-red-900">Hapus</button>
                                {{-- Form untuk proses hapus (disembunyikan) --}}
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users-destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination (sesuaikan dengan kode Anda) --}}
        @if (isset($users) && $users instanceof \Illuminate\Pagination\AbstractPaginator && $users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif

        {{-- Modal Konfirmasi Hapus (Modern) --}}
        <div id="deleteConfirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden transition-opacity duration-300 ease-in-out">
            <div class="relative top-1/4 mx-auto p-6 border w-full max-w-md shadow-2xl rounded-xl bg-white transform transition-all duration-300 ease-in-out scale-95" id="deleteModalDialog">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-xl leading-6 font-semibold text-gray-900">Hapus Pengguna</h3>
                    <div class="mt-3 px-7 py-3">
                        <p class="text-sm text-gray-600">
                            Apakah Anda yakin ingin menghapus pengguna "<strong id="userNameToDelete" class="font-medium"></strong>"? Tindakan ini tidak dapat diurungkan.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button id="cancelDeleteButton" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors duration-150 font-medium">
                            Batal
                        </button>
                        <button id="confirmDeleteButtonModal" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-150 font-medium">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

    @endif {{-- End of conditional check for $user (edit mode) --}}

</div> {{-- End of main container --}}

@endsection

@push('scripts')
{{-- Script untuk Modal Konfirmasi Hapus (Hanya relevan untuk tampilan daftar) --}}
{{-- Pastikan script ini hanya dijalankan jika ada elemen dengan ID deleteConfirmationModal --}}
<script>
    if (document.getElementById('deleteConfirmationModal')) {
        function confirmDelete(userId, userName) {
            const modal = document.getElementById('deleteConfirmationModal');
            const modalDialog = document.getElementById('deleteModalDialog');
            document.getElementById('userNameToDelete').textContent = userName;

            modal.classList.remove('hidden');
            // Trigger reflow to enable transition
            modal.offsetHeight;
            modalDialog.classList.remove('scale-95');
            modalDialog.classList.add('scale-100');


            const confirmButton = document.getElementById('confirmDeleteButtonModal');
            const cancelButton = document.getElementById('cancelDeleteButton');

            // Remove previous event listeners to avoid multiple submissions
            const newConfirmButton = confirmButton.cloneNode(true);
            confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);

            const newCancelButton = cancelButton.cloneNode(true);
            cancelButton.parentNode.replaceChild(newCancelButton, cancelButton);


            newConfirmButton.addEventListener('click', function() {
                document.getElementById('delete-form-' + userId).submit();
                closeDeleteModal();
            });

            newCancelButton.addEventListener('click', function() {
                closeDeleteModal();
            });

            // Close with escape key
            document.addEventListener('keydown', handleEscapeKey);
             // Prevent scrolling on body when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteConfirmationModal');
            const modalDialog = document.getElementById('deleteModalDialog');
            modalDialog.classList.remove('scale-100');
            modalDialog.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                 // Restore scrolling on body
                document.body.style.overflow = '';
            }, 300); // Match transition duration
            document.removeEventListener('keydown', handleEscapeKey);
        }

        function handleEscapeKey(event) {
            if (event.key === "Escape") {
                closeDeleteModal();
            }
        }

         // Close modal when clicking outside the modal content
        const modal = document.getElementById('deleteConfirmationModal');
        modal.addEventListener('click', function(event) {
            const modalContent = modal.querySelector('.relative'); // Ambil elemen konten modal
            // Cek apakah klik dilakukan di luar area konten modal
            if (!modalContent.contains(event.target)) {
                closeDeleteModal();
            }
        });
    }
</script>
@endpush
