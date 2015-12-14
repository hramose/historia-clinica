var app = angular.module('app', [], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.controller('PacientsController', function ($scope) {
    $scope.pacient = {};

    $scope.putAgeFromDate = function (date) {
        var regexp = /^\d{2}([./-])\d{2}\1\d{4}$/;

        if (date.match(regexp)) {
            var arrDate = date.split('/');
            var dateUser = new Date(arrDate[2], arrDate[1] - 1, arrDate[0]);
            var ageDifMs = Date.now() - dateUser.getTime();
            var ageDate = new Date(ageDifMs);
            $scope.pacient.age = Math.abs(ageDate.getUTCFullYear() - 1970);
        } else {
            console.log('nop!');
        }
    }

    $scope.showDeleteModal = function(e) {
        e.preventDefault();
        var href = $(e.target).parent().attr('href');
        var confirmDelete = confirm('Estàs segur d\'eliminar aquest pacient?');
        if (confirmDelete) {
            window.location.href = href;
        }
    };

});

app.controller('FlashController', function($scope, $timeout) {
    $scope.timeOut = false;

    $timeout(function() {
        $scope.timeOut = true;
    }, 3000);
});

app.controller('ReviewController', function ($scope, $filter, $timeout) {
    $scope.data = new Date();
    $scope.actualDate = new Date();
    $scope.form = {};

    $timeout(function() {
        $scope.form.review = $('#review').val();
        $('.review-text').html($('.review-text span').text());
    }, 100);

    $scope.addDateToReview = function (e) {
        e.preventDefault();
        if (typeof $scope.form.review == 'undefined')
            $scope.form.review = '';

        $scope.form.review += (($scope.form.review != '') ? "\n" : "") + $filter('date')(new Date(), 'dd MMM yyyy H:mm') + "\n";
    };

    $scope.submitForm = function (e) {
        var text = $scope.form.review;
        $('#review').val(text);
    };

    $scope.showActualHour = function() {
        $scope.actualDate = $filter('date')(new Date(), 'dd MMM yyyy H:m');
    }
});


