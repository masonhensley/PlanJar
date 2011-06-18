$(function() {
    // Set up the Selectable instance with default options.
    create_selectables();
});

function destroy_selectables() {
    $('#friends_group').selectable('destroy');
    $('#joined_groups').selectable('destroy');
    $('#followed_groups').selectable('destroy');
}

function create_selectable(ul_element) {
    // Make the list tiems selectable.
    $(ul_element + ' li').click(function() {
        if ($(this).hasClass('group_selected')) {
            if ($(ul_element + ' li').hasClass('group_selected') && $(ul_element + ' li') != this) {
                $(this).removeClass('group_selected');
            }
        } else {
            $(this).addClass('group_selected');
        }
    });
}

function create_selectables(mode) {
    create_selectable('#friends_group');
    create_selectable('#joined_groups');
    create_selectable('#followed_groups');
}