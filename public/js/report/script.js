$('#paramsForm').submit(function () {

    var act = $(this).attr('action');

    if (act === '#') {
        alert('Selecione um relatório');
        return false;
    }

    var newval = $('#report_selection').val();
    var user = $('#user_id');

    if (!user.is(':disabled') && newval !== '3' && newval !== '2') {
        try {
            if (user.val() === "") {
                alert("Selecione um usuário!");
                return false;
            }
        } catch (e) {
            return false;
        }
    }
    return true;
});

$(document).ready(function () {
    $('#report_selection').trigger("change");
});

function clearParams() {

    var today = new Date();
    var todayStr = today.toLocaleFormat('%d/%m/%y');

    putValue($('#report_selection'), '');
    putValue($('#user_id'), '');
    putValue($('#type_id'), '');
    putValue($('#customer_id'), '');
    putValue($('input[name=startDate]'), todayStr);
    putValue($('input[name=endDate]'), todayStr);
}

function putValue(el, v) {
    var tagName = el.prop("tagName");
    if (tagName === "SELECT") {
        el.find("option[selected]").removeAttr("selected");
        el.find("option[value=" + v + "]").attr("selected", "selected");
    } else {
        el.val(v);
    }
    el.trigger("change");
}
