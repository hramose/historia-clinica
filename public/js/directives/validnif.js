'use strict';

/**
 * @ngdoc directive
 * @name directive:validnif
 * @description
 * # validnif
 */
angular.module('app')
    .directive('validnif', function () {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function (scope, elem, attr, ctrl) {
                var validRegex = /^[XYZ]?([0-9]{7,8})([A-Z])$/i;
                var dniLetters = 'TRWAGMYFPDXBNJZSQVHLCKE';
                ctrl.$parsers.unshift(function (value) {
                    var valid = false;
                    if (value && value.length === 9) {
                        value = value.toUpperCase().replace(/\s/, '');
                        var niePrefix = value.charAt(0);
                        switch (niePrefix) {
                            case 'X':
                                niePrefix = 0;
                                break;
                            case 'Y':
                                niePrefix = 1;
                                break;
                            case 'Z':
                                niePrefix = 2;
                                break;
                        }
                        value = niePrefix + value.substr(1);
                        var valid = false;
                        if (validRegex.test(value)) {
                            valid = (value.charAt(8) === dniLetters.charAt(parseInt(value, 10) % 23));
                        }
                    }
                    ctrl.$setValidity('validnif', valid);
                    return valid ? value : undefined;
                });
            }
        };
    });