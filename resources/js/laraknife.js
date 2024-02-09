'use strict';

window.globalSorter = null;
var debug = function (message) {
    //console.log(message);
}
var autoClick = function(){
    document.getElementById('_btnInternal').click();
}
var numberedButtonClick = function(event){
    const name = event.currentTarget.getAttribute("name");
    document.getElementById('_lknAction').value = name;
    document.getElementById('_btnInternal').click();
}
var paginationClick = function(){
    const pageNo = Number.parseInt(this.innerHTML) - 1;
    document.getElementsByName('pageIndex')[0].value = pageNo;
    document.getElementById('_btnInternal').click();
}
var setAutoUpdate = function(){
    const elements = document.getElementsByClassName('lkn-autoupdate');
    for (let ix=0; ix < elements.length; ix++){
        let item = elements[ix];
        item.addEventListener("change", autoClick);
    }
}
var setNumberedButtonClick = function(){
    const elements = document.getElementsByClassName('lkn-numbered-button');
    for (let ix=0; ix < elements.length; ix++){
        let item = elements[ix];
        item.addEventListener("click", numberedButtonClick);
    }   
}
var setPaginationClick = function(){
    const elements = document.getElementsByClassName('page-link');
    for (let ix=0; ix < elements.length; ix++){
        let item = elements[ix];
        item.addEventListener("click", paginationClick);
    }   
}
var onClickSort = function (event) {
    if (window.globalSorter != null) {
        window.globalSorter.handleClickSort(event);
    }
}
class SortItem {
    constructor(key, direction = 'asc') {
        this.key = key
        this.direction = direction
    }
}
class TableSorter {
    constructor(statusField = '_sortParams', attributName = 'sortId') {
        this.statusField = statusField;
        this.attributeName = attributName;
        this.sortItems = []
    }
    addSortItem(item, onTop = true) {
        if (!this.indexOfKey(item.key)) {
            if (onTop) {
                this.sortItems.unshift(item);
            } else {
                this.sortItems.push(item);
            }
            debug(`addSortItem(${onTop}): ${item.key}:${item.direction}`)
        }
    }
    initializeForm() {
        const list = document.querySelectorAll("th");
        for (var ix = 0; ix < list.length; ix++) {
            const item = list[ix];
            const attr = item.getAttribute(this.attributeName);
            if (attr != null) {
                item.addEventListener("click", onClickSort);
            }
        }
        this.initializeTableHeader();
    }
    indexOfKey(key) {
        var rc = null;
        for (var ix = 0; ix < this.sortItems.length; ix++) {
            if (this.sortItems[ix].key === key) {
                rc = ix;
                break;
            }
        }
        return rc;
    }
    initializeTableHeader() {
        const status = document.getElementById(this.statusField);
        if (status){
            const items = status.value.split(';');
            debug(`initializeTableHeader(): ${items.length} items`);
            for (var ix = 0; ix < items.length; ix++) {
                const parts = items[ix].split(':');
                if (parts[0] !== '' && this.indexOfKey(parts[0]) == null) {
                    this.addSortItem(new SortItem(parts[0], parts.length > 1 ? parts[1] : 'asc'), false);
                }
            }
            this.updateArrows();
        }
    }
    handleClickSort(event) {
        const key = event.currentTarget.getAttribute('sortId');
        const item1 = this.sortItems.length == 0 ? null : this.sortItems[0];
        if (item1 != null && item1.key === key) {
            this.sortItems[0].direction = item1.direction === 'desc' ? 'asc' : 'desc';
        } else {
            const ix = this.indexOfKey(key);
            var item = null;
            if (ix == null) {
                item = new SortItem(key, 'asc');
                debug(`handleClickSort(): adding ${key}:asc}`)
            } else {
                item = this.sortItems[ix];
                if (ix === this.sortItems.length - 1){
                    this.sortItems.pop();
                } else {
                    this.sortItems = this.sortItems.slice(ix, 1);
                }
                debug(`deleted: ${ix} count: ${this.sortItems.length}`);
                debug(`handleClickSort(): moving ${item.key}:${item.direction}`)
            }
            this.addSortItem(item);
        }
        this.updateStatus();
        this.updateArrows();
        autoClick();
    }
    updateArrows() {
        for (var ix = 0; ix < this.sortItems.length; ix++) {
            const item = this.sortItems[ix];
            const element = document.querySelector(`th[${this.attributeName}="${item.key}"]`);
            if (element != null) {
                let value = element.innerHTML;
                const lastChar = value.slice(-1);
                const lastChar2 = lastChar.charCodeAt(0);
                const arrows = ix > 0 ? [8593, 8595] : [8607, 8609];
                const arrow = arrows[item.direction === 'asc' ? 0 : 1];
                if (lastChar2 < 8593 || lastChar2 > 8609) {
                    value += `&#${arrow}`;
                } else {
                    value = value.substring(0, value.length - 1) + `&#${arrow}`;
                }
                element.innerHTML = value;
                debug(`updateArrows(${ix}): ${value}`);
            }
        }
    }
    updateStatus() {
        const status = [];
        for (var ix = 0; ix < this.sortItems.length; ix++) {
            const item = this.sortItems[ix];
            status.push(`${item.key}:${item.direction}`);
        }
        let status2 = status.join(';');
        debug(`updateStatus(): ${status2}`);
        document.getElementsByName(this.statusField)[0].value = status2;
    }
}
var ready = (callback) => {
    if (document.readyState != "loading") {
        callback();
    } else {
        document.addEventListener("DOMContentLoaded", callback);
    }
}
ready(() => {
    /* Do things after DOM has fully loaded */
    window.globalSorter = new TableSorter();
    window.globalSorter.initializeForm();
    setAutoUpdate();
    setPaginationClick();
    setNumberedButtonClick();
}); 
