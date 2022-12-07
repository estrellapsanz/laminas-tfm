$(document).ready(function () {

    $('button.leyenda').click(function (e) {
        e.stopImmediatePropagation();
        if ($('.mycollapse').prop('hidden'))
            $('.mycollapse').prop('hidden', false);
        else
            $('.mycollapse').prop('hidden', true);
        return;
    })

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

    //guardar modal confirmar: oferta y deposito
    $('.btn-submit-modal').click(function () {

        var nota = $('input[name="nota_modal"]');
        var obs_modal = $('textarea[name="observaciones_modal"]');
        var obs_modal_denegar_oferta = $('textarea[name="observaciones_denegacion_oferta_modal"]');

        //feedback tramitar deposito
        if (obs_modal !== undefined && obs_modal !== '' && !obs_modal.prop('disabled')) {
            document.solicitudForm.observaciones.value = obs_modal.val();
        }

        //feedback denegar oferta
        if (obs_modal_denegar_oferta !== undefined && obs_modal_denegar_oferta !== '' && !obs_modal_denegar_oferta.prop('disabled')) {
            document.solicitudForm.observaciones_denegacion_oferta.value = obs_modal_denegar_oferta.val();
        }

        //tramitar deposito: autorizar
        if (nota !== undefined && !nota.prop('disabled')) {
            if (nota.val() !== null && nota.val() !== "" && nota.val() >= 0 && nota.val() <= 10) {
                document.solicitudForm.nota_final.value = nota.val();
            } else {
                $('#label_nota').addClass('text-danger');
                $('#error_modal').attr('hidden', false);
                return;
            }

        } else if (nota == undefined) { //error
            $('#label_nota').addClass('text-danger');
            $('#error_modal').attr('hidden', false);
            return;
        }

        //tramitar ofertar
        document.solicitudForm.submit();
    })

    //eliminar oferta
    $('.eliminar-oferta').click(function () {
        var accion = $(this).data('accion');
        var cod = $(this).data('cod');
        document.eliminarForm.accion.value = accion;
        document.eliminarForm.cod_oferta.value = cod;
    })

    $('.btn-submit-modal-eliminar').click(function () {
        document.eliminarForm.submit();
    })
    //fin eliminar


    $('.btn-submit-modal-deposito').click(function () {
        document.solicitudDepositoForm.submit();
    })
    //fin eliminar

    //tramitar oferta
    $('.btn-tramitar-estudiante-oferta').click(function () {
        var accion = $(this).data('accion');
        var cod = $(this).data('cod');
        var estudiante = $(this).data('estudiante');

        if (accion == 'Denegado') {
            var modal = $('#confirmarModal');
            if (modal !== undefined) {
                var textarea_obs = $('textarea[name="observaciones_denegacion_oferta_modal"]', modal);
                var div_obs = $('p#denegar_oferta_estudiante', modal);
                textarea_obs.attr('disabled', false);
                div_obs.attr('hidden', false);
            }
        }
        document.solicitudForm.estado.value = accion;
        document.solicitudForm.estudiante.value = estudiante;
        document.solicitudForm.cod_oferta.value = cod;
    })

    $('.btn-submit-modal-proponer').click(function () {
        document.proponerOfertaForm.submit();
    })

    //deposito: botones de accion
    $('.btn-tramitar-estudiante-deposito').click(function () {

        var accion = $(this).data('accion');
        var cod = $(this).data('cod_oferta');
        var cod_solicitud = $(this).data('cod_solicitud');

        if (accion !== 'autorizar') {
            $('#autorizar').attr('hidden', true);
            $('#label_nota').attr('hidden', true);
            $('input[name="nota_modal"]').attr('disabled', true).attr('hidden', true);

            if (accion == 'denegar') {
                $('#denegar').attr('hidden', false);
                $('textarea[name="observaciones_modal"]').attr('hidden', true).prop('disabled', true);
            } else {
                $('#cambios').attr('hidden', false);
                $('textarea[name="observaciones_modal"]').attr('hidden', false).prop('disabled', false);
            }

        } else {
            $('#autorizar').attr('hidden', false);
            $('#label_nota').attr('hidden', false);
            $('input[name="nota_modal"]').attr('hidden', false).attr('disabled', false);
            $('textarea[name="observaciones_modal"]').attr('hidden', true).prop('disabled', true);
        }

        document.solicitudForm.accion.value = accion;
        document.solicitudForm.cod_oferta.value = cod;

        if (cod_solicitud !== undefined)
            document.solicitudForm.cod_solicitud.value = cod_solicitud;

    })

    //al cerrar el modal
    $('.btn-modal-cerrar, .btn-close').click(function () {
        $('#label_nota').removeClass('text-danger');
        $('input[name="nota_modal"]').val('');
        $('#error_modal').attr('hidden', true);

        $('#autorizar').attr('hidden', true);
        $('#denegar').attr('hidden', true);
        $('#cambios').attr('hidden', true);
        $('#denegar_oferta_estudiante').attr('hidden', true);
        $('textarea#observaciones_denegacion_oferta_modal').attr('disabled', true);

    })

    $('select#trabajo_depositar').change(function () {
        var cod_oferta = $(this).val()
        $.ajax({
            type: "POST",
            url: 'get-datos-oferta',
            data: {'cod_oferta': cod_oferta}
        }).done(function (data) {

            if (data !== undefined && data !== null) {
                var descripcion = data['DESCRIPCION'];
                var docente = data['DOCENTE'];

                $('#descripcion').val(descripcion);
                $('#docente').val(docente);
            }
        }).fail(function () {
            console.log('error recuperando oferta');
            alert('error');
        });

    })
});


var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
})
