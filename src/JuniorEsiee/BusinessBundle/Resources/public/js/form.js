$(function(){
    $('.ui.dropdown')
        .dropdown()
    ;

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
});