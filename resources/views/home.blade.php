@extends('layouts.app')

@section('title', 'Portfolio - ' . config('app.name'))

@section('seo')
    <x-seo
        title="Vedat Egündüz - Full-Stack Developer | Laravel & PHP Backend Specialist"
        description="Backend ağırlıklı çalışan bir full-stack geliştiriciyim. Laravel, PHP, RESTful API, JWT authentication ve modern web teknolojileri konusunda deneyimliyim. Ölçeklenebilir ve sürdürülebilir yazılım geliştiriyorum."
        keywords="vedat egündüz, full-stack developer, backend developer, laravel developer, php developer, rest api, jwt authentication, mysql, database design, web developer, software engineer, next.js, tailwind css, türkiye, turkey"
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

