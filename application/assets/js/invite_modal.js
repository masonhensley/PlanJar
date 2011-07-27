$(function () {
    initialize_invite_modal();
})

function initialize_invite_modal() {
    // Close click handler
    $('#close_invite_modal').click(function () {
        reset_invite_modal();
        
        $('#invite_modal').hide('fast');
    });
    
    // Draggable
    $('#invite_modal').draggable({
        handle: '#invite_modal .title_bar'
    });
    
    // In-field label
    $('#invite_modal .in-field_block label').inFieldLabels();
}

function open_invite_modal(priv_type, invite_type) {
    $('#invite_modal').show('fast');
}

function reset_invite_modal() {
    
}