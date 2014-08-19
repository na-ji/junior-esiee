$(function(){
    $('[data-row-action]').click(function(){
        var $this = $(this);
        var $row = $this.parents('tr');
        $row.addClass('disabled');
        $.get($this.attr('data-row-action'), function(){
            $row.removeClass('disabled');
            $row.addClass('positive');
        })
    })
});