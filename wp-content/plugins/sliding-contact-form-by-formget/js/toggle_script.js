jQuery(window).load(function() {
  jQuery('.fg_left_form_container').css("display", 'block');
  jQuery('.fg_right_form_container').css("display", 'block');
  jQuery('.fg_bottom_form_container').css("display", 'block');
    jQuery('.left_toggle_first').click(function() {
        jQuery('.fg_left_form_container').toggleClass("open");
        e.preventDefault();
    });
    jQuery(".fg_left_form_container").toggle().animate({"right": "+=0", width: 'toggle', easing: 'swing'}, 2000, 'swing');

    jQuery('.right_toggle_first').click(function() {
        jQuery('.fg_right_form_container').toggleClass("open");
        e.preventDefault();
    });
    jQuery(".fg_right_form_container").toggle().animate({"left": "+=0", width: 'toggle', easing: 'swing'}, 2000, 'swing');

    jQuery('.bottom_toggle_first').click(function() {
        jQuery('.fg_bottom_form_container').toggleClass("open");
        e.preventDefault();
    });
    jQuery(".fg_bottom_form_container").toggle().animate({"bottom": "+=0", height: 'toggle', easing: 'swing'}, 2000, 'swing');
    var screen_height = window.screen.height;
    var mid_height = screen_height / 2 - 300;
	//var contact_button_height = jQuery('.fg_right_contact_button').height();
	if(jQuery('.fg_right_contact_button').height() < 250){
    var button_display_height = screen_height / 2 - 175;
	}
	else {
	var button_display_height = screen_height / 2 - 250;
	}
	if(jQuery('.fg_left_contact_button').height() < 250){
    var button_display_height = screen_height / 2 - 175;
	}
	else {
	var button_display_height = screen_height / 2 - 250;
	}
    jQuery('div.fg_left_form_display').css("margin-top", mid_height + "px");
    jQuery('div.fg_left_contact_button').css("margin-top", button_display_height + "px");
    jQuery('.fg_right_form_display').css("margin-top", mid_height + "px");
    jQuery('div.fg_right_contact_button').css("margin-top", button_display_height + "px");
 });