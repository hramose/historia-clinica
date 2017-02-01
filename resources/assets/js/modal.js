'use strict';
(function () {
    angular.module('ModalModule', []);

    angular.module('ModalModule').service('ModalService', ModalService);

    ModalService.$inject = ['$window'];

    function ModalService($window) {
        var _el;
        var _modals = [];

        this.createRandomId = function (min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        };

        this.createModal = function (opts) {
            _el = document.createElement('div');
            _el.id = 'modal-' + this.createRandomId(0, 10000);
            _el.className = 'angular-modal-alert';

            var modalInner = document.createElement('div');
            modalInner.className = 'inner';
            var modalTitle = document.createElement('div');
            modalTitle.className = 'modal-title';
            modalTitle.innerHTML = opts.title;
            var modalText = document.createElement('div');
            modalText.className = 'modal-text';
            modalText.innerHTML = opts.message;

            modalInner.appendChild(modalTitle);
            modalInner.appendChild(modalText);

            _el.appendChild(modalInner);

            var modalButtons = document.createElement('div');
            modalButtons.className = 'buttons';
            var spanButton = document.createElement('span');
            spanButton.className = 'button';
            spanButton.innerHTML = opts.buttonMsg;
            modalButtons.appendChild(spanButton);


            _el.appendChild(modalButtons);

            if (_modals.indexOf(_el.id) != -1)
                _el.id = 'modal-' + this.createRandomId(0, 10000);

            _modals[_el.id] = _el;

            document.body.appendChild(_el);
            /**
             * Styles
             **/
            var newEl = document.getElementById(_el.id);
            newEl.setAttribute("style",
                "margin-top:" + -(newEl.offsetHeight / 2) + "px; margin-left: " + -(newEl.offsetWidth / 2) + "px");

            return _el.id;
        };

        this.showModal = function (id) {
            var modal = this.getModal(id);
            if (typeof modal != 'undefined') {
                modal.style.visibility = 'visible';

                $window.scrollTo(0, angular.element(modal).offsetTop);

                var overlay = document.createElement('div');
                overlay.className = 'modal-overlay'
                document.body.appendChild(overlay);
                overlay.onclick = function (e) {
                    document.body.removeChild(modal);
                    document.body.removeChild(overlay);
                };
            }
            else
                console.error('Incorrect ID, modal not found');
        };

        this.callbackFunction = function (id, callback) {
            var modal = this.getModal(id);
            if (typeof modal != 'undefined') {
                modal.querySelector('.button').onclick = function (e) {
                    callback();
                };
            }
        };

        this.getModal = function (id) {
            return _modals[id];
        }
    }
})();