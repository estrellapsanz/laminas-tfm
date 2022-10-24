$(document).ready(function () {
    $('.check-normativa').click(function (e) {
        alert('Debe leer la normativa sobre el TFM');
    })

    $('.btn-accion').click(function () {
        var accion = $(this).data('accion');
        var cod = $(this).data('cod');
        var pertecenencia = $(this).data('flg');

        if (accion !== '') {
            document.solicitudForm.accion.value = accion;
            document.solicitudForm.cod_oferta.value = cod;
            document.solicitudForm.flg.value = pertecenencia;
            document.solicitudForm.action = 'guardar-solicitud-oferta';
            document.solicitudForm.submit();
        }
    })


});