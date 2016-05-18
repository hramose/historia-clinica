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
    }
});

app.controller('PacientsController', function ($scope, $filter) {
    $scope.pacient = {};

    if (document.querySelector('.pacient_json')) {
        $scope.pacient = JSON.parse(document.querySelector('.pacient_json').innerHTML);
        var str = $scope.pacient.birth_date.replace(/-/g, '/');
        $scope.pacient.birth_date = $filter('date')(new Date(str), 'dd/MM/y');
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
    $scope.show_msg = false;
    $scope.review = {
        date: '',
        review: {
            antecedents: '',
            motiu_consulta: '',
            limit_articular: {dots: []}
        },
        id: '',
        patient_id: ''
    };
    $scope.dates = [];
    $scope.patient = [];
    $scope.selected_dot = '';
    $scope.dots = [];
    $scope.clickCounts = 0;
    $scope.prevDot = '';

    var image = $('img#human_body_img');
    image.mapster({
        mapKey: 'body_part',
        fillColor: 'F92525',
        onClick: function (e) {
            console.log(e);
            if ($scope.selected_dot == 'low') {
                image.mapster('set', false, e.key);
                image.mapster('set', true, e.key, {
                    stroke: 'F92525',
                    fill: false
                });
                $scope.show_msg = false;
                $scope.$apply();
            } else if ($scope.selected_dot == 'medium') {
                image.mapster('set', false, e.key);
                image.mapster('set', true, e.key, {
                    fillColor: '77cdaa'
                });
                $scope.show_msg = false;
                $scope.$apply();
            }
            else if ($scope.selected_dot == 'high') {
                image.mapster('set', false, e.key);
                image.mapster('set', true, e.key, {
                    fillColor: 'F92525'
                });
                $scope.show_msg = false;
                $scope.$apply();
            } else {
                image.mapster('set', false);
                $scope.show_msg = true;
                $scope.$apply();
            }

            if ($scope.selected_dot != "") {
                var obj = {
                    key: e.key,
                    level: $scope.selected_dot
                };

                var dots_temp = $scope.dots.filter(function (a) {
                    return a.key != obj.key
                });
                dots_temp.push(obj)
                $scope.dots = dots_temp;
                $scope.review.review.limit_articular.dots = $scope.dots;
                $scope.$apply();
            }

            return false;
        }
    });

    $scope.load_review = function (json) {
        $scope.review.id = json.id;
        $scope.review.review = typeof json.review != 'undefined' ? json.review : $scope.review.review;
        $scope.review.patient_id = json.patient_id;
        $scope.review.date = json.date;
        //pongo puntos en su sitio
        if (typeof $scope.review.review.limit_articular !== 'undefined') {
            if ($scope.review.review.limit_articular.dots.length > 0) {
                var ds = JSON.parse($scope.review.review.limit_articular.dots);
                for (var i = 0; i < ds.length; i++) {
                    var opts = {};
                    if (ds[i].level == 'low') {
                        opts = {
                            fill: false,
                            stroke: 'F92525'
                        }
                    } else if (ds[i].level == 'medium') {
                        opts = {
                            fillColor: '77cdaa'
                        };
                    } else {
                        opts = {
                            fillColor: 'F92525'
                        };
                    }
                    image.mapster('set', true, ds[i].key, opts);
                }
                $scope.dots = ds;
            }
        }
    };

    var $review = $('#review');
    if ($review.length && $review.html().trim() != '[]') {
        $scope.load_review(JSON.parse($review.html()));
        $review.html('');
    }

    var $patient = $('#patient');
    if ($patient.length && $patient.html() != '') {
        $scope.patient = JSON.parse($patient.html());
        var str = $scope.patient.birth_date.replace(/-/g, '/');
        $scope.patient.birth_date = $filter('date')(new Date(str), 'dd/MM/y');
        $patient.html('');
    }

    $scope.set_selected_dot = function (dot) {
        $scope.selected_dot = dot;
    };

    $scope.today_date = function () {
        if ($scope.review.id == '')
            $scope.review.date = $filter('date')(new Date(), 'dd/MM/y');
    };

    $scope.edit_review = function (element) {
        if ($(element).val() != -1)
            $window.location.href = base_url + '/valoracions/pacient/' + $scope.patient.id + '/show/' + $(element).val();
    };

    $scope.delete_review = function (e) {
        e.preventDefault();
        $window.location.href = base_url + '/valoracions/pacient/' + $scope.patient.id + '/delete/' + $scope.review.id;
    };

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

        /* if (obj === null) {
         var date = new Date();
         $scope.dates.push({date: $filter('date')(date, 'dd MMM yyyy HH:mm'), text: '', id: date.getTime()});
         } else {
         $scope.animate = true;
         $scope.editDateReview(obj, true);
         }*/
        var date = new Date();
        $scope.dates.push({date: $filter('date')(date, 'dd MMM yyyy HH:mm'), text: '', id: date.getTime()});
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
    /**/

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

    $scope.submit_form = function (e) {
        /*e.preventDefault();
         e.stopPropagation();
         console.log($scope.review);*/
    }
});

app.controller('SearchController', function ($scope, $filter, $timeout, $http, $sce, $window) {
    $scope.search = {term: '', url: $('#url').val()};
    $scope.autocomplete = false;
    $scope.pacients = [];
    $scope.widthSearchInput = '100px';

    $timeout(function () {
        var $term = $('input[name="term"]');
        $scope.widthSearchInput = ($term.outerWidth() - 1) + 'px';
    });

    angular.element($window).bind('resize', function () {
        var $term = $('input[name="term"]');
        $scope.widthSearchInput = ($term.outerWidth() - 1) + 'px';
    });

    $scope.search_pacient = function () {
        if ($scope.search.term == '') {
            $scope.autocomplete = false;
            return;
        }
        $http({
            method: 'POST',
            url: $scope.search.url + '/' + $scope.search.term
        }).then(function mySucces(response) {
            $scope.pacients = response.data;
            if ($scope.pacients.length) {
                $scope.autocomplete = true;
            } else {
                $scope.autocomplete = false;
            }
        }, function myError(response) {
            console.log(response);
        });
    };

    $scope.underline_word = function (word) {
        var regex = new RegExp($scope.search.term, 'gi');
        var t = word.replace(regex, '<strong>$&</strong>');
        return $sce.trustAsHtml(t);
    };

    $scope.show_review_from = function (pacient) {
        $window.location.href = $scope.pacientUrl + '/' + pacient.id;
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
        } else {
            $scope.patient = obj;
            $scope.bill.patient_id = $scope.patient.id;
            $scope.client.name = $scope.patient.full_name;
            $scope.client.address = $scope.patient.address;
            $scope.client.city = $scope.patient.city;
            $scope.client.cif = $scope.patient.nif;
        }

        $scope.autocomplete = false;
    }

    $scope.count = function (n) {
        return new Array(n);
    }

    $scope.show_total = function (n1, n2) {
        $scope.bill.total_bill = (isNaN(n1) ? 0 : n1) - (isNaN(n2) ? 0 : n2);
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
