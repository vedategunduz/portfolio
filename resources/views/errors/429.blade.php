@extends('errors.layout')

@section('title', '429 - ' . config('app.name'))
@section('heading', __('messages.errors.429.heading'))
@section('message', __('messages.errors.429.message'))
