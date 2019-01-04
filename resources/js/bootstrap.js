try {
    window.jQuery = window.$ = $ = require('jquery');
    window.DataTable = require('datatables.net-bs4');
    window.Popper = require('popper.js').default;
    require('bootstrap');

} catch (e) {
    console.log(e);
}