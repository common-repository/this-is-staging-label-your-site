jQuery(document).ready(function(){

	//color picker
    jQuery('#tis-label-box-color,#tis-label-text-color').wpColorPicker();

    //hide/show advances front end options
    jQuery('input[name=tis-front-end]').on('change', function(){
    	if (jQuery(this).val()==0) {
    		jQuery('.tis-front-end-advanced-option').hide('slow');
    	}
    	else {
    		jQuery('.tis-front-end-advanced-option').show('slow');
    	}
    });

    //hide/show users id list
    jQuery('input[name=tis-visibility]').on('change', function(){
    	if (jQuery(this).val()==3) {
    		jQuery('#tis-list-of-users').show('slow');
    	}
    	else {
    		jQuery('#tis-list-of-users').hide('slow');
    	}
    });

    //Ajax
    jQuery('#tis-save-settings').on('click', function(){
    	
    	jQuery(".succes_log").hide('slow');
    	jQuery(".overall-error").hide('slow');

    	var valid_users_ids = jQuery('#tis-list-of-users').val();
    	var regext 	= /^[0-9.,]+$/;
    	var visibility = jQuery('input[name=tis-visibility]:checked').val();

    	if (valid_users_ids !='' && visibility==3) {
    		if (!regext.test(valid_users_ids)) {
    			alert(trans_str.wrong_user_id);
    			return false;
    		}
    	} else {
    		valid_users_ids = 0;
    	}

    	if (visibility==3 && valid_users_ids =='') {
    		alert(trans_str.no_ids);
    		return false;
    	}

	    var error   = '';
	    var options = {
		    label_name:	  jQuery('#tis-label-name').val(),
		    box_color:    jQuery('#tis-label-box-color').val(),
			text_color:	  jQuery('#tis-label-text-color').val(),
			admin_enable: jQuery('input[name=tis-adminbar]:checked').val(),
			front_enable: jQuery('input[name=tis-front-end]:checked').val(),
			position:	  jQuery('input[name=tis-front-position]:checked').val(),
			the_spartan:  jQuery('input[name=tis-spartan]:checked').val(),
			visibility:   visibility,
			userslist: 	  valid_users_ids,
		}

	    var the_data = {
	    	action: 'tis_update_options',
	    	data: options
	    }

	    jQuery.ajax({
	    	url: tis_ajax.ajax_url,
	    	data: the_data,
	    	type: "post",
	    	success: function (response){

	    		if (response==1) {
	    			jQuery(".succes_log").show('slow');
	    			jQuery(".succes_log").text(trans_str.success);
	    		}
	    		else{
	    			jQuery(".overall-error").show('slow');
	    			jQuery(".overall-error").text(response);
	    		}
				jQuery("html, body").animate({scrollTop:0}, '500')
	    	},
	    	error: function() {
	    		jQuery('#error').text(trans_str.err_err);
	    	}
	    });

	    return false;
	});

});
