@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('About') }}</h1>

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card shadow mb-4">

                <div class="card-profile-image mt-4">
                    <img src="{{ asset('img/favicon.png') }}" class="rounded-circle" alt="user-image">
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="font-weight-bold">Desafio TWGroup</h5>
                            <p>Esta es la implementacion de la prueba tecnica realizada para el puesto de trabajo publicado por la empresa TWGroup</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="font-weight-bold">Creditos</h5>
                            <ul>
                                <li><a href="https://laravel.com" target="_blank">Laravel</a></li>
                                <li><a href="https://github.com/DevMarketer/LaravelEasyNav" target="_blank">LaravelEasyNav</a></li>
                                <li><a href="https://startbootstrap.com/themes/sb-admin-2" target="_blank">SB Admin 2</a></li>
                                <li><a href="https://github.com/aleckrh/laravel-sb-admin-2" target="_blank">Laravel SB Admin 2</a></li>
                                <li><a href="https://fullcalendar.io" target="_blank">Full Calendar</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@endsection
