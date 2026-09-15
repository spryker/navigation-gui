/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var tableAccess = require('ZedGuiModules/libs/table/table-access');
var navigationTree = require('./navigation-tree');
var navigationHandle;

/**
 * @param {string} selector
 *
 * @return {void}
 */
function initialize(selector) {
    var navigationTable = document.querySelector(selector);

    if (!navigationTable) {
        return;
    }

    navigationTree.initialize();

    $(navigationTable).on('click', 'tbody > tr:not(.child)', tableRowSelect);

    tableAccess.requestTable(navigationTable, function (handle) {
        navigationHandle = handle;

        handle.on('draw', selectFirstRow);

        handle.raw().on('select', loadNavigationTree).on('deselect', resetNavigationTree);
    });
}

/**
 * @return {void}
 */
function tableRowSelect() {
    selectRow(this);
}

/**
 * @return {void}
 */
function selectFirstRow() {
    selectRow(0);
}

/**
 * @param {Object|number} row - Row node or row index.
 *
 * @return {void}
 */
function selectRow(row) {
    var api = navigationHandle.raw();

    api.rows().deselect();
    api.row(row).select();
}

/**
 * @return {void}
 */
function loadNavigationTree(e, api, type, indexes) {
    var rowData = api.row(indexes[0]).data();
    navigationTree.load(rowData[0]);
}

/**
 * @return {void}
 */
function resetNavigationTree() {
    navigationTree.reset();
}

/**
 * Open public methods
 */
module.exports = {
    initialize: initialize,
};
