@extends('layouts.public')
@section('title', $page->getTranslation('title', app()->getLocale(), false))
@section('content')
    {{ $blocks }}
@endsection
