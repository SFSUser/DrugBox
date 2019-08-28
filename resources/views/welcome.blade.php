@extends('layouts.app')

@section('content')
        <div class="flex-center position-ref full-height">
            <div class="content" style="padding: 10px 30% 10px 30%">
                <div class="splash">
                    <div class="row" >
                        <div class="col-md-4">
                            <img style="width: 100%" src="https://i.imgur.com/cyf366J.png"/>
                        </div>
                        <div class="col-md-8">
                            <h3>DRUGBOX</h3>
                            <i>Sistema de logística de medicamentos.</i><br>
                            @auth
                            @else
                            <b style="color:red;">Usuario generado automáticamente:</b><br>
                            <b>Usuario: </b> {{$user_data->document}}<br>
                            <b>Clave: </b> 123123123<br>
                            @endauth
                        </div>
                    </div>
                    <hr>
                    <center>
                        <!--SPLASH DE BIENVENIDA-->
                        @if (Route::has('login'))
                            @auth
                                Ya has iniciado sesión <a href="{{route('home')}}">Volver</a>
                            @else
                                <a class="btn btn-success" href="{{ route('login') }}">Iniciar sesión</a>

                                @if (Route::has('register'))
                                    <a class="btn btn-info" href="{{ route('register') }}">Registrarse</a>
                                @endif
                            @endauth
                        @endif
                    </center>
                </div>
            </div>
        </div>
@endsection