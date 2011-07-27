function initialize_invite_modal() {
    $('#close_invite_modal').click(function () {
        reset_invite_modal();
        
        $('#invite_modal').hide('fast');
    });
}

function open_invite_modal(priv_type, invite_type) {
    $('#invite_modal').show('fast');
}

function reset_invite_modal() {
    
}