@extends('layouts.admin')

@section('title', __('messages.blog_admin.create_post') . __('messages.admin.title_suffix') . config('app.name'))
@section('page-title', __('messages.blog_admin.create_post'))

@section('content')
    @if($errors->any())
        <x-admin.notice variant="danger" class="mb-6">
            {{ __('messages.blog_admin.fix_form_errors') }}
        </x-admin.notice>
    @endif

    <form
        action="{{ route('admin.posts.store') }}"
        method="POST"
        enctype="multipart/form-data"
        data-autosave-enabled="1"
        data-autosave-post-id=""
        data-autosave-store-url="{{ route('admin.posts.autosave.store') }}"
        data-autosave-update-url-template="{{ route('admin.posts.autosave.update', ['post' => '__POST__']) }}"
        data-autosave-submit-update-url-template="{{ route('admin.posts.update', ['post' => '__POST__']) }}"
    >
        @csrf
        @include('admin.posts._form')
    </form>
@endsection
