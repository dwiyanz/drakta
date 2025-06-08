@extends('layouts.app')

@section('content')

{{-- Main container for the form with a clean, bright background and subtle shadow --}}
{{-- Using a very light background, a noticeable shadow, and rounded corners --}}
{{-- Changed background to a very light, cheerful green with a subtle gradient and increased shadow depth --}}
<div class="container mx-auto px-8 py-10 bg-gradient-to-r from-blue-100 via-teal-100 to-purple-100 p-8 rounded-2xl shadow-2xl mt-8 mb-8 max-w-3xl"> {{-- Light green to teal gradient background, larger rounded corners, deeper shadow, max width --}}

    {{-- Title of the form --}}
    {{-- Styled for prominence and better visual hierarchy with a soft dark color --}}
    {{-- Changed title color to a slightly brighter, modern shade of green --}}
    <h1 class="text-center text-4xl font-extrabold text-gray-800 mb-12 drop-shadow-sm">Edit Beranda</h1> {{-- Vibrant green title for readability on light background, added subtle text shadow --}}

    {{-- Success message --}}
    @if(session('success'))
        {{-- Styling for a success alert message with a light green background and vibrant text --}}
        {{-- Kept the success message green as it's standard for success, refined styling --}}
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-8 shadow-md" role="alert"> {{-- Light green background, rounded corners, increased margin-bottom, added medium shadow --}}
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- The form for editing Beranda details --}}
    {{-- Increased vertical space between form groups and added a subtle border --}}
    <form action="{{ route('admin.beranda.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6"> {{-- Added some spacing --}}
        @csrf
        @method('PUT')

        {{-- Input field for Gambar Utama --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="gambar_utama" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Utama</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- File input styling with bright green accent --}}
            {{-- File input styling updated for a more modern look with vibrant green colors and subtle hover effect --}}
            <input type="file" name="gambar_utama" id="gambar_utama" class="mt-1 block w-full text-sm text-gray-600
                file:mr-4 file:py-3 file:px-6
                file:rounded-full file:border-0
                file:text-sm file:font-bold
                file:bg-teal-200 file:text-teal-800 {{-- Bright teal background and text for file button --}}
                hover:file:bg-teal-300 {{-- Lighter teal on hover --}}
                transition duration-300 ease-in-out {{-- Added transition for hover effect --}}
                cursor-pointer
            ">
            @if($beranda->gambar_utama)
                {{-- Display current image with styling --}}
                {{-- Image preview styling updated with softer shadow --}}
                <img src="{{ asset($beranda->gambar_utama) }}" alt="Gambar Utama" class="mt-6 rounded-lg shadow-lg" style="max-width: 250px; height: auto;"> {{-- Styling image preview, slightly larger max-width, deeper shadow --}}
            @endif
        </div>

        {{-- Input field for Gambar Carousel 1 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="gambar_carousel1" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Carousel 1</label> {{-- Label text color (slightly darker gray for contrast) --}}
             {{-- File input styling updated for a more modern look with vibrant green colors and subtle hover effect --}}
            <input type="file" name="gambar_carousel1" id="gambar_carousel1" class="mt-1 block w-full text-sm text-gray-600
                file:mr-4 file:py-3 file:px-6
                file:rounded-full file:border-0
                file:text-sm file:font-bold
                file:bg-teal-200 file:text-teal-800 {{-- Bright teal background and text for file button --}}
                hover:file:bg-teal-300 {{-- Lighter teal on hover --}}
                transition duration-300 ease-in-out {{-- Added transition for hover effect --}}
                cursor-pointer
            ">
            @if($beranda->gambar_carousel1)
                <img src="{{ asset($beranda->gambar_carousel1) }}" alt="Carousel 1" class="mt-6 rounded-lg shadow-lg" style="max-width: 250px; height: auto;"> {{-- Styling image preview, slightly larger max-width, deeper shadow --}}
            @endif
        </div>

        {{-- Input field for Link Produk 1 --}}
        <div class="form-group">
            <label for="link1_g1" class="block text-sm font-semibold text-gray-700 mb-2">Link Produk 1</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Input styling updated with brighter focus color and more padding --}}
            <input type="text" name="link1_g1" id="link1_g1" value="{{ old('link1_g1', $beranda->link1_g1) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md"> {{-- Light gray border, green focus ring, increased padding, added transition and focus shadow --}}
        </div>

        {{-- Input field for Gambar Carousel 2 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="gambar_carousel2" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Carousel 2</label> {{-- Label text color (slightly darker gray for contrast) --}}
             {{-- File input styling updated for a more modern look with vibrant green colors and subtle hover effect --}}
            <input type="file" name="gambar_carousel2" id="gambar_carousel2" class="mt-1 block w-full text-sm text-gray-600
                file:mr-4 file:py-3 file:px-6
                file:rounded-full file:border-0
                file:text-sm file:font-bold
                file:bg-teal-200 file:text-teal-800 {{-- Bright teal background and text for file button --}}
                hover:file:bg-teal-300 {{-- Lighter teal on hover --}}
                transition duration-300 ease-in-out {{-- Added transition for hover effect --}}
                cursor-pointer
            ">
            @if($beranda->gambar_carousel2)
                 {{-- Display current image with styling --}}
                 {{-- Image preview styling updated with softer shadow --}}
                <img src="{{ asset($beranda->gambar_carousel2) }}" alt="Carousel 2" class="mt-6 rounded-lg shadow-lg" style="max-width: 250px; height: auto;"> {{-- Styling image preview, slightly larger max-width, deeper shadow --}}
            @endif
        </div>

        {{-- Input field for Link Produk 2 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="link1_g2" class="block text-sm font-semibold text-gray-700 mb-2">Link Produk 2</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Input styling updated with brighter focus color and more padding --}}
            <input type="text" name="link1_g2" id="link1_g2" value="{{ old('link1_g2', $beranda->link1_g2) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md"> {{-- Light gray border, green focus ring, increased padding, added transition and focus shadow --}}
        </div>

        {{-- Input field for Gambar Carousel 3 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="gambar_carousel3" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Carousel 3</label> {{-- Label text color (slightly darker gray for contrast) --}}
             {{-- File input styling updated for a more modern look with vibrant green colors and subtle hover effect --}}
            <input type="file" name="gambar_carousel3" id="gambar_carousel3" class="mt-1 block w-full text-sm text-gray-600
                file:mr-4 file:py-3 file:px-6
                file:rounded-full file:border-0
                file:text-sm file:font-bold
                file:bg-teal-200 file:text-teal-800 {{-- Bright teal background and text for file button --}}
                hover:file:bg-teal-300 {{-- Lighter teal on hover --}}
                transition duration-300 ease-in-out {{-- Added transition for hover effect --}}
                cursor-pointer
            ">
            @if($beranda->gambar_carousel3)
                 {{-- Display current image with styling --}}
                 {{-- Image preview styling updated with softer shadow --}}
                <img src="{{ asset($beranda->gambar_carousel3) }}" alt="Carousel 3" class="mt-6 rounded-lg shadow-lg" style="max-width: 250px; height: auto;"> {{-- Styling image preview, slightly larger max-width, deeper shadow --}}
            @endif
        </div>

        {{-- Input field for Link Produk 2 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="link1_g3" class="block text-sm font-semibold text-gray-700 mb-2">Link Produk 2</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Input styling updated with brighter focus color and more padding --}}
            <input type="text" name="link1_g3" id="link1_g3" value="{{ old('link1_g3', $beranda->link1_g3) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md"> {{-- Light gray border, green focus ring, increased padding, added transition and focus shadow --}}
        </div>

        {{-- Textarea for Section 2 - Text 1 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="sec2_text1" class="block text-sm font-semibold text-gray-700 mb-2">Section 2 - Text 1</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Textarea styling updated with brighter focus color and more padding --}}
            <textarea name="sec2_text1" id="sec2_text1" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md">{{ old('sec2_text1', $beranda->sec2_text1) }}</textarea> {{-- Light gray border, green focus ring, increased padding, rows set to 4, added transition and focus shadow --}}
        </div>

        {{-- Textarea for Section 2 - Text 2 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="sec2_text2" class="block text-sm font-semibold text-gray-700 mb-2">Section 2 - Text 2</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Textarea styling updated with brighter focus color and more padding --}}
            <textarea name="sec2_text2" id="sec2_text2" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md">{{ old('sec2_text2', $beranda->sec2_text2) }}</textarea> {{-- Light gray border, green focus ring, increased padding, rows set to 4, added transition and focus shadow --}}
        </div>

        {{-- Textarea for Section 2 - Text 3 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="sec2_text3" class="block text-sm font-semibold text-gray-700 mb-2">Section 2 - Text 3</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Textarea styling updated with brighter focus color and more padding --}}
            <textarea name="sec2_text3" id="sec2_text3" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md">{{ old('sec2_text3', $beranda->sec2_text3) }}</textarea> {{-- Light gray border, green focus ring, increased padding, rows set to 4, added transition and focus shadow --}}
        </div>

        {{-- Input field for Section 3 - Judul --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="sec3_judul" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Judul</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Input styling updated with brighter focus color and more padding --}}
            <input type="text" name="sec3_judul" id="sec3_judul" value="{{ old('sec3_judul', $beranda->sec3_judul) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md"> {{-- Light gray border, green focus ring, increased padding, added transition and focus shadow --}}
        </div>

        {{-- Textarea for Section 3 - Text 1 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="sec3_text1" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Text 1</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Textarea styling updated with brighter focus color and more padding --}}
            <textarea name="sec3_text1" id="sec3_text1" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md">{{ old('sec3_text1', $beranda->sec3_text1) }}</textarea> {{-- Light gray border, green focus ring, increased padding, rows set to 4, added transition and focus shadow --}}
        </div>

        {{-- Textarea for Section 3 - Text 2 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="sec3_text2" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Text 2</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Textarea styling updated with brighter focus color and more padding --}}
            <textarea name="sec3_text2" id="sec3_text2" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md">{{ old('sec3_text2', $beranda->sec3_text2) }}</textarea> {{-- Light gray border, green focus ring, increased padding, rows set to 4, added transition and focus shadow --}}
        </div>

        {{-- Textarea for Section 3 - Text 3 --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="sec3_text3" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Text 3</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Textarea styling updated with brighter focus color and more padding --}}
            <textarea name="sec3_text3" id="sec3_text3" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md">{{ old('sec3_text3', $beranda->sec3_text3) }}</textarea> {{-- Light gray border, green focus ring, increased padding, rows set to 4, added transition and focus shadow --}}
        </div>

        {{-- Textarea for Section 3 - Map --}}
        <div class="form-group">
            {{-- Label text color set to a slightly lighter gray --}}
            {{-- Label text color updated for better contrast on light background --}}
            <label for="sec3_map" class="block text-sm font-semibold text-gray-700 mb-2">Section 3 - Map</label> {{-- Label text color (slightly darker gray for contrast) --}}
            {{-- Textarea styling updated with brighter focus color and more padding --}}
            <textarea name="sec3_map" id="sec3_map" rows="6" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-4 transition duration-300 ease-in-out focus:shadow-md">{{ old('sec3_map', $beranda->sec3_map) }}</textarea> {{-- Light gray border, green focus ring, increased padding, rows set to 6, added transition and focus shadow --}}
        </div>

        {{-- Submit Button for saving changes --}}
        <div>
            {{-- Button styling with bright green background and shadow --}}
            {{-- Button styling updated with a vibrant green background and deeper shadow, and refined hover effect --}}
            <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-xl text-lg font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl"> {{-- Vibrant green background, white text, deeper shadow, larger rounded corners, hover scale and deeper shadow effect --}}
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection
