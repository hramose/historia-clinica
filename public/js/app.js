var app = angular.module('app', ['ngAnimate', 'AngularPrint'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.controller('AppController', function ($scope) {

});

app.controller('FrontController', function ($scope, $timeout, $filter, $sce, $http) {
    $scope.patients = [];

    $scope.underline_word = function (word) {
        var regex = new RegExp($scope.patient, 'gi');
        var t = word.replace(regex, '<strong>$&</strong>');
        return $sce.trustAsHtml(t);
    };

    $scope.search_pacients = function () {
        if ($scope.patient == '') {
            $scope.patients = [];
            return;
        }
        $http({
            method: 'POST',
            url: base_url + '/pacients/s/' + $scope.patient
        }).then(function mySucces(response) {
            $scope.patients = response.data;
        }, function myError(response) {
            console.log(response);
        });
    };

    $scope.show_birthdays = function (e) {
        var el = $(e.target);
        var pacients = el.attr('data-json') ? JSON.parse(el.attr('data-json')) : JSON.parse(el.parent().attr('data-json'));
        if (Object.keys(pacients).length !== 0 && JSON.stringify(pacients) !== JSON.stringify({})) {
            var $div = $('<div>',
                {
                    'class': 'tooltip tooltip-' + new Date().getTime()
                });
            $div.css({
                'position': 'absolute',
                top: (el.offset().top + 20) + "px",
                left: (el.offset().left) + "px"
            });
            for (var i in pacients) {
                $div.append("<p>El pacient " + pacients[i].full_name + " compleix anys el dia " + pacients[i].date + "</p>");
            }
            $('body').append($div);
        }
    };

    $scope.delete_tooltip = function () {
        $('.tooltip').remove();
    };
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
        $scope.pacient.birth_date = typeof $scope.pacient.birth_date != 'undefined' ? $filter('date')(new Date($scope.pacient.birth_date), 'dd/MM/y') : '';
        $scope.pacient.age = typeof $scope.pacient.age != 'undefined' ? parseInt($scope.pacient.age) : '';
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

app.controller('ReviewController', function ($scope, $filter, $timeout, $window) {
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

    $scope.print = function () {
        $window.print();
    };
});

app.controller('BillController', function ($scope, $filter, $timeout, $http, $sce, $window) {
    $scope.bill = {
        qty: 0,
        price_per_unit: 0.0,
        discount: 0.0,
        iva: 0.0,
        irpf: 0.0,
        total_bill: 0.0,
        total_partial: 0.0,
        amount_irpf: 0.0,
        total: 0.0,
        amount_discount: 0.0
    };
    $scope.billInfo = {};
    $scope.urlBillInfo = '';
    $scope.clients = {};
    $scope.pacients = [];
    $scope.client = {};
    $scope.patient = {};
    $scope.autocomplete = false;
    $scope.widthSearchInput = '100px';
    $scope.searchUrl = '';

    $scope.Base64 = {
        _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
            var t = "";
            var n, r, i, s, o, u, a;
            var f = 0;
            e = $scope.Base64._utf8_encode(e);
            while (f < e.length) {
                n = e.charCodeAt(f++);
                r = e.charCodeAt(f++);
                i = e.charCodeAt(f++);
                s = n >> 2;
                o = (n & 3) << 4 | r >> 4;
                u = (r & 15) << 2 | i >> 6;
                a = i & 63;
                if (isNaN(r)) {
                    u = a = 64
                } else if (isNaN(i)) {
                    a = 64
                }
                t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
            }
            return t
        }, decode: function (e) {
            var t = "";
            var n, r, i;
            var s, o, u, a;
            var f = 0;
            e = e.replace(/[^A-Za-z0-9+/=]/g, "");
            while (f < e.length) {
                s = this._keyStr.indexOf(e.charAt(f++));
                o = this._keyStr.indexOf(e.charAt(f++));
                u = this._keyStr.indexOf(e.charAt(f++));
                a = this._keyStr.indexOf(e.charAt(f++));
                n = s << 2 | o >> 4;
                r = (o & 15) << 4 | u >> 2;
                i = (u & 3) << 6 | a;
                t = t + String.fromCharCode(n);
                if (u != 64) {
                    t = t + String.fromCharCode(r)
                }
                if (a != 64) {
                    t = t + String.fromCharCode(i)
                }
            }
            t = $scope.Base64._utf8_decode(t);
            return t
        }, _utf8_encode: function (e) {
            e = e.replace(/rn/g, "n");
            var t = "";
            for (var n = 0; n < e.length; n++) {
                var r = e.charCodeAt(n);
                if (r < 128) {
                    t += String.fromCharCode(r)
                } else if (r > 127 && r < 2048) {
                    t += String.fromCharCode(r >> 6 | 192);
                    t += String.fromCharCode(r & 63 | 128)
                } else {
                    t += String.fromCharCode(r >> 12 | 224);
                    t += String.fromCharCode(r >> 6 & 63 | 128);
                    t += String.fromCharCode(r & 63 | 128)
                }
            }
            return t
        }, _utf8_decode: function (e) {
            var t = "";
            var n = 0;
            var r = c1 = c2 = 0;
            while (n < e.length) {
                r = e.charCodeAt(n);
                if (r < 128) {
                    t += String.fromCharCode(r);
                    n++
                } else if (r > 191 && r < 224) {
                    c2 = e.charCodeAt(n + 1);
                    t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                    n += 2
                } else {
                    c2 = e.charCodeAt(n + 1);
                    c3 = e.charCodeAt(n + 2);
                    t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                    n += 3
                }
            }
            return t
        }
    };

    angular.element($window).bind('resize', function () {
        var $term = $('input[name="client_name"]').parent();
        $scope.widthSearchInput = ($term.outerWidth() + 20) + 'px';
    });

    $timeout(function () {
        var $term = $('input[name="client_name"]').parent();
        $scope.widthSearchInput = ($term.outerWidth() + 20) + 'px';
    }, 500);

    $scope.ask_question = function (e) {
        e.preventDefault();
        var el = $(e.target).parent();
        var url = el.attr('href');
        var msg = confirm('Estàs segur que vols eliminar aquesta factura?');
        if (msg) {
            $window.location.href = url;
        }
    };

    $scope.today_date = function () {
        /*if ($scope.review.id == '')*/
        $scope.bill.date = $filter('date')(new Date(), 'dd/MM/y');
    };

    $scope.underline_word = function (word) {
        var regex = new RegExp($scope.client.name, 'gi');
        var t = word.replace(regex, '<strong>$&</strong>');
        return $sce.trustAsHtml(t);
    };

    $scope.search_clients_and_patients = function () {
        if ($scope.client.name == '') {
            $scope.autocomplete = false;
            return;
        }
        $timeout(function () {
            $http({
                method: 'POST',
                url: $scope.searchUrl + '/' + $scope.client.name
            }).then(function mySucces(response) {
                $scope.pacients = response.data.patients;
                $scope.clients = response.data.clients;
                if ($scope.clients.length || $scope.pacients.length) {
                    $scope.autocomplete = true;
                } else {
                    $scope.autocomplete = false;
                }
            }, function myError(response) {
                console.log(response);
            });
        });
    };

    $scope.put_on_bill = function (obj, model) {
        if (model == 'client') {
            $scope.client = obj;
            $scope.bill.client_id = $scope.client.id;
            $scope.bill.patient_id = '';
        } else {
            $scope.patient = obj;
            $scope.bill.patient_id = $scope.patient.id;
            $scope.client.name = $scope.patient.full_name;
            $scope.client.address = $scope.patient.address;
            $scope.client.city = $scope.patient.city;
            $scope.client.cif = $scope.patient.nif;
            $scope.bill.client_id = '';
        }

        $scope.autocomplete = false;
    }

    $scope.count = function (n) {
        return new Array(n);
    }

    $scope.show_total = function (n1, n2) {
        $scope.bill.total_bill = (isNaN(n1) ? 0 : n1) - (isNaN(n2) ? 0 : n2);
        console.log($scope.bill.total_bill);
        return $filter('currency')($scope.bill.total_bill.toFixed(2));
    };

    $scope.show_amount_discount = function (n1, n2) {
        $scope.bill.amount_discount = (isNaN(n1) ? 0 : n1) * (isNaN(n2) ? 0 : n2) / 100;
        $scope.bill.total_partial = $scope.bill.total - $scope.bill.amount_discount;
        return $filter('currency')($scope.bill.amount_discount.toFixed(2));
    };

    $scope.show_amount_irpf = function (n1, n2) {
        if (n2.toString().indexOf(',') != -1)
            n2 = n2.toString().replace(',', '.');
        $scope.bill.amount_irpf = (isNaN(n1) ? 0 : n1) * (isNaN(n2) ? 0 : n2) / 100;
        return $filter('currency')($scope.bill.amount_irpf.toFixed(2));
    };

    $scope.autocomplete_id = function () {
        if ($scope.bill.id == "" || typeof $scope.bill.id == 'undefined') {
            $scope.bill.id = $scope.lastId + 1;
        }
    };

    $scope.format_date = function (date) {
        var str = date.replace(/-/g, '/');
        return $filter('date')(new Date(str), 'dd/MM/y');
    };

    var $bill = $('#bill');
    if ($bill.length && $bill.html().trim() != '[]') {
        $scope.bill = JSON.parse($bill.html());
        var str = $scope.bill.creation_date.replace(/-/g, '/');
        $scope.bill.creation_date = $filter('date')(new Date(str), 'dd/MM/y');
        var str = $scope.bill.expiration_date.replace(/-/g, '/');
        $scope.bill.expiration_date = $filter('date')(new Date(str), 'dd/MM/y');
        if ($scope.bill.patient_id != "" && $scope.bill.patient_id != null) {
            $scope.put_on_bill($scope.bill.patient, 'patient');
        }
        if ($scope.bill.client_id != "" && $scope.bill.client_id != null) {
            $scope.put_on_bill($scope.bill.client, 'client');
        }
    }
});

app.controller('TestController', function ($scope, $http, $filter, $interval, $timeout, $window, $sce) {
    $scope.output = '';
    $scope.days = 5;
    $scope.daysMail = 5;
    $scope.title = '';
    $scope.time = 0;
    $scope.timeLoader = '';
    $scope.first_time = true;
    $scope.status = 0;

    $scope.show_output = function (url, title, params) {
        $scope.timeLoader = '';
        $scope.time = 0;
        var stop = $interval(function () {
            if ($scope.timeLoader.length == 3)
                $scope.timeLoader = '';
            $scope.timeLoader += '.';
        }, 100);
        params = params || null;
        if (params != null) {
            url = url + '/' + $scope[params];
        }

        $scope.title = title;

        $http({
            method: 'GET',
            url: url,
        }).then(function mySucces(response) {
            $interval.cancel(stop);
            $scope.output = $sce.trustAsHtml(response.data.output);
            $scope.time = response.data.time;
            $scope.status = response.status;
            $scope.first_time = false;
        }, function myError(response) {
            console.log(response);
        });
    };
});


