@extends('errors.layout')

@section('title', '403 - ' . config('app.name'))
@section('heading', __('messages.errors.403.heading'))
@section('message', __('messages.errors.403.message'))
