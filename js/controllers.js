'use strict';

var controllers = angular.module('acs.controllers', []);

controllers.controller('navigation', ['$scope', '$location', 'auth', function($scope, $location, auth) {

    $scope.user = auth.user;

    $scope.active = function(path) {
        return path === $location.path();
    };
    
    $scope.logout = function() {
        $scope.user = {};
        $location.path('home');
    };

}]);

controllers.controller('login', ['$scope', '$location', '$http', 'auth', function($scope, $location, $http, auth) {

    $scope.input = {};

    $scope.login = function() {
        $http.post('api/account/login', {
            email: $scope.input.email,
            password: $scope.input.password
        }).success(function(data) {
            if (data.status) {
                $scope.user = auth.user;
                $scope.user.email = data.email;
                $scope.user.token = data.token;
                $location.path('home');
            } else {
            }
        });
    };

}]);

controllers.controller('register', ['$scope', '$location', '$http', function($scope, $location, $http) {

    $scope.input = {};

    $scope.register = function() {
        $http.post('api/account/register', {
            email: $scope.input.email,
            password: $scope.input.password
        }).success(function(data) {
            if (data.status) {
                $location.path('login');
            } else {
            }
        });
    };

}]);

controllers.controller('home', ['$scope', '$location', '$http', 'auth', function($scope, $location, $http, auth) {

    $scope.user = auth.user;
    
    $scope.information = function() {
        $http.post('api/account/information', {
            token: $scope.user.token
        }).success(function(data) {
            if (data.status) {
                alert(data.message);
            } else {
            }
        });
    };

}]);
