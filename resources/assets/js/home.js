angular.module('app', []);

angular.module('app').config(['$interpolateProvider', function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
}]);

RequestsController.$inject = ['$scope'];

angular.module('app').controller('RequestsController', RequestsController);


function RequestsController($scope) {
    $scope.dni = '';
}
