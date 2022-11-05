$(document).ready(function () {
    $('.check-normativa').click(function (e) {
        alert('Debe leer la normativa sobre el TFM');
    })

    $('.btn-accion').click(function () {

        var accion = $(this).data('accion');
        var cod = $(this).data('cod');
        var pertecenencia = $(this).data('flg');
        var plan_trabajo = $(this).data('plan_trabajo');

        if (accion !== '' && cod !== undefined && pertecenencia !== undefined && plan_trabajo !== undefined) {
            document.solicitudForm.accion.value = accion;
            document.solicitudForm.cod_oferta.value = cod;
            document.solicitudForm.flg.value = pertecenencia;
            document.solicitudForm.plan_trabajo.value = plan_trabajo;
            document.solicitudForm.action = 'guardar-solicitud-oferta';

            if (accion != 'anular') {
                document.solicitudForm.submit();
            }
        } else {
            alert('Error en la solicitud');
        }
    })


    $('.btn-modal-solicitar').click(function () {
        var accion = $(this).data('accion');
        var cod = $(this).data('cod');
        var pertecenencia = $(this).data('flg');
        document.solicitudForm.accion.value = accion;
        document.solicitudForm.cod_oferta.value = cod;
        document.solicitudForm.flg.value = pertecenencia;
        document.solicitudForm.action = 'guardar-solicitud-oferta';
    })


    $('#plan_trabajo_modal').change(function () {

        var plan = $(this).find(":selected").val();
        document.solicitudForm.plan_trabajo.value = plan;
        document.solicitudForm.action = 'guardar-solicitud-oferta';

    })

    $('.btn-submit-modal').click(function () {
        document.solicitudForm.submit();
    })

    $('.btn-submit-modal-proponer').click(function () {
        document.proponerForm.submit();
    })

});

