@extends('layouts.admin')

@section('title', 'Hesap Ayarları - Admin - ' . config('app.name'))
@section('page-title', 'Hesap Ayarları')

@section('content')
    @if(session('success'))
        <div class="mb-6 rounded-sm border border-emerald-200 dark:border-emerald-800 bg-emerald-50/90 dark:bg-emerald-900/20 p-4">
            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-sm border border-red-200 dark:border-red-900 bg-red-50/80 dark:bg-red-900/20 p-4">
            <p class="text-sm font-medium text-red-700 dark:text-red-300">Lütfen formdaki alanları kontrol edin.</p>
        </div>
    @endif

    <x-admin.card class="p-6 max-w-3xl">
        <div class="mb-6">
            <h2 class="text-base font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Admin Bilgileri</h2>
            <p class="mt-1 text-sm text-[#706f6c] dark:text-[#8F8F8B]">Giriş yaptığınız hesabın adını, e-posta adresini ve şifresini bu alandan güncelleyebilirsiniz.</p>
        </div>

        <div data-form-success class="hidden mb-6 rounded-sm border border-emerald-200 dark:border-emerald-800 bg-emerald-50/90 dark:bg-emerald-900/20 p-4">
            <p data-form-success-message class="text-sm font-medium text-emerald-800 dark:text-emerald-200"></p>
        </div>

        <div data-form-error class="hidden mb-6 rounded-sm border border-red-200 dark:border-red-900 bg-red-50/80 dark:bg-red-900/20 p-4">
            <p data-form-error-message class="text-sm font-medium text-red-700 dark:text-red-300"></p>
        </div>

        <form id="admin-profile-form" method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] mb-1">Ad</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" data-field-input="name" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                    <p data-field-error="name" class="mt-1 text-xs text-red-600 dark:text-red-400 {{ $errors->has('name') ? '' : 'hidden' }}">{{ $errors->first('name') }}</p>
                </div>

                <div>
                    <label for="email" class="block text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] mb-1">E-posta</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" data-field-input="email" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                    <p data-field-error="email" class="mt-1 text-xs text-red-600 dark:text-red-400 {{ $errors->has('email') ? '' : 'hidden' }}">{{ $errors->first('email') }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-1">Şifre Güncelle</h3>
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-4">Şifreyi değiştirmek istemiyorsanız aşağıdaki alanları boş bırakın.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="current_password" class="block text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] mb-1">Mevcut Şifre</label>
                        <input id="current_password" type="password" name="current_password" data-field-input="current_password" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                        <p data-field-error="current_password" class="mt-1 text-xs text-red-600 dark:text-red-400 {{ $errors->has('current_password') ? '' : 'hidden' }}">{{ $errors->first('current_password') }}</p>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] mb-1">Yeni Şifre</label>
                        <input id="password" type="password" name="password" data-field-input="password" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                        <p data-field-error="password" class="mt-1 text-xs text-red-600 dark:text-red-400 {{ $errors->has('password') ? '' : 'hidden' }}">{{ $errors->first('password') }}</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] mb-1">Yeni Şifre Tekrar</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" data-field-input="password_confirmation" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                        <p data-field-error="password_confirmation" class="mt-1 text-xs text-red-600 dark:text-red-400 hidden"></p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 pt-2">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Bu değişiklikler bir sonraki oturumdan itibaren de geçerli olur.</p>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-sm bg-[#D62113] text-white text-sm font-medium hover:bg-[#b81a0f] transition-colors">
                    Kaydet
                </button>
            </div>
        </form>
    </x-admin.card>
@endsection

@push('scripts')
    @vite('resources/js/pages/admin/profile.js')
@endpush
