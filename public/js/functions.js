$(document).ready(function () {
    $('.check-normativa').click(function () {
        alert('Debe leer la normativa sobre el TFM');
    })

    $('.btn-accion').click(function () {
        var accion = $(this).data('accion');
        var cod = $(this).data('cod');
      
        if (accion !== '') {
            document.solicitudForm.accion.value = accion;
            document.solicitudForm.cod_oferta.value = cod;
            document.solicitudForm.action = 'guardar-solicitud-oferta';
            document.solicitudForm.submit();
        }
    })


});