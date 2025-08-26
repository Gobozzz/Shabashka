@extends('layouts.worker')

@section('title', 'Админка')

@section('content')
    <h1>Профиль админа</h1>
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button class="button-default">Выйти</button>
    </form>
@endsection