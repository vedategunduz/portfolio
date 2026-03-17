@extends('errors.layout')

@section('title', '503 - ' . config('app.name'))
@section('heading', __('messages.errors.503.heading'))
@section('message', __('messages.errors.503.message'))
