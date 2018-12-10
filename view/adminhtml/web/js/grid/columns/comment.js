define([
    'jquery',
    'underscore',
    'Magento_Ui/js/grid/columns/column',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal'
], function ($, _, Column, alert) {
    'use strict';

    return Column.extend({

        defaults: {
            bodyTmpl: 'Inkl_Check24/comment',
            modal: null
        },

        initialize: function () {
            this._super();

            return this;
        },

        getFieldClass: function (row) {
            return 'cursor-pointer';
        },

        getFieldHandler: function (row) {
            var self = this;

            return function() {
                self.showModal(row.id);
            };
        },

        showModal: function (id) {

            var commentModal = $('.data-grid-cell-content-comment-modal[data-id="' + id + '"]');

            this.modal = commentModal.modal({
                title: 'Kommentar bearbeiten',
                buttons: [{
                    text: 'Schliessen',
                    class: '',
                    click: function () {
                        this.closeModal();
                    }
                }]
            });

            this.modal.modal('openModal');

            return false;
        },

        save: function (id, saveUrl) {

            var self = this;
            var commentElement = $('.data-grid-cell-content-comment[data-id="' + id + '"]');
            var commentModal = $('.data-grid-cell-content-comment-modal[data-id="' + id + '"]');
            var commentValue = commentModal.find('input').val();

            var params = {
                'ajax': 1,
                'comment': commentValue
            };

            $.ajax({
                showLoader: true,
                url: saveUrl,
                data: params,
                type: 'POST',
                dataType: 'json'
            }).done(function (data) {

                if (data.error) {
                    alert({
                        title: 'Error',
                        content: data.message
                    });

                    return false;
                }

                commentElement.html(commentValue);

                self.modal.modal('closeModal');
            });

        }

    });
});
