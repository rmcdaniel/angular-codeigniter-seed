/* global _ angular store moment */
'use strict';

angular.module('acs.services', []).
factory('user', function($q, $http) {
    return {
        clear: function() {
            store.set('user', {});
        },
        permissions: function(resource) {
            var deferred = $q.defer();
            var user = _.isUndefined(store.get('user')) ? {} : store.get('user');
            $http.post('api/user/permissions', {
                token: user.token,
                resource: resource
            }).success(function(data) {
                if (data.status) {
                    deferred.resolve(data.permissions);
                    return;
                }
                deferred.reject();
            });
            return deferred.promise;
        },
        loggedIn: function() {
            var user = _.isUndefined(store.get('user')) ? {} : store.get('user');
            return !_.isEmpty(user) && !_.isEmpty(user.token) && !_.isUndefined(user.token);
        },
        getEmail: function() {
            var user = _.isUndefined(store.get('user')) ? {} : store.get('user');
            return user.email;
        },
        getToken: function() {
            var user = _.isUndefined(store.get('user')) ? {} : store.get('user');
            return user.token;
        },
        setEmail: function(email) {
            var user = _.isUndefined(store.get('user')) ? {} : store.get('user');
            user.email = email;
            store.set('user', user);
        },
        setToken: function(token) {
            var user = _.isUndefined(store.get('user')) ? {} : store.get('user');
            user.token = token;
            store.set('user', user);
        }
    };
}).
factory('alerts', function($interval) {
    var alerts = undefined;
    if (!window.alertsInterval) {
        window.alertsInterval = $interval(function() {
            var alive = [];
            _.forEach(alerts, function(alert) {
                if (!moment().isAfter(moment(alert.timestamp).add(5, 'seconds'))) {
                    alive.push(alert);
                }
            });
            alerts = alive;
            store.set('alerts', alerts);
        }, 1000);
    }
    return {
        clear: function() {
            store.set('alerts', []);
        },
        get: function() {
            if (_.isUndefined(alerts)) {
                alerts = store.get('alerts');
            }
            if (_.isEmpty(alerts)) {
                alerts = [];
            }
            return alerts;
        },
        set: function(val) {
            alerts = val;
            store.set('alerts', alerts);
        },
        success: function(msg) {
            alerts.push({id: Math.random().toString(16), success: msg, timestamp: new Date().getTime()});
            store.set('alerts', alerts);
        },
        fail: function(msg) {
            alerts.push({id: Math.random().toString(16), danger: msg, timestamp: new Date().getTime()});
            store.set('alerts', alerts);
        }
    };
});
