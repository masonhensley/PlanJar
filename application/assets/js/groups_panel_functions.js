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
    $(ul_element + ' li').click(function() {
        console.log(this);
        if ($(this).hasClass('group_selected')) {
            $(this).removeClass('group_selected');
        } else {
            $(this).addClass('group_selected');
        }
    });
    
//        // Set up the Selectable instance with default options (the shown
//        // options are to keep the last selected item from disappearing.
//        $(ul_element).selectable({
//            selected: function(event, ui) {
//                if ($(ui.selected).hasClass('group_label')) {
//                    // Dissalow group label divs from being selected.
//                    $(ui.selected).removeClass('ui-selected');
//                } else {
//                    $(ui.selected).addClass('my-selected');
//                }
//            },
//            unselected: function(event, ui) {
//                $(ui.unselected).removeClass('my-selected');
//            },
//            // Disallow group label divs from being selected.
//            selecting: function(event, ui) {
//                if ($(ui.selecting).hasClass('group_label')) {
//                    $(ui.selecting).removeClass('ui-selecting');
//                }
//            }
//        });
//        // The following instantiation was pulled from
//        // http://forum.jquery.com/topic/ui-selectable-allow-select-multiple-without-lasso
//        $(ul_element).selectable({
//            selected: function (event, ui) {
//                var e= $(ui.selected);
//                if (e.hasClass('group_label')) {
//                    // Dissalow group label divs from being selected.
//                    e.removeClass('ui-selected');
//                } else {
//                    if (e.hasClass('my-selected')) {
//                        e.removeClass('my-selected');
//                        e.removeClass('ui-selected');
//                    } else {
//                        e.addClass('my-selected');
//                        e.addClass('ui-selected');
//                    }
//                }
//            },
//            unselected: function (event, ui) {
//                var e= $(ui.unselected);
//                if (e.hasClass('my-selected')) {
//                    e.addClass('my-selected');
//                    e.addClass('ui-selected');
//                } else {
//                    e.removeClass('ui-selected');
//                    e.removeClass('my-selected');
//                }
//            },
//        });
}

function create_selectables(mode) {
    create_selectable('#friends_group');
    create_selectable('#joined_groups');
    create_selectable('#followed_groups');
}