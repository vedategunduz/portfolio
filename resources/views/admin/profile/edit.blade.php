@extends('layouts.admin')

@section('title', __('messages.profile.page_title') . __('messages.admin.title_suffix') . config('app.name'))
@section('page-title', 'Hesap Ayarları')

@section('content')
    @if(session('success'))
        <x-admin.notice variant="success" class="mb-6">
            {{ session('success') }}
        </x-admin.notice>
    @endif

    @if($errors->any())
        <x-admin.notice variant="danger" class="mb-6">
            {{ __('messages.profile.form_error') }}
        </x-admin.notice>
    @endif

    <x-admin.card class="p-6 max-w-3xl">
        <div class="mb-6">
            <h2 class="text-base font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.profile.admin_info') }}</h2>
            <p class="mt-1 text-sm text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.profile.admin_info_desc') }}</p>
        </div>

        <x-admin.notice variant="success" data-form-success class="hidden mb-6">
            <span data-form-success-message></span>
        </x-admin.notice>

        <form id="admin-profile-form" method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.form.input
                    label="{{ __('messages.profile.name_label') }}"
                    id="name"
                    name="name"
                    :value="old('name', $user->name)"
                    error-field="name"
                    :error="$errors->first('name')"
                    data-field-input="name"
                />

                <x-admin.form.input
                    label="{{ __('messages.profile.email_label') }}"
                    id="email"
                    name="email"
                    type="email"
                    :value="old('email', $user->email)"
                    error-field="email"
                    :error="$errors->first('email')"
                    data-field-input="email"
                />
            </div>

            <div class="pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-1">{{ __('messages.profile.update_password') }}</h3>
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-4">{{ __('messages.profile.password_optional') }}</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.form.input
                        class="md:col-span-2"
                        label="{{ __('messages.profile.current_password') }}"
                        id="current_password"
                        name="current_password"
                        type="password"
                        error-field="current_password"
                        :error="$errors->first('current_password')"
                        data-field-input="current_password"
                    />

                    <x-admin.form.input
                        label="{{ __('messages.profile.new_password') }}"
                        id="password"
                        name="password"
                        type="password"
                        error-field="password"
                        :error="$errors->first('password')"
                        data-field-input="password"
                    />

                    <div>
                        <x-admin.form.input
                            label="{{ __('messages.profile.confirm_password') }}"
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            data-field-input="password_confirmation"
                        />
                        <p data-field-error="password_confirmation" class="mt-1 text-xs text-red-600 dark:text-red-400 hidden"></p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.profile.note_next_session') }}</p>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-sm bg-[#D62113] text-white text-sm font-medium hover:bg-[#b81a0f] transition-colors w-full sm:w-auto">
                    {{ __('messages.save') }}
                </button>
            </div>
        </form>
    </x-admin.card>
@endsection

@push('scripts')
    @vite('resources/js/pages/admin/profile.js')
@endpush
