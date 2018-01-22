@extends('standard.layouts.main')

@section('content')
    @if (isset($data))
        <pre>{!! $data !!}</pre>
    @endif
@endsection
