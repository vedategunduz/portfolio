@extends('layouts.admin')

@section('title', __('messages.blog_admin.edit_post') . __('messages.admin.title_suffix') . config('app.name'))
@section('page-title', __('messages.blog_admin.edit_post'))

@section('content')
    @if($errors->any())
        <div class="mb-6 rounded-sm border border-red-200 dark:border-red-900/50 bg-red-50/90 dark:bg-red-900/20 p-4">
            <p class="text-sm font-medium text-red-700 dark:text-red-300">{{ __('messages.blog_admin.fix_form_errors') }}</p>
        </div>
    @endif

    <form
        action="{{ route('admin.posts.update', $post) }}"
        method="POST"
        enctype="multipart/form-data"
        data-autosave-enabled="1"
        data-autosave-post-id="{{ $post->id }}"
        data-autosave-store-url="{{ route('admin.posts.autosave.store') }}"
        data-autosave-update-url-template="{{ route('admin.posts.autosave.update', ['post' => '__POST__']) }}"
        data-autosave-submit-update-url-template="{{ route('admin.posts.update', ['post' => '__POST__']) }}"
    >
        @csrf
        @method('PUT')
        @include('admin.posts._form', ['post' => $post])
    </form>
@endsection
