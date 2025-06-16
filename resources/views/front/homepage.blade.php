@extends('front.layouts.master')
@section('title','Anasayfa')
@section('content')
    <!-- hero area -->
    <div class="hero-area hero-bg">
        <img src="{{asset('images')}}/fd2cac15-e121-4e40-96f9-1941952864d9.png" style="width: 100%; height: 100%; position: absolute; opacity: 70%; object-fit: cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 offset-lg-2 text-center">
                    <div class="hero-text">
                        <div class="hero-text-tablecell">
                            <h1>Tüm Canlılar İçin Güvenli Bir Yaşam Ortamı</h1>
                            <div class="hero-btns">
                                <a href="{{route('contact')}}" class="bordered-btn">İletişime Geçin</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end hero area -->

    <!-- features list section -->
    <div class="list-section pt-80 pb-80">
        <div class="container">

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="list-box d-flex align-items-center">
                        <div class="list-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="content">
                            <h3>Aynı Hafta Kurulum</h3>
                            <p>Hızlı Kurulum !</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="list-box d-flex align-items-center">
                        <div class="list-icon">
                            <i class="fas fa-phone-volume"></i>
                        </div>
                        <div class="content">
                            <h3>7/24 Destek</h3>
                            <p>Tüm gün destek al !</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="list-box d-flex justify-content-start align-items-center">
                        <div class="list-icon">
                            <i class="fas fa-sync"></i>
                        </div>
                        <div class="content">
                            <h3>Geri Dönüş</h3>
                            <p>Gün içinde geri dönüş al !</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end features list section -->







@endsection
