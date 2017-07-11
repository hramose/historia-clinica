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
                    let date = moment();
                    return scope.events.filter(function (item) {
                        let eventDate = moment(item.date);
                        if ((date.get('month') == eventDate.get('month')) && (date.get('year') == eventDate.get('year'))) {
                            return true;
                        }
                    });
                };

                scope.redrawCalendar = function (month, year) {
                    var now = moment();

                    if (typeof month !== 'undefined') {
                        now = moment([(new Date()).getFullYear(), month, 1]);
                    }
                    if (typeof year !== 'undefined') {
                        now = moment([year, (new Date()).getMonth(), 1]);
                    }
                    if (typeof month !== 'undefined' && typeof year !== 'undefined') {
                        now = moment([year, month, 1]);
                    }

                    let events_of_month = scope.getEventsOfThisMonth();

                    let first_day = moment(now).startOf('month');
                    let end_day = moment(now).endOf('month');

                    let month_range = moment.range(first_day, end_day);
                    let weeks = [];

                    for (let w of month_range.by('days')) {
                        if (weeks.indexOf(w.week()) === -1)
                            weeks.push(w.week())
                    }

                    let weekdays = moment.weekdaysMin();
                    let sunday = weekdays[0];
                    weekdays.splice(0, 1);
                    weekdays.push(sunday);

                    scope.weekdays_name = weekdays;
                    scope.weeks = weeks;
                    scope.weekdays = new Array(7);
                    scope.month_name = now.format('MMMM');
                    scope.actual_month = parseInt(now.format('M')) - 1;
                    scope.actual_year = parseInt(now.format('YYYY'));

                    let day = 1;
                    let days_in_month = first_day.daysInMonth();
                    let start_day = first_day.toDate().getDay() - 1;
                    if (start_day === -1) start_day = 6;

                    let real_now_date = moment();
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
                                var day = parseInt(moment(item.date).format('DD'));
                                var domElement = $('#hcs-calendar .hcs-weeks .hcs-day').filter(function () {
                                    return parseInt($(this).text()) === day;
                                });
                                domElement.addClass('has-events');
                                domElement.prop('title', $.trim(`${domElement.prop('title')}\n${item.visit_reason}`));
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