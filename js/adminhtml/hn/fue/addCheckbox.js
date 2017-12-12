/**
 * Created by root on 29/09/2015.
 */

function addCheckboxForFUE() {
    var checkbox = jQuery("<tr><td> Send Follow up email to wishlist's owner</td> <td><input id='send-fue' type='checkbox' name='send_fue' /></td> </tr>");

    if(!( jQuery('#send-fue').length > 0 ) )
    jQuery('#special_price').parent().parent().after(checkbox);
}


function hideOrderFields() {
      //jQuery('#fue_condition_status').parent().parent().hide();
      //jQuery('#fue_condition_status').parent().parent().hide();

    jQuery('.fue_condition_status').hide();
}



jQuery(document).ready(function() {
    jQuery('#special_price').keydown(function() {
        addCheckboxForFUE();
    });
    jQuery('#special_to_date').keydown(function() {
        addCheckboxForFUE();
    });
    jQuery('#special_to_date').change(function() {
        addCheckboxForFUE();
    });
});
