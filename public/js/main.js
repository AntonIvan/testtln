$(document).ready(function(){
    $('.collapsible').collapsible();

    $('#check').click( function () {
       inn = $('#inn').val();

        if(inn === "") {
            alert("Заполните все поля");
            return false;
        }
        $.ajax({
            type: 'POST',
            cache: false,
            data: { inn: inn},
            url: '/api/check',
            success: function (data) {
                alert(data);
            },
            error: function (data) {
                alert("Произошла ошибка");
            }
        });
    });


});
