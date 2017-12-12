
var fue = new Class.create();


fue.prototype = {
	initialize : function(event_id, status_id, event_name_id) {
		this.event_id = event_id;
		this.status_id = status_id;
		this.event_name_id = event_name_id;
	},
	
	
	setNameForEvent : function (event) {
		var when = $(this.event_id).getValue();
		if (when == 'sales_order_save_after') {
			var event_name = "Order is " + $(this.status_id).getValue();
			$(this.event_name_id).setValue(event_name);

		}
	},
	reloadCondition :function(event) {
	var event = 	$('fue_event').getValue();
	if (event =='sales_order_save_after') {
		$$('.order_condition').each(function(ele) {
			$(ele).show();
		}) ;
	} else if (event =='customer_register_success' || event == 'customer_save_before'
		|| event =='customer_login' || event == 'customer_logout' || event =='newsletter_subscriber_save_commit_after'
			|| event =='abandoned_cart') {
		$$('.order_condition').each(function(ele) {
			$(ele).hide();
			var text = $('fue_event')[$('fue_event').selectedIndex].text;

			$('fue_envent_name').setValue(text);
			
		}) ;
	} else if (event=='wishlist_share' || event =='wishlist_item_add') {
		$$('.order_condition').each(function(ele) {
			$(ele).hide();
		}) ;
		var text = $('fue_event')[$('fue_event').selectedIndex].text;

		$('fue_envent_name').setValue(text);

		$$('.wishlist').each(function(ele) {
			$(ele).show();
		}) ;
	}
		
	}
}
