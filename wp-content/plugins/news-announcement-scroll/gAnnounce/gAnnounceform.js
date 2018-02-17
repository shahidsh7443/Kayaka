function _gAnnounce() {
	if ( ( document.gAnnouncefrm.gNews_text.value ).trim() == "" ) {
		alert( "Please enter the news." );
		document.gAnnouncefrm.gNews_text.focus();
		return false;
	} else if ( ( document.gAnnouncefrm.gNews_order.value ).trim() =="" ) {
		alert( "Please enter the display order." )
		document.gAnnouncefrm.gNews_order.focus();
		return false;
	} else if ( isNaN( document.gAnnouncefrm.gNews_order.value ) ) {
		alert( "Please enter the display order in number." );
		document.gAnnouncefrm.gNews_order.focus();
		document.gAnnouncefrm.gNews_order.select();
		return false;
	} else if ( ( document.gAnnouncefrm.gNews_type.value ).trim() == "" ) {
		alert( "Please enter the type(This is to group the announcement records)." );
		document.gAnnouncefrm.gNews_type.focus();
		return false;
	} else if ( document.gAnnouncefrm.gNews_status.value == "" ) {
		alert( "Please select the display status." );
		document.gAnnouncefrm.gNews_status.focus();
		return false;
	}
	escapeVal( document.gAnnouncefrm.gNews_text,'<br>' );
}
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}

function escapeVal( textarea, replaceWith ) {
	textarea.value = escape( textarea.value ) //encode textarea strings carriage returns
	for ( i = 0; i < textarea.value.length; i++ ) {
		//loop through string, replacing carriage return encoding with HTML break tag
		if( textarea.value.indexOf( "%0D%0A" ) > -1 ) {
			//Windows encodes returns as \r\n hex
			textarea.value = textarea.value.replace( "%0D%0A", replaceWith );
		} else if ( textarea.value.indexOf( "%0A" ) > -1 ) {
			
			//Unix encodes returns as \n hex
			textarea.value = textarea.value.replace( "%0A",replaceWith );
		} else if ( textarea.value.indexOf( "%0D" ) > -1 ) {
			
			//Macintosh encodes returns as \r hex
			textarea.value = textarea.value.replace( "%0D", replaceWith );
		}
	}
	textarea.value = unescape( textarea.value ); //unescape all other encoded characters
}

function ConvertBR( input ) {
	var output = "";
	
	for ( var i = 0; i < input.length; i++ ) {
		if ( ( input.charCodeAt(i) == 13) && ( input.charCodeAt(i + 1) == 10) ) {
			i++;
			output += "";
		} else {
			output += input.charAt(i);
		}
	}
	//return output;
	alert( output );
}

function gNews_redirect() {
	window.location = "options-general.php?page=news-announcement-scroll";
}

function gNews_gHelp() {
	window.open( "https://wordpress.org/plugins/news-announcement-scroll/faq/" );
}

function gNews_delete( id ) {
	if ( confirm( "Do you want to delete this record?" ) ) {
		document.frm_gNews_display.action = "options-general.php?page=news-announcement-scroll&ac=del&rand=76mv1ojtlele176mv1ojtlele1&did="+id;
		document.frm_gNews_display.submit();
	}
}
