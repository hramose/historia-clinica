var app = angular.module('app', ['ngAnimate'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.controller('AppController', function ($scope) {

});

app.controller('UsersController', function ($scope, $filter) {
    $scope.user = {};

    $scope.randomStr = function (m) {
        var m = m || 9, s = '', r = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        for (var i = 0; i < m; i++) {
            s += r.charAt(Math.floor(Math.random() * r.length));
        }
        return s;
    };

    $scope.showDeleteModal = function (e) {
        e.preventDefault();
        var href = $(e.target).parent().attr('href');
        var confirmDelete = confirm('Estàs segur d\'eliminar aquest usuari?');
        if (confirmDelete) {
            window.location.href = href;
        }
    };

    if (document.querySelector('.user_json')) {
        $scope.user = JSON.parse(document.querySelector('.user_json').innerHTML);
        $scope.user.blocked = $scope.user.blocked == 1;
        $scope.user.password = $scope.randomStr(16);
        console.log($scope.user);
    }
});

app.controller('PacientsController', function ($scope, $filter) {
    $scope.pacient = {};

    if (document.querySelector('.pacient_json')) {
        $scope.pacient = JSON.parse(document.querySelector('.pacient_json').innerHTML);
        $scope.pacient.birth_date = $filter('date')(new Date($scope.pacient.birth_date), 'dd/MM/y');
    }

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

    $scope.showDeleteModal = function (e) {
        e.preventDefault();
        var href = $(e.target).parent().attr('href');
        var confirmDelete = confirm('Estàs segur d\'eliminar aquest pacient?');
        if (confirmDelete) {
            window.location.href = href;
        }
    };

});

app.controller('FlashController', function ($scope, $timeout) {
    $scope.timeOut = false;

    $timeout(function () {
        $scope.timeOut = true;
    }, 3000);
});

app.controller('ReviewController', function ($scope, $filter, $timeout) {
    $scope.data = new Date();
    $scope.actualDate = new Date();
    $scope.form = {};
    $scope.review = [];
    $scope.dates = [];

    if ($('#review').val() != '') {
        $scope.review = JSON.parse($('#review').val());
    }

    $scope.addDateToReview = function (e) {
        e.preventDefault();
        var obj = null;
        for (var i = 0; i < $scope.review.length; i++) {
            if ($scope.isToday($scope.review[i].id)) {
                obj = $scope.review[i];
                break;
            }
        }
        for (var i = 0; i < $scope.dates.length; i++) {
            if ($scope.isToday($scope.dates[i].id)) {
                obj = $scope.dates[i];
                break;
            }
        }

        if (obj === null) {
            var date = new Date();
            $scope.dates.push({date: $filter('date')(date, 'dd MMM yyyy HH:mm'), text: '', id: date.getTime()});
        } else {
            $scope.animate = true;
            $scope.editDateReview(obj, true);
        }
    };

    $scope.submitForm = function (e) {
        var finalReview = $scope.review;
        finalReview = finalReview.concat($scope.dates);

        var review = JSON.stringify(finalReview);
        $('#review').val(review);
    };

    $scope.showActualHour = function () {
        $scope.actualDate = $filter('date')(new Date(), 'dd MMM yyyy HH:m');
    }

    $scope.isToday = function (date) {
        /*return moment(new Date(date)).isSame(moment(), 'day');*/
        return true;
    }

    $scope.editDateReview = function (dateObject, fromOtherFn) {
        fromOtherFn = fromOtherFn || false;
        if (!fromOtherFn)
            $scope.animate = false;
        $scope.edit = dateObject.id;

        $timeout(function () {
            $scope.animate = false;
        }, 500);
    }

    $scope.checkKey = function (e) {
        /*if (e.keyCode == 13 && !e.shiftKey) {
         $scope.edit = '';
         }*/
    }

    $scope.showReview = function (dateObj) {

    };
});


