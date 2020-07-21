import '../scss/faq.scss';
function add(order,atrib){
	let num = parseInt(atrib) + 1;
	jQuery.ajax({
	    url: '/wp-admin/admin-ajax.php',
	    method: 'get',
	    data: {
	        action: 'sendpositivevaloration',
	        order: parseInt(order),
			value: num
	    },
	    success: function (resp) {
	    	jQuery('.question').eq(parseInt(order)-1).find('.front-answer').hide();
	    	jQuery('.question').eq(parseInt(order)-1).find('.back-answer').show();
	    }
	});
}
function remove(order,atrib){
	let num = parseInt(atrib) + 1;
	jQuery.ajax({
	    url: '/wp-admin/admin-ajax.php',
	    method: 'get',
	    data: {
	        action: 'sendnegativevaloration',
	        order: parseInt(order),
			value: num
	    },
	    success: function (resp) {
	    	jQuery('.question').eq(parseInt(order)-1).find('.front-answer').hide();
	    	jQuery('.question').eq(parseInt(order)-1).find('.back-answer').show();
	    }
	});
}

jQuery('.yes').on('click',function(){
	let thisA = jQuery(this);
	add(thisA.attr('order'),thisA.attr('value'));
});
jQuery('.no').on('click',function(){
	let thisA = jQuery(this);
	remove(thisA.attr('order'),thisA.attr('value'));
});
