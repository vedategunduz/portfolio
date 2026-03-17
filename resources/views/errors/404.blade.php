@extends('errors.layout')

@section('title', '404 - ' . config('app.name'))
@section('heading', __('messages.errors.404.heading'))
@section('message', __('messages.errors.404.message'))
