/* global angular, i18n */
'use strict';

angular.module('acs', ['acs.filters', 'acs.services', 'acs.directives', 'acs.controllers', 'ngRoute', 'ui.bootstrap', 'ngTable']).
config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

    $routeProvider.when('/home', {
        controller: 'home',
        templateUrl: 'partials/home.html'
    });

    $routeProvider.when('/administrator', {
        controller: 'administrator',
        templateUrl: 'partials/administrator.html'
    });

    $routeProvider.when('/administrator/users', {
        controller: 'users',
        templateUrl: 'partials/users.html'
    });

    $routeProvider.when('/administrator/user/:id', {
        controller: 'user',
        templateUrl: 'partials/user.html'
    });

    $routeProvider.when('/administrator/roles', {
        controller: 'roles',
        templateUrl: 'partials/roles.html'
    });
                
    $routeProvider.when('/administrator/role/:role', {
        controller: 'role',
        templateUrl: 'partials/role.html'
    });

    $routeProvider.when('/login', {
        controller: 'login',
        templateUrl: 'partials/login.html'
    });

    $routeProvider.when('/register', {
        controller: 'register',
        templateUrl: 'partials/register.html'
    });

    $routeProvider.otherwise({
        redirectTo: '/home'
    });

    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    var param = function(obj) {
        var query = '',
            name, value, fullSubName, subName, subValue, innerObj, i;

        for (name in obj) {
            value = obj[name];

            if (value instanceof Array) {
                for (i = 0; i < value.length; ++i) {
                    subValue = value[i];
                    fullSubName = name + '[' + i + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if (value instanceof Object) {
                for (subName in value) {
                    subValue = value[subName];
                    fullSubName = name + '[' + subName + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if (value !== undefined && value !== null) query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
        }

        return query.length ? query.substr(0, query.length - 1) : query;
    };

    $httpProvider.defaults.transformRequest = [function(data) {
        return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
    }];
    
}]);

Array.prototype.contains = function(obj) {
    return this.indexOf(obj) > -1;
};
