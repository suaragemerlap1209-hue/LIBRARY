@extends('layouts.member')

@section('title', 'Bantuan')
@section('page-title', 'Pusat Bantuan')

@section('content')
    <x-landing.help-hero />
    <x-landing.help-panduan-pinjam />
    <x-landing.help-kebijakan-denda />
    <x-landing.help-faq />
    <x-landing.help-syarat-ketentuan />
    <x-landing.help-kontak />
@endsection