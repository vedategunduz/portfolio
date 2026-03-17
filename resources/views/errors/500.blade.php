@extends('errors.layout')

@section('title', '500 - ' . config('app.name'))
@section('heading', __('messages.errors.500.heading'))
@section('message', __('messages.errors.500.message'))
