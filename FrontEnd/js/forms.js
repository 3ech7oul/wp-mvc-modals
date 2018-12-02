jQuery.noConflict();

Forms = {
    serviceId: null,
    sendForm: function (context) {
        console.log('Forms.sendForm');
        var
            form = jQuery(context).closest('form'),
            requestFormDiv = jQuery(context).closest('.request-form'),
            requestMsgDiv = jQuery(context).closest('.wprt-contact-form').find('.sucess-meg'),

            nonce = FormsData.nonce
            ;

        if ( (requestFormDiv.data('action') !== undefined) &&(Forms.serviceId !== null) ) {
            $('<input />').attr('type', 'hidden')
                .attr('name', "serviceId")
                .attr('value', Forms.serviceId)
                .appendTo(form);
        }

        var
            formData = form.serializeArray(),
            postData = {
            security: nonce,
            formData: formData,
            action: 'HandlerSendForms'
        };

        requestFormDiv.hide();
        requestMsgDiv.show();
        jQuery.ajax({
            type: "POST",
            url: formsPluginUrl.url,
            data: postData,
            async: true,
            success: function (data) {
                //console.log(data)
            }
        });
    },
};