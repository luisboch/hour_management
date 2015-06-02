
function loadHeaderSelection() {
    var filterLocation = $('.columns-filter');
    var localResultTable = $('table.use_column_selection');

    // Add elements to filter
    localResultTable.find('thead > tr > th').each(function (i) {
        var title = $(this).text();
        
        if($(this).find('.hidden-xs').length > 0 ){
            title = $(this).find('.hidden-xs').text();
        }
        
        filterLocation.append('<div class="checkbox">' +
                '<label>' +
                '<input class="" type="checkbox" name="column_' + i + '" \n\
                     id="column_' + i + '" checked="checked" /> ' + title +
                '</label>' +
                '</div> ');

    });



    var tbRows = localResultTable.find('tbody > tr');

    filterLocation.find("div.checkbox").each(function (columnIndex) {
        $(this).find('input[type=checkbox]').click(function () {
            var header = localResultTable.find('thead > tr > th:eq(' + columnIndex + ')');

            var checkbox = $(this);

            if (checkbox.is(":checked")) {
                header.css('display', 'table-cell');
            } else {
                header.css('display', 'none')
            }

            tbRows.each(function () {
                $(this).find('td:eq(' + columnIndex + ')').each(function () {
                    var column = $(this);
                    if (checkbox.is(":checked")) {
                        column.css('display', 'table-cell');
                    } else {
                        column.css('display', 'none')
                    }
                });
            });

        });
    });
}


$(document).ready(function () {
    loadHeaderSelection();
});