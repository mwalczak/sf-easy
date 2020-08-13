$(document).ready(function () {
    $('.project_filter select').change(function () {
        $.getJSON('/projects/'+$(this).val()+'/users', function(data){
            let select = $('.project_filter_target select');
            let value = select.val();
            select.empty();
            select.append('<option value=""></option>');
            $.each(data, function(i, user){
                select.append('<option value="'+user.id+'">'+user.email+'</option>');
            });
            select.val(value);
        });
    });
});

