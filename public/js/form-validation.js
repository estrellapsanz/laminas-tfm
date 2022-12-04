var FormValidation = function () {

    // basic validation
    var SolicitudAltaOfertaForm = function () {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form = $('form[name=proponerOfertaForm]');
        var error1 = $('.error-form', form);
        var success1 = $('.success-form', form);

        var msg = 'Campo requerido.';
        var msgacp = 'Seleccione sólo solo los ficheros con los tipos requeridos.';
        var msg_consentimiento = 'Es necesario que marque el consentimiento para realizar la solicitud.';

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ":disabled, :hidden", // validate all fields including form hidden input
            messages: {
                area: {required: msg},
                titulo: {required: msg},
                subtitulo: {required: msg},
                descripcion: {required: msg},

            },
            rules: {
                area: {required: true},
                titulo: {required: true},
                subtitulo: {required: true},
                descripcion: {required: true},
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                //success1.hide();
                error1.show();
                //document.scrollTo(error1, -200);
            },
            errorPlacement: function (error, element) { // render error placement for each input type
                error.insertAfter(element); // for other inputs, just perform default behavior
                $(element).closest('div').find('.form-control').addClass('error-border');
                $(element).closest('div').find('label').addClass('help-block-error');
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {

                console.log('validated');
                var isValid = true;
                //success1.show();
                error1.hide();
                alert(123);

            }
        });

    };

    return {
        //main function to initiate the module
        init: function () {
            SolicitudAltaOfertaForm();
        }
    };
}();