@extends('layouts.admin')

@section('title', 'Request Log - Admin - ' . config('app.name'))
@section('page-title', 'Sayfa Geçmişi — Request Log')

@section('content')
    <section class="space-y-6" aria-labelledby="request-log-heading">
        <h2 id="request-log-heading" class="sr-only">Request Log</h2>
        <livewire:admin.request-log-table />
    </section>
@endsection
