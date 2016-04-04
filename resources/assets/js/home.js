angular.module('app', ['pascalprecht.translate']);

angular.module('app').config(['$interpolateProvider', '$translateProvider', function ($interpolateProvider, $translateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');

    $translateProvider.translations('es', {
        REQUEST: 'Para pedir una sesión necesitamos tus datos',
        DATA: 'Deja tus datos y nos pondremos en contacto contigo lo más pronto posible',
        SEARCH: 'Busca si estás en la base de datos introduciendo tu DNI',
        PACIENTNAME: 'Nombre',
        PACIENTSURNAMES: 'Apellidos',
        PACIENTPHONE: 'Teléfono',
        OBSV: 'Observaciones',
        CALENDAR: 'Puedes escoger la sesión en el calendario siguiente'
    });
    $translateProvider.translations('ca', {
        REQUEST: 'Per demanar una sessió necessitem tenir les teves dades',
        DATA: 'Deixa les teves dades i ens posarem en contacte amb tu el més aviat possible',
        SEARCH: 'Cerca si ets a la base de dades introduïnt el teu DNI',
        PACIENTNAME: 'Nom',
        PACIENTSURNAMES: 'Cognoms',
        PACIENTPHONE: 'Telèfon',
        OBSV: 'Observacions',
        CALENDAR: 'Pots escollir la sessió en el seguent calendari'
    });

    $translateProvider.preferredLanguage('ca');
    $translateProvider.useSanitizeValueStrategy('escape');
}]);

RequestsController.$inject = ['$scope', '$translate'];

angular.module('app').controller('RequestsController', RequestsController);


function RequestsController($scope, $translate) {
    $scope.dni = '';

    if (sessionStorage.getItem('lang')) {
        $translate.use(sessionStorage.getItem('lang'));
        $('body').find('a.active').removeClass('active');
        $('a#'+sessionStorage.getItem('lang')).addClass('active');
    }

    $scope.change_language = function (e, lang) {
        e.preventDefault();
        $('body').find('a.active').removeClass('active');
        $(e.target).parent().addClass('active');
        $translate.use(lang);
        sessionStorage.setItem('lang', lang);
    }
}
