@extends('layouts.main')

@section('content')
    Salut {{ Auth::user()->nickname }}.
@endsection
