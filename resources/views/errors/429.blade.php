@extends('errors.layout')

@section('title', '429 - ' . config('app.name'))
@section('heading', 'Çok fazla istek')
@section('message', 'Kısa sürede çok fazla deneme yaptınız. Lütfen bir süre bekleyip tekrar deneyin.')
