// Import styles
import './scss/app.scss';

// Import scripts
import 'bootstrap/js/src/dropdown';

$('.oficio').on('click',function(){
	$('.header_contact').addClass('active');
	$('.header_contact__bull').addClass('active');
});

$('.header_contact__bull').on('click',function(){
	$('.header_contact').removeClass('active');
	$('.header_contact__bull').removeClass('active');
});