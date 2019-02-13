(function (window, $) {
    window.LaravelDataTables = window.LaravelDataTables || {};

    /**
     * @param tableId
     * @returns DataTable
     */
    function getdT(tableId) {
        return window.LaravelDataTables[tableId];
    }

    function hideFilterBtn(element) {
        return $(element).addClass('d-none').removeClass('d-inline');
    }

    function showFilterBtn(element) {
        return $(element).addClass('d-inline').removeClass('d-none');
    }

    function performSearch(table) {
        let dT = getdT(table.attr('id'));
        let searched = false;

        dT = dT.state.clear();
        table.find('.filter-input').each(function () {
            dT.columns($(this).attr('name') + ':name').search($(this).val().trim());
            searched = searched === false ? !!$(this).val().trim().length : true;
        });

        if (searched) {
            showFilterBtn(table.find('.btn-reset-filter'));
        } else {
            hideFilterBtn(table.find('.btn-reset-filter'));
        }

        dT.columns().search().draw();
    }

    $('.filter-input').on('keypress', function (e) {
        if (e.which == 13) {
            $('.btn-filter').trigger('click');
        }
    });

    $('.btn-filter').on('click', function (e) {
        performSearch($(this).closest('table').first());
    });

    $('.btn-reset-filter').on('click', function (e) {
        let table = $(this).closest('table').first();
        table.find('.filter-input').each(function () {
            $(this).val('');
        });
        performSearch(table);
        hideFilterBtn();

    });

    window.dTstateLoadParams = function dTStateLoadParams(e, settings, state) {

        if (settings.aoColumns === undefined || settings.nTable === undefined) {
            return;
        }

        if(settings.aoColumns.length !== state.columns.length){
            let dT = new $.fn.dataTable.Api( settings );
            dT.state.clear();
            return;
        }

        let searched = false;
        let table = $(settings.nTable);

        $.each(state.columns, function (i, v) {

            let element = $(clearIdSelector(settings.aoColumns[i].sName + '-filter-input')).first();

            if (!element.length) {
                return true;
            }
            element.val(v.search.search);
            searched = searched === false ? !!v.search.search.length : true;
        });

        if (searched) {
            showFilterBtn(table.find('.btn-reset-filter'));
        } else {
            hideFilterBtn(table.find('.btn-reset-filter'));
        }
    }

})(window, jQuery);

function clearIdSelector( id ) {
    return "#" + id.replace( /(:|\.|\[|\]|,|=)/g, "\\$1" );
}


