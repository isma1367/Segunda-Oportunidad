//console.log($("#contenedor_fotos").html());

$("#contenedor_fotos img").click(function(e) {

    var img = $(this);
    if (img.hasClass('active')) {
        img.removeClass('active');
    } else {
        img.addClass('active');
    }

    var input = $(this).next()
    if(input.is(":checked"))
        input.prop('checked',false);
    else
        input.prop('checked',true);
});
