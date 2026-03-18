@extends('layouts.admin')

@section('title', __('messages.admin.nav.login_history') . __('messages.admin.title_suffix') . config('app.name'))
@section('page-title', 'Giriş Geçmişi')

@section('content')
    <x-admin.ui.filter-card title="{{ __('messages.filters') }}" :action="route('admin.login-history.index')" method="get">
        <x-admin.form.input label="{{ __('messages.log.date_from') }}" type="date" name="date_from" value="{{ request('date_from') }}" />
        <x-admin.form.input label="{{ __('messages.log.date_to') }}" type="date" name="date_to" value="{{ request('date_to') }}" />
        <x-admin.form.select label="{{ __('messages.history.filter_type') }}" name="type" :options="['' => __('messages.history.filter_type_all'), 'success' => __('messages.history.filter_success'), 'failed' => __('messages.history.filter_failed')]" :selected="request('type')" />
        <x-admin.form.input label="{{ __('messages.history.filter_email') }}" name="email" value="{{ request('email') }}" />
        <div class="sm:col-span-2 lg:col-span-1">
            <x-admin.form.input label="{{ __('messages.history.filter_ip') }}" name="ip" value="{{ request('ip') }}" placeholder="192.168.1.1" />
        </div>
        <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-4">
            <x-admin.ui.button variant="primary" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                {{ __('messages.filter') }}
            </x-admin.ui.button>
            <x-admin.ui.button variant="secondary" :href="route('admin.login-history.index')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ __('messages.clear') }}
            </x-admin.ui.button>
        </div>
    </x-admin.ui.filter-card>

    <x-admin.card>
        <div class="hidden md:block overflow-x-auto">
            <x-admin.ui.table-wrapper>
                <x-slot:header>
                    <tr>
                        <x-admin.ui.table-th>{{ __('messages.history.table_date') }}</x-admin.ui.table-th>
                        <x-admin.ui.table-th>{{ __('messages.history.table_type') }}</x-admin.ui.table-th>
                        <x-admin.ui.table-th>{{ __('messages.history.table_email') }}</x-admin.ui.table-th>
                        <x-admin.ui.table-th>{{ __('messages.history.table_ip') }}</x-admin.ui.table-th>
                        <x-admin.ui.table-th>{{ __('messages.history.table_reason') }}</x-admin.ui.table-th>
                        <x-admin.ui.table-th>{{ __('messages.history.table_useragent') }}</x-admin.ui.table-th>
                    </tr>
                </x-slot:header>
                @forelse($logs as $log)
                    <x-admin.ui.table-row :zebra="$loop->iteration % 2 === 0">
                        <x-admin.ui.table-td class="whitespace-nowrap">{{ $log->attempted_at?->format('d/m/Y H:i:s') }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td class="whitespace-nowrap">
                            @if($log->isSuccess())
                                <x-admin.ui.badge variant="success">{{ __('messages.history.badge_success') }}</x-admin.ui.badge>
                            @else
                                <x-admin.ui.badge variant="danger">{{ __('messages.history.badge_failed') }}</x-admin.ui.badge>
                            @endif
                        </x-admin.ui.table-td>
                        <x-admin.ui.table-td>{{ $log->email }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td variant="secondary" class="whitespace-nowrap">{{ $log->ip_address ?? '—' }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td variant="secondary">{{ $log->getFailureReasonLabel() ?? '—' }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td variant="secondary" class="max-w-xs truncate" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 35) }}</x-admin.ui.table-td>
                    </x-admin.ui.table-row>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 lg:px-4 py-12 text-center text-sm text-[#6b7280] dark:text-[#9ca3af]">{{ __('messages.no_records') }}</td>
                    </tr>
                @endforelse
            </x-admin.ui.table-wrapper>
        </div>

        <div class="md:hidden divide-y divide-[#e5e5e5] dark:divide-[#333333]">
            @forelse($logs as $log)
                <div class="p-4 hover:bg-[#f5f5f5] dark:hover:bg-[#252525] transition-colors duration-150">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs text-[#6b7280] dark:text-[#9ca3af]">{{ $log->attempted_at?->format('d/m/Y H:i') }}</span>
                        @if($log->isSuccess())
                            <x-admin.ui.badge variant="success">{{ __('messages.history.badge_success') }}</x-admin.ui.badge>
                        @else
                            <x-admin.ui.badge variant="danger">{{ __('messages.history.badge_failed') }}</x-admin.ui.badge>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-[#111827] dark:text-[#f3f4f6]">{{ $log->email }}</p>
                    <dl class="mt-2 space-y-1 text-xs text-[#6b7280] dark:text-[#9ca3af]">
                        @if($log->ip_address)<div><dt class="inline">IP:</dt> <dd class="inline">{{ $log->ip_address }}</dd></div>@endif
                        @if($log->failure_reason)<div><dt class="inline">Sebep:</dt> <dd class="inline">{{ $log->getFailureReasonLabel() }}</dd></div>@endif
                        @if($log->user_agent)<div><dt class="inline">UA:</dt> <dd class="inline break-all">{{ Str::limit($log->user_agent, 45) }}</dd></div>@endif
                    </dl>
                </div>
            @empty
                <div class="px-4 py-12 text-center text-sm text-[#6b7280] dark:text-[#9ca3af]">{{ __('messages.no_records') }}</div>
            @endforelse
        </div>

        <x-admin.ui.pagination :paginator="$logs" />
    </x-admin.card>
@endsection
