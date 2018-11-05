jQuery.noConflict();

Forms = {
    sendForm: function (context) {
        console.log('Forms.sendForm');
        var
            form = jQuery(context).closest('form'),
            requestFormDiv = jQuery(context).closest('.request-form'),
            requestMsgDiv = jQuery(context).closest('.wprt-contact-form').find('.sucess-meg'),
            formData = form.serializeArray(),
            nonce = FormsData.nonce,
            postData = {
                security: nonce,
                formData: formData,
                action: 'HandlerSendForms'
            }
            ;

        jQuery.ajax({
            type: "POST",
            url: formsPluginUrl.url,
            data: postData,
            success: function (data) {
                requestFormDiv.hide();
                requestMsgDiv.show();
            }
        });
    },
};