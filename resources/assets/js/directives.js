'use strict';

angular.module('app')
    .directive('hcsCalendar', function ($timeout) {
        return {
            templateUrl: '/templates/hcs-calendar.html',
            replace: true,
            link: function (scope, el, args) {
                scope.events = JSON.parse(args.events);
                /**
                 * Functions for the calendar to work
                 */

                scope.getEventsOfThisMonth = function () {
                    var date = moment();
                    return scope.events.filter(function (item) {
                        var eventDate = moment(item.date);
                        if ((date.get('month') == eventDate.get('month')) && (date.get('year') == eventDate.get('year'))) {
                            return true;
                        }
                    });
                };

                scope.redrawCalendar = function (month, year) {
                    var now = moment();

                    if (typeof month !== 'undefined') {
                        now = moment([(new Date()).getFullYear(), month, (new Date()).getDate()]);
                    }
                    if (typeof year !== 'undefined') {
                        now = moment([year, (new Date()).getMonth(), (new Date()).getDate()]);
                    }
                    if (typeof month !== 'undefined' && typeof year !== 'undefined') {
                        now = moment([year, month, (new Date()).getDate()]);
                    }

                    var events_of_month = scope.getEventsOfThisMonth();

                    var first_day = moment(now).startOf('month');
                    var end_day = moment(now).endOf('month');

                    var month_range = moment.range(first_day, end_day);
                    var weeks = [];

                    for (var w of month_range.by('days')) {
                        if (weeks.indexOf(w.week()) === -1)
                            weeks.push(w.week())
                    }

                    var weekdays = moment.weekdaysMin();
                    var sunday = weekdays[0];
                    weekdays.splice(0, 1);
                    weekdays.push(sunday);

                    scope.weekdays_name = weekdays;
                    scope.weeks = weeks;
                    scope.weekdays = new Array(7);
                    scope.month_name = now.format('MMMM');
                    scope.actual_month = parseInt(now.format('M')) - 1;
                    scope.actual_year = parseInt(now.format('YYYY'));

                    var day = 1;
                    var days_in_month = first_day.daysInMonth();
                    var start_day = first_day.toDate().getDay() - 1;
                    if (start_day === -1) start_day = 6;

                    var real_now_date = moment();
                    $timeout(function () {
                        $('#hcs-calendar .hcs-weeks .hcs-day').text('');
                        $('#hcs-calendar .hcs-weeks').each(function (index, value) {
                            $(this).find('.hcs-day').each(function (idx, val) {
                                if (day <= days_in_month && (index > 0 || idx >= start_day)) {
                                    $(this).text(day);
                                    if (day == real_now_date.format('D') && now.format('M') == real_now_date.format('M') && now.format('YYYY') == real_now_date.format('YYYY'))
                                        $(this).addClass('active');
                                    day++;
                                }
                            })
                        });
                        events_of_month.forEach(function (item, index) {
                            if (moment(item.date).format('YYYY-MM') === real_now_date.format('YYYY-MM')) {
                                var day = moment(item.date).format('DD');
                                var domElement = $('#hcs-calendar .hcs-weeks').find('.hcs-day:contains(' + day + ')');
                                domElement.addClass('has-events');
                            }
                        });
                    }, 0);
                };

                scope.showToolTip = function (item) {
                    //it works
                };

                scope.redrawCalendar();

                scope.changeMonth = function (opt) {
                    if (opt == 'add') {
                        if (scope.actual_month == 11) {
                            scope.actual_month = 0;
                            scope.actual_year += 1;
                        }
                        else
                            scope.actual_month += 1;
                    } else {
                        if (scope.actual_month == 0) {
                            scope.actual_month = 11;
                            scope.actual_year -= 1;
                        }
                        else
                            scope.actual_month -= 1;
                    }

                    scope.redrawCalendar(scope.actual_month, scope.actual_year);
                };
            }
        }
    });