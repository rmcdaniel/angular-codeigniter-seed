/* global angular, _, Ladda */
'use strict';

angular.module('acs.directives', [])
.directive('menu', [function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var $this = jQuery(element);
            $this.find('li').has('ul').children('ul').addClass('collapse');
            $this.find('li').has('ul').children('a').on('click', function(e) {
                e.preventDefault();
                $(this).parent('li').toggleClass('active').children('ul').collapse('toggle');
                $(this).parent('li').siblings().removeClass('active').children('ul.in').collapse('hide');
            });
        }
    };
}])
.directive('loading', [function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs, ngModel) {
            var loaders = ['loaded'].concat(scope.$eval(attrs.loading));
            
            var watch = function(newValue) {
                if (_.every(loaders, function(loader) {
                    return ((loader == '') || (_.isUndefined(loader))) ? true : scope.$eval(loader);
                })) {
                    jQuery(element).removeClass('loading');
                    jQuery(element).css('opacity', 1);
                } else {
                    jQuery(element).css('opacity', 0.4);
                    jQuery(element).addClass('loading');
                }
            };

            _.forEach(loaders, function(loader) {
                scope.$watch(loader, watch);
            });
        }
    };
}])
.directive('selectpicker', [function() {
    return {
        require: 'ngModel',
        restrict: 'A',
        link: function(scope, element, attrs, ngModel) {
            jQuery(element).selectpicker();
            scope.$watch(function() {
                return ngModel.$modelValue;
            }, function(newValue) {
                jQuery(element).selectpicker('refresh');
            });
        }
    };
}])
.directive('ladda', [function() {
    return {
        restrict: 'A',
        priority: -1,
        link: function(scope, element, attrs) {
            var ladda = Ladda.create(element[0]);

            element.addClass('ladda-button');
            element.attr('data-style', 'expand-right');
            element.attr('data-size', 1);

            scope.$watch(function() {
                return scope.$eval(attrs.ladda);
            }, function(newValue) {
                if (newValue) {
                    ladda.start();
                } else {
                    ladda.stop();                    
                }
            });

        }
    };
}]);
