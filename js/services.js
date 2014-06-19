'use strict';

angular.module('acs.services', []).
factory('auth', function() {
    var user = {};
    return {
        user: function() {
            return user;
        }
    };
});
