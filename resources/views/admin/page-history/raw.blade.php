@extends('layouts.admin')

@section('title', __('messages.page_history.raw') . __('messages.admin.title_suffix') . config('app.name'))
@section('page-title', __('messages.page_history.page_history_section') . ' — ' . __('messages.page_history.raw'))

@section('content')
    <section class="space-y-6" aria-labelledby="request-log-heading">
        <h2 id="request-log-heading" class="sr-only">{{ __('messages.page_history.raw') }}</h2>
        <livewire:admin.request-log-table />
    </section>
@endsection
