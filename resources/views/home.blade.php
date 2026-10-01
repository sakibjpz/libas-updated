@extends('layouts.app')

@section('title', 'LibasBD - Premium Burkha, Abaya & Women\'s Fashion Online in Bangladesh')
@section('canonical', rtrim(config('app.url'), '/') . '/')

@section('description', 'libasbd - Your trusted online shopping destination in Bangladesh. Shop for quality products including burkha, 3 piece, cosmetics and more.')

@section('keywords', 'Premium Burkha Bangladesh, Buy Burkha Online BD, Abaya Online Bangladesh, Dubai Abaya, Saudi Burkha, Jilbab Bangladesh, Niqab, Hijab Online BD, Premium 3 Piece Bangladesh, Pakistani 3 Piece, Unstitched Three Piece, Salwar Kameez Bangladesh, Original Cosmetics Bangladesh, Skin Care Online BD, Women\'s Fashion Bangladesh, Online Fashion Store, Modest Fashion, Muslim Women Clothing, Islamic Clothing BD, Eid Collection, Wedding Abaya, Men Fashion Bangladesh, Kids Fashion BD, Kids Wear Online, Sherwani Premium Bangladesh, Sherwani Online, Panjabi Online BD, Boys and Girls Dress, Family Fashion, Cash on Delivery, বোরকা, আবায়া, থ্রি পিস, কসমেটিকস, শেরওয়ানি, পাঞ্জাবি, LibasBD')

@section('content')

        {{-- SEO heading (visually hidden — design unchanged) --}}
        <h1 style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;">
            LibasBD - Premium Burkha, Abaya, Three Piece & Women's Fashion Online Shopping in Bangladesh
        </h1>

        {{-- Banner --}}
        @include('partials.Banner')

        {{-- Product Categories --}}
        @include('partials.Product_Categories')

@endsection
