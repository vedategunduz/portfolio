@extends('errors.layout')

@section('title', '404 - ' . config('app.name'))
@section('heading', 'Sayfa bulunamadı')
@section('message', 'Aradığınız sayfa mevcut değil veya taşınmış olabilir.')
