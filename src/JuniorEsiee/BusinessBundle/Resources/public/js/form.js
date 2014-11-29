$(function(){
    $('.ui.dropdown')
        .dropdown()
    ;

    $('.message .close').on('click', function() {
        $(this).closest('.message').fadeOut();
    });

    $('[data-dimmer=click]').click(function(e){
        $(this).find('.dimmer').dimmer('show');
        e.stopPropagation();
    });

    $( "[data-widget=datePicker]" ).datepicker({
        dateFormat:"dd/mm/yy",
        showOtherMonths: true,
        selectOtherMonths: true,
        dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
        monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ],
        firstDay: 1
    });

    if ($('.association_list_type').length)
    {
        $('.association_list_type').each(function() {
            var id = $(this).attr('id');
            $('#' + id + '_list').click(function() {
                $('#' + id + '_modal').modal('show');
            });
            $('#' + id + '_delete').click(function() {
                if (window[id + '_choice']['multiple'])
                {
                    $('#' + id + '_value').html('');
                } else {
                    $('#' + id + '_value').val('');
                }
                $('#' + id).val(null);
            });
        });
        $('.select-line').click(function (e) {
            var id = $(this).attr('target');
            var value = $(this).attr('target-value');
            $('#' + id + '_modal').modal('hide');
            if (window[id + '_choice']['multiple'])
            {
                if ($('#' + id).val() === null || ($('#' + id).val() !== null && $('#' + id).val().indexOf( value ) === -1))
                {
                    $('#' + id + '_value').append('<li item-value="' + value + '">' + window[id + '_choice'][value]['username'] + '<a id="' + id + '_delete_one" target-value="' + value + '"><i class="minus circle icon red"></i></a></li>');
                    var current_value = ($('#' + id).val() !== null ? $('#' + id).val() : []);
                    current_value.push(value);
                    $('#' + id).val(current_value);
                    $('#' + id + '_delete_one[target-value="' + value + '"]').click(function() {
                        var value = $(this).attr('target-value');
                        var current_value = $('#' + id).val();
                        current_value.splice(current_value.indexOf(value), 1);
                        $('#' + id).val(current_value);
                        $('li[item-value="' + value + '"]').remove();
                    });
                }
            } else {
                $('#' + id + '_value').val(window[id + '_choice'][value]['username']);
                $('#' + id).val(value);
            }
        });
    }
});