@extends('layouts.app')

@section('title', 'Portfolio - ' . config('app.name'))

@section('seo')
    <x-seo
        :title="__('messages.home.seo.title')"
        :description="__('messages.home.seo.description')"
        :keywords="__('messages.home.seo.keywords')"
        :canonical="true"
    />
@endsection

@section('content')
<x-sections.background>
    <x-sections.hero />
    <x-sections.about />
    <x-sections.projects />
    <x-sections.contact />
</x-sections.background>
@endsection

