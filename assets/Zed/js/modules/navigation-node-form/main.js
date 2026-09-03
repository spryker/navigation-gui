/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var safeChecks = require('ZedGuiModules/libs/safe-checks');

/**
 * Legacy jQuery datepicker setup, including the manual min/max bookkeeping that keeps the two ends
 * of the validity range consistent, plus the native-picker suppression the legacy `.safe-datetime`
 * inputs rely on.
 *
 * @deprecated Superseded by `DatePickerType` and the Gui DateTimePicker, which handle range linking
 *   declaratively. Kept only for installations running spryker/gui older than 5.4.0.
 */
function initLegacyValidityPickers(validFrom, validTo) {
    validFrom.datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        maxDate: validTo.val(),
        defaultData: 0,
        onClose: function (selectedDate) {
            validTo.datepicker('option', 'minDate', selectedDate);
        },
    });

    validTo.datepicker({
        defaultData: 0,
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        numberOfMonths: 3,
        minDate: validFrom.val(),
        onClose: function (selectedDate) {
            validFrom.datepicker('option', 'maxDate', selectedDate);
        },
    });

    safeChecks.addSafeDatetimeCheck();
}

$(document).ready(function () {
    var $nodeTypeField = $('#navigation_node_node_type');

    displaySelectedNodeTypeField($nodeTypeField.val());
    $nodeTypeField.on('change', changeNodeType);

    var validFrom = $('#navigation_node_valid_from');
    var validTo = $('#navigation_node_valid_to');

    // From spryker/gui 5.4.0 on, these fields are built with `DatePickerType`, which marks them
    // with `data-spryker-picker` and lets the Gui DateTimePicker initialize and range-link them.
    // Older Gui versions have no such type, so the legacy picker below is set up instead.
    if (!validFrom.is('[data-spryker-picker]')) {
        initLegacyValidityPickers(validFrom, validTo);
    }

    $('.spryker-form-autocomplete').each(function (key, value) {
        var autoCompletedField = $(value);
        if (autoCompletedField.data('url') === 'undefined') {
            return;
        }

        if (autoCompletedField.hasClass('ui-autocomplete')) {
            autoCompletedField.autocomplete('destroy');
        }

        autoCompletedField.autocomplete({
            source: autoCompletedField.data('url'),
            minLength: 3,
        });
    });

    deleteNavigationNodeHandler();
});

/**
 * @param {string} type
 *
 * @return {void}
 */
function displaySelectedNodeTypeField(type) {
    $('[data-node-type="' + type + '"]').removeClass('hidden');
}

/**
 * @return {void}
 */
function changeNodeType() {
    resetNodeTypeFields();
    displaySelectedNodeTypeField($(this).val());
    triggerResize();
}

function deleteNavigationNodeHandler() {
    var $deleteSelectedNodeButton = $('#remove-selected-node-btn');
    var $deleteNavigationNodeForm = $('form[name="delete_navigation_node_form"]');
    var message = $deleteSelectedNodeButton.data('confirm-message');

    $deleteSelectedNodeButton.on('click', function (event) {
        event.preventDefault();

        if (confirm(message)) {
            $deleteNavigationNodeForm[0].submit();
        }
    });
}

/**
 * @return {void}
 */
function resetNodeTypeFields() {
    $('.js-node-type-field').addClass('hidden').find('input[type="text"]').val('');
}

/**
 * @return {void}
 */
function triggerResize() {
    var resizeEvent = new Event('resize');
    window.dispatchEvent(resizeEvent);
}
