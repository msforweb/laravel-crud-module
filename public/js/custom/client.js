/* quick updtae the clients notes when they write with te ajax call.*/
function UpdateNotes(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery.jAjaxCall({
        send_data: jQuery('#client-notes').serializeArray(),
        xhr_area: 'Client',
        xhr_module: 'UpdateNotes',
        xhr_action: '',
        xhr_url: Url_GAR,
        xhr_timeout :   (1000*25),
        fn_alert: function(mt, m){
        },
        callbefore_send: function (jqXHR, settings) {
        },
        callback_on_success: function (data, textStatus, jqXHR) {
        },
        callback_on_error: function (jqXHR, textStatus, errorThrown) {},
        callback_on_complete: function (jqXHR, textStatus) {}
    }, 'updatenotes');

}
jQuery(document).ready(function () {

    /* Open notes in the modal popup*/
    $('#modal-popup-sm').on('shown.bs.modal', function (e) {

        if(jQuery('#cm_notes').length > 0)
        {
            var magicalTimeout=3000;
            var timeout;
            $('#cm_notes').keyup('keyup',function(event) {
                var form=this
                clearTimeout(timeout);
                timeout=setTimeout(function(){
                    UpdateNotes();
                    console.log("call ajax");
                },magicalTimeout)
            });
        }

    });
    $("#modal-popup-sm").on("hidden.bs.modal", function () {
        if(jQuery('#cm_notes').length > 0) {
            UpdateNotes();
            window.location.reload();
        }
    });

});