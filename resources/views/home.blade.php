
@extends('layouts.app')

@section('content')
    <script>
        //Cargar app AngulaJS
        var app = angular.module('app', []);

        //Reemplazar cuadros de diálogo tradicionales por unos mas estéticos.
        $(function () {
            alert = swal;
        });

        //Configuración AngularJS
        app.config(function ($interpolateProvider) {

            //Cambiar el símbolo para no entrar en conflicto con blade.
            $interpolateProvider.startSymbol('[[').endSymbol(']]');
        });
        app.controller('controller_main', function ($http, $scope) {
            var c = this;
            //Obtener las URL para ejecutar cada acción.
            c.url = '{{route('crud_medicine')}}';
            c.url_user = '{{route('crud_user')}}';

            //Valores predeterminados de las listas.
            $scope.medicines = [];
            $scope.form = {editing: false};
            $scope.medicine = {type: 'Tableta'};
            $scope.users = [];

            //Cargar listados iniciales
            c.load = function(){
                c.get();
                c.getUsers();
            }

            //Obtener listado de cuentas de usuario.
            c.getUsers = function(){
                $http.get(c.url_user).then(function(r){
                    $scope.users = r.data.data;
                });
            }

            //Eliminar un medicamento.
            c.delete = function(el){
                el.busy = true;
                var p = {id: el.id};

                //Función que ejecuta la acción de eliminar en el servidor.
                var del = () => {
                    $http.delete(c.url, {params: p}).then(function(r){
                        //Ocultar elemento eliminado.
                        el.hidden = true;
                        alert("Correcto", "El elemento ha sido eliminado", "success");
                    }, function () {
                        alert("Error", "Ocurrió un error al eliminar el elemento", "error");
                    });
                }
                //Diálogo para confirmar la eliminación del elemento.
                swal({
                    title: "¿Estas seguro?",
                    text: "Se eliminará el medicamento de la lista.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        del();
                    }
                    //Pone opaco el elemento: esta en proceso
                    el.busy = false;
                });
            }

            //Muestra el cuadro de edición con los valores del medicamento.
            c.edit = function(el){
                $scope.medicine = el;
                $scope.form.editing = true;
            }

            //Ejecuta la acción en el servidor que aplica los cambios o crea el medicamento.
            c.create = function(){
                var el = $scope.medicine;
                el.busy = true;
                var p = {data: el, id: el.id};

                //Llamada al servidor
                $http.put(c.url, p).then(function(r){

                    //Si se encontraron errores de validación, mostrar mensajes de error:
                    if(Object.keys(r.data.errors).length > 0){
                        var error_msg = "";
                        for(var x in r.data.errors){
                            x = r.data.errors[x];
                            error_msg += x + "\n";
                        }
                        alert('Error:', 'Se encontraron los siguientes errores de validación: \n' + error_msg, 'warning');
                        return;
                    }
                    console.log(r.data);
                    $scope.form.editing = false;
                    c.get();
                    alert("Correcto", "El elemento ha sido guardado", "success");
                }, function () {
                    alert("Error", "Ocurrió un error al guardar el elemento", "error");
                });
            }

            //Obtener listado de medicamentos.
            c.get = function(){
                $http({
                    url: c.url
                }).then(function(r){
                    console.log(r);
                    $scope.medicines = r.data.data;
                }, function () {
                    alert("Error", "Ocurrió un error al obtener listado", "error");
                })
            }
        });
    </script>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Menú principal</div>
                    <div class="card-body">
                        <div ng-app="app" ng-controller="controller_main as c" ng-init="c.load()">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#list_instructor" role="tab" aria-selected="true">
                                        <i class="fa fa-tablets"></i> Medicamentos
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#list_solicitud" role="tab" aria-selected="false">
                                        <i class="fa fa-user"></i> Usuarios
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#acerca" role="tab" aria-selected="false">
                                        <i class="fa fa-info"></i> Acerca
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">

                                <!--CUADRO ACERCA DEL PROGRAMA, CON EL README... -->
                                <div class="tab-pane fade" id="acerca" role="tabpanel">
                                    <div class="p-1">
                                        <h4 class="mt-1">Acerca de DRUGBOX:</h4>
                                        <hr>
                                        <pre>
                                            <code>
                                                {{$read}}
                                            </code>
                                        </pre>
                                    </div>
                                </div>

                                <!--LISTADO DE MEDICAMENTOS-->
                                <div class="tab-pane fade active show" id="list_instructor" role="tabpanel">
                                    <div class="p-1" style="overflow: auto">
                                        <button ng-click="c.edit({})" class="btn btn-success float-right">
                                            <i class="fa fa-plus"></i> Crear nuevo
                                        </button>
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Tipo</th>
                                            <th>Cantidad</th>
                                            <th>Creado</th>
                                            <th>Modificado</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr ng-repeat="m in medicines" ng-if="!m.hidden" ng-class="{'item-busy': m.busy}">
                                            <td>
                                                <i ng-click="c.delete(m)" class="fa fa-trash"></i>
                                                <i ng-click="c.edit(m)" class="fa fa-pencil"></i>
                                            </td>
                                            <td>[[m.code]]</td>
                                            <td>[[m.name]]</td>
                                            <td>[[m.type]]</td>
                                            <td>[[m.count]]</td>
                                            <td>[[m.creation_date]]</td>
                                            <td>[[m.update_date]]</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!--LISTADO DE MEDICAMENTOS-->
                                <div class="tab-pane fade" id="list_solicitud" role="tabpanel">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Documento</th>
                                                <th>Correo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="u in users">
                                                <td>[[u.name]]</td>
                                                <td>[[u.documentType]]: [[document]]</td>
                                                <td>[[u.email]]</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!--FORMULARIO PARA EDITAR MEDICAMENTOS-->
                            <div ng-if="form.editing" class="locker">
                                <div class="card" style="width: 500px;margin:auto;">
                                    <div class="card-header">Editar elemento</div>
                                    <div class="card-body">
                                        <form ng-submit="c.create()" class="was-validated">
                                            <div>
                                                <div class="input-group input-group-sm mb-1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Código</span>
                                                    </div>
                                                    <input ng-model="medicine.code" required="" maxlength="4" name="codigo" type="text" class="form-control">
                                                    <div class="invalid-feedback">Por favor ingrese el código del medicamento</div>
                                                </div>

                                                <div class="input-group input-group-sm mb-1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Nombre</span>
                                                    </div>
                                                    <input ng-model="medicine.name" required="" name="name" class="form-control">
                                                    <div class="invalid-feedback">Por favor ingrese el nombre del medicamento</div>
                                                </div>

                                                <div class="input-group input-group-sm mb-1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Tipo</span>
                                                    </div>
                                                    <select ng-model="medicine.type" required="" name="tipo" class="form-control">
                                                        <option>Tableta</option>
                                                        <option>Cápsula</option>
                                                        <option>Inyectable</option>
                                                    </select>
                                                    <div class="invalid-feedback">Por favor ingrese el tipo de medicamento</div>
                                                </div>

                                                <div class="input-group input-group-sm mb-1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Cantidad</span>
                                                    </div>
                                                    <input type="number" ng-model="medicine.count" required="" name="count" max="1000" class="form-control">
                                                    <div class="invalid-feedback">Por favor ingrese la cantidad actual del medicamento</div>
                                                </div>
                                                <button ng-click="form.editing = false" class="btn btn-danger float-right">Cancelar</button>
                                                <button type="submit" class="btn btn-success float-right mr-1" ng-class="{'item-busy': medicine.busy}">Guardar cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

