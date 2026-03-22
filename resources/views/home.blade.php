@extends('layouts.app')

@section('title', __('messages.home.title') . ' - ' . config('app.name'))

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

    <x-sections.home-blog :posts="($posts ?? collect())" />

    <x-sections.contact />
</x-sections.background>
@endsection

