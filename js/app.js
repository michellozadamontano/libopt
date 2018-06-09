/**
 * Created by michellm on 23/06/2016.
 */

var app = angular.module('app',['ngRoute']);

//las rutas de mi sitio
app.config(function($routeProvider){
    $routeProvider.when('auth/login',{
        templateUrl:'auth/views/login.html',
        controller:'authController'
    })

});

app.controller('authController',function($scope){

});
