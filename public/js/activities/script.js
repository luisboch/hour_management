
function finishAction(a, t) {
    try {
        $('#finish_end_time').val(t);
        $('#action_id').val(a);
        $('#action_submit').submit();
    } catch (e) {
        alert(e);
    }
    return false;
}

var opts = {
    'minTime': '06:00am',
    'maxTime': '08:00pm',
    'timeFormat': 'H:i:s'
};

$('#action_end_time').timepicker(opts);
$('#action_start_time').timepicker(opts);
$('.action-input-time').timepicker(opts);
$('#status').change(function() {
    var chkActive = $('#active').is(':checked');
    var newval = $('#status').val();
    if (newval === '0' && chkActive) {
        $('#newActionPanel').slideDown('fast');
    } else {
        $('input[name=action_start_time]').val('');
        $('input[name=action_end_time]').val('');
        $('#newActionPanel').slideUp('fast');
    }
});

$('#active').change(function() {
    var chkActive = $('#active').is(':checked');
    var newval = $('#status').val();
    if (newval === '0' && chkActive) {
        $('#newActionPanel').slideDown('fast');
    } else {
        $('input[name=action_start_time]').val('');
        $('input[name=action_end_time]').val('');
        $('#newActionPanel').slideUp('fast');
    }

});