/* global angular, _, i18n */
'use strict';

var controllers = angular.module('acs.controllers', []);

controllers.controller('base', ['$scope', '$location', '$q', 'user', function($scope, $location, $q, user) {

    $scope.loaded = false;
    $scope.user = user;
    $scope.permissions = {};

    $scope.init = function() {
        var promises = [];
    
        promises.push(user.permissions('user', 'read')
        .then(function(permissions) {
            $scope.permissions.users = permissions;
        }));
    
        promises.push(user.permissions('role', 'read')
        .then(function(permissions) {
            $scope.permissions.roles = permissions;
        }));
        
        $q.all(promises)
        .then(function() {
            $scope.loaded = true;
        }, function() {
            $scope.loaded = true;
        });
    };

    $scope.active = function(path) {
        return $location.path().match(new RegExp(path + '.*', 'i')) != null;
    };

    $scope.logout = function() {
        $scope.user.clear();
        window.location.reload();
    };
    
}]);

controllers.controller('navigation', ['$scope', '$location', 'user', function($scope, $location, user) {

    $scope.user = user;
    
}]);

controllers.controller('login', ['$scope', '$location', '$http', '$window', 'alerts', 'user', function($scope, $location, $http, $window, alerts, user) {

    $scope.alerts = alerts;
    $scope.input = {};
    $scope.waiting = false;

    $scope.login = function() {
        $scope.waiting = true;
        $http.post('api/user/login', {
            email: $scope.input.email,
            password: $scope.input.password
        }).success(function(data) {
            $scope.waiting = false;
            if (data.status) {
                user.setEmail(data.email);
                user.setToken(data.token);
                $location.path('administrator');
                $window.location.reload();
            } else {
                if (_.isEmpty(data.errors)) {
                    data.errors = i18n.t('msg.fill_out_login');
                }
                alerts.fail(data.errors);
            }
        });
    };

}]);

controllers.controller('register', ['$scope', '$location', '$http', 'alerts', function($scope, $location, $http, alerts) {

    $scope.alerts = alerts;

    $scope.input = {};

    $scope.register = function() {
        $scope.waiting = true;
        if ($scope.input.password != $scope.input.confirmation) {
            alerts.fail(i18n.t('msg.password_mismatch'));
            $scope.waiting = false;
            return;
        }
        $http.post('api/user/register', {
            email: $scope.input.email,
            password: $scope.input.password
        }).success(function(data) {
            $scope.waiting = false;
            if (data.status) {
                alerts.success(i18n.t('msg.registered'));
                $location.path('login');
            } else {
                if (_.isEmpty(data.errors)) {
                    data.errors = '';
                }
                alerts.fail(data.errors);
            }
        });
    };

}]);

controllers.controller('home', ['$scope', '$location', '$http', 'user', function($scope, $location, $http, user) {

    $scope.user = user;
    
    $scope.information = function() {
        $http.post('api/user/information', {
            token: $scope.user.token
        }).success(function(data) {
            if (data.status) {
                alert(data.message);
            } else {
            }
        });
    };

}]);

controllers.controller('administrator', ['$scope', '$location', '$http', 'user', function($scope, $location, $http, user) {

    $scope.user = user;
    
    $scope.information = function() {
        $http.post('api/user/information', {
            token: $scope.user.token
        }).success(function(data) {
            if (data.status) {
                alert(data.message);
            } else {
            }
        });
    };

}]);

controllers.controller('users', ['$scope', '$location', '$http', 'user', 'alerts', 'ngTableParams', function($scope, $location, $http, user, alerts, ngTableParams) {

    $scope.user = user;
    $scope.tableLoaded = false;
    
    if (!$scope.user.loggedIn()) {
        $location.path('login');
        return;
    }

    $scope.alerts = alerts;

    $scope.tableParams = new ngTableParams({
        page: 1,
        count: 10,
        sorting: {
            id: 'asc'
        }
    }, {
        total: 0,
        getData: function($defer, params) {
            $http.post('api/user/table', {
                token: $scope.user.getToken(),
                params: JSON.stringify(params.$params)
            }).success(function(data) {
                params.total(data.total);
                $defer.resolve(data.users);
                $scope.tableLoaded = true;
            });
        }
    });

}]);

controllers.controller('user', ['$scope', '$timeout', '$location', '$http', '$routeParams', 'user', 'alerts', 'ngTableParams', function($scope, $timeout, $location, $http, $routeParams, user, alerts, ngTableParams) {

    $scope.user = user;
    
    if (!$scope.user.loggedIn()) {
        $location.path('login');
        return;
    }

    $scope.alerts = alerts;
    $scope.input = {user: {roles: []}};
    $scope.role_header = "'Role'";

    $scope.read = function() {
        $http.post('api/user/read', {
            token: $scope.user.getToken(),
            id: $routeParams.id
        }).success(function(data) {
            if (data.status) {
                $scope.input = {user: data.user};
                $scope.tableParams.reload();
            }
        });
    };

    $scope.update = function(close) {
        $http.post('api/user/update', {
            token: $scope.user.getToken(),
            user: JSON.stringify($scope.input.user)
        }).success(function(data) {
            if (data.status) {
                if (_.isUndefined(close)) {
                    $scope.input = {user: data.user};
                    $scope.tableParams.reload();
                } else {
                    $location.path('administrator/users');
                }
                $scope.alerts.success(i18n.t('msg.user_updated'));
            } else {
                $scope.alerts.fail(data.errors);
            }
        });
    };

    $scope.addRole = function(role) {
        if (_.isEmpty(role)) {
            alerts.fail(i18n.t('msg.enter_role_name'));
            return;
        }
        role = JSON.stringify(role.toLowerCase()).replace(/\W/g, '').trim();
        if (_.isEmpty(role)) {
            alerts.fail(i18n.t('msg.enter_role_name'));
            return;
        }
        $scope.input.user.roles.push(role);
        $scope.tableParams.reload();
    };

    $scope.deleteRole = function(role) {
        $scope.input.user.roles = _.without($scope.input.user.roles, role);
        $scope.tableParams.reload();
    };
    
    $scope.tableParams = new ngTableParams({
        page: 1,
        count: 10,
        sorting: {
            role: 'asc'
        }
    }, {
        total: 0,
        getData: function($defer, params) {
            params.total($scope.input.user.roles.length);
            $defer.resolve($scope.input.user.roles);
        }
    });

    $scope.cancel = function() {
        $location.path('administrator/users');
    };
    
}]);

controllers.controller('roles', ['$scope', '$location', '$http', 'user', 'alerts', 'ngTableParams', function($scope, $location, $http, user, alerts, ngTableParams) {

    $scope.user = user;
    $scope.tableLoaded = false;
    
    if (!$scope.user.loggedIn()) {
        $location.path('login');
        return;
    }

    $scope.alerts = alerts;
    $scope.input = {};

    $scope.tableParams = new ngTableParams({
        page: 1,
        count: 10,
        sorting: {
            role: 'asc'
        }
    }, {
        total: 0,
        getData: function($defer, params) {
            $http.post('api/role/table', {
                token: $scope.user.getToken(),
                params: JSON.stringify(params.$params)
            }).success(function(data) {
                params.total(data.total);
                $defer.resolve(data.roles);
                $scope.tableLoaded = true;
            });
        }
    });

    $scope.addRole = function(role) {
        if (_.isEmpty(role)) {
            alerts.fail(i18n.t('msg.enter_role_name'));
            return;
        }
        role = JSON.stringify(role.toLowerCase()).replace(/\W/g, '').trim();
        if (_.isEmpty(role)) {
            alerts.fail(i18n.t('msg.enter_role_name'));
            return;
        }
        $http.post('api/role/create', {
            token: $scope.user.getToken(),
            role: role
        }).success(function(data) {
            if (data.status) {
                $scope.tableParams.reload();
                $scope.alerts.success(i18n.t('msg.role_added'));
            } else {
                $scope.alerts.fail(data.errors);
            }
        });
    };

    $scope.deleteRole = function(role) {
        $http.post('api/role/delete', {
            token: $scope.user.getToken(),
            role: role
        }).success(function(data) {
            if (data.status) {
                $scope.tableParams.reload();
                $scope.alerts.success(i18n.t('msg.role_deleted'));
            } else {
                $scope.alerts.fail(data.errors);
            }
        });
    };

}]);

controllers.controller('role', ['$scope', '$location', '$http', '$routeParams', 'user', 'alerts', 'ngTableParams', function($scope, $location, $http, $routeParams, user, alerts, ngTableParams) {

    $scope.user = user;
    
    if (!$scope.user.loggedIn()) {
        $location.path('login');
        return;
    }

    $scope.alerts = alerts;
    $scope.input = {resources: []};
    $scope.updateCount = 0;

    $scope.update = function(close) {
        $scope.failCount = 0;
        _.forEach($scope.input.resources, function(resource) {
            $scope.updateCount += 1;
            $http.post('api/role/update', {
                token: $scope.user.getToken(),
                role: $routeParams.role,
                resource: resource.name,
                permissions: JSON.stringify(resource.permissions)
            }).success(function(data) {
                if (!data.status) {
                    $scope.failCount += 1;
                    $scope.errors = data.errors;
                }
                $scope.updateCount -= 1;
                if ($scope.updateCount == 0) {
                    if (_.isUndefined(close)) {
                        $scope.tableParams.reload();
                    } else {
                        $location.path('administrator/roles');
                    }
                    if ($scope.failCount) {
                        $scope.alerts.fail($scope.errors);
                    } else {
                        $scope.alerts.success(i18n.t('msg.role_updated'));
                    }
                }
            });
        });
    };

    $scope.tableParams = new ngTableParams({
        page: 1,
        count: 10,
        sorting: {
            resource: 'asc'
        }
    }, {
        total: 0,
        getData: function($defer, params) {
            $http.post('api/resource/table', {
                token: $scope.user.getToken(),
                params: JSON.stringify(params.$params),
                role: $routeParams.role
            }).success(function(data) {
                $scope.input.resources = data.resources;
                params.total(data.total);
                $defer.resolve(data.resources);
            });
        }
    });

    $scope.cancel = function() {
        $location.path('administrator/roles');
    };

}]);