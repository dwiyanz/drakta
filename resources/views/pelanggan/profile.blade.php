@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="bg-white py-10">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <img
                alt="Logo DR AKTA PERCETAKAN"
                class="mx-auto h-20 w-auto rounded-lg shadow-md"
                src="/images/logo.png"
            />
            <h1 class="text-3xl font-bold text-gray-900 mt-4">DR AKTA PERCETAKAN</h1>
        </div>
        <div class="space-y-8">
            <div class="bg-gray-50 rounded-lg p-6 shadow-md">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ $profil->judul_p1 }}</h2>
                <p class="text-gray-700 leading-relaxed">
                    {{ $profil->isi_p1 }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 rounded-lg p-6 shadow-md">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ $profil->visi }}</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {!! $profil->isi_visi !!}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-lg p-6 shadow-md">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ $profil->misi }}</h2>
                    <div class="text-gray-700 space-y-2 whitespace-pre-line">
                         {!! $profil->isi_misi !!}
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-6 shadow-md">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">{{ $profil->kontak }}</h2>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {!! $profil->isi_kontak !!}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection