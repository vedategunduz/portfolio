@extends('errors.layout')

@section('title', '500 - ' . config('app.name'))
@section('heading', 'Sunucu hatası')
@section('message', 'Bir şeyler yanlış gitti. Lütfen daha sonra tekrar deneyin.')
