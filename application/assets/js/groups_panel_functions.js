$(function() {
    // Set up the Selectable instance with default options.
    create_selectables('standard');
    
    // Initialize the group buttonset (select one/select multiple).
    $('div .radio').buttonset();
});

function destroy_selectables() {
    $('#friends_group').selectable('destroy');
    $('#joined_groups').selectable('destroy');
    $('#followed_groups').selectable('destroy');
}

function create_selectable(ul_element, mode) {
    if (mode == 'standard') {
        // Set up the Selectable instance with default options (the shown
        // options are to keep the last selected item from disappearing.
        $(ul_element).selectable({
            selected: function(event, ui) {
                if ($(ui.selected).hasClass('group_label')) {
                    // Dissalow group label divs from being selected.
                    $(ui.selected).removeClass('ui-selected');
                } else {
                    $(ui.selected).addClass('my-selected');
                }
            },
            unselected: function(event, ui) {
                $(ui.unselected).removeClass('my-selected');
            },
            // Disallow group label divs from being selected.
            selecting: function(event, ui) {
                if ($(ui.selecting).hasClass('group_label')) {
                    $(ui.selecting).removeClass('ui-selecting');
                }
            }
        });
    } else {
        // The following instantiation was pulled from
        // http://forum.jquery.com/topic/ui-selectable-allow-select-multiple-without-lasso
        $(ul_element).selectable({
            selected: function (event, ui) {
                var e= $(ui.selected);
                if (e.hasClass('group_label')) {
                    // Dissalow group label divs from being selected.
                    e.removeClass('ui-selected');
                } else {
                    if (e.hasClass('my-selected')) {
                        e.removeClass('my-selected');
                        e.removeClass('ui-selected');
                    } else {
                        e.addClass('my-selected');
                        e.addClass('ui-selected');
                    }
                }
            },
            unselected: function (event, ui) {
                var e= $(ui.unselected);
                if (e.hasClass('my-selected')) {
                    e.addClass('my-selected');
                    e.addClass('ui-selected');
                } else {
                    e.removeClass('ui-selected');
                    e.removeClass('my-selected');
                }
            },
            // Disallow group label divs from being selected.
            selecting: function(event, ui) {
                if ($(ui.selecting).hasClass('group_label')) {
                    $(ui.selecting).removeClass('ui-selecting');
                }
            }
        });
    }
}

function create_selectables(mode) {
    create_selectable('#friends_group', mode);
    create_selectable('#joined_groups', mode);
    create_selectable('#followed_groups', mode);
}


// Should be called when #sel_one or #sel_mult
// Set up the Selectable instance with "standard" options or toggle options.
function toggle_group_select() {
    if ($('#sel_one').attr('checked') == 'checked') {
        // Set up the Selectable instances with standard options.
        destroy_selectables();
        create_selectables('standard');
    } else {
        // Set up the Selectable instances with custom options.
        destroy_selectables();
        create_selectables('custom');
    }
}