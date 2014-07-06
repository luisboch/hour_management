$(".alert").alert();



$(document).ready(function() {
    $('select').each(function() {
        if ($(this).find('option[selected]').length === 0) {
            var df = $(this).find('option[default]');
            if(df.length === 1){
               df.attr("selected", "selected")
            }
        }
    })
})