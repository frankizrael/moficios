import '../scss/single_promotion.scss';
import "swiper/swiper-bundle.min.css";
import Swiper from "swiper";
import Swal from 'sweetalert2';
var swiper = new Swiper('.img-trabajos .swiper-container', {
  slidesPerView: 4,
  spaceBetween: 10,
  loop: true,
  pagination: {
    el: '.img-trabajos .swiper-pagination',
    clickable: true,
  }
});

$('.calificacion-stars .stars').on('mouseenter',function(){
	let data = $(this).attr('data');
	let datareal = parseInt(data)+1;
	$('.calificacion-stars .stars').removeClass('active');
	for (let u=0;u<datareal;u++){
		$('.calificacion-stars .stars').eq(u).addClass('active');
	}
});

$('.calificacion-stars .stars').on('mouseleave',function(){
	$('.calificacion-stars .stars').removeClass('active');
});

$('.calificacion-stars .stars').on('click',function(){
	let data = $(this).attr('data');
	let datareal = parseInt(data)+1;
	$('.calificacion-stars .stars').removeClass('active');
	$('.calificacion-stars .stars').removeClass('active_fijo');
	for (let u=0;u<datareal;u++){
		$('.calificacion-stars .stars').eq(u).addClass('active_fijo');
	}
	//crear variable nena
	$('.calificacion-stars').attr('data-esc',datareal);
});

$('#jsCancell').on('click',function(){
	$('#comment_textarea').val();
	$('.calificacion-stars .stars').removeClass('active');
	$('.calificacion-stars .stars').removeClass('active_fijo');
	$('.calificacion-stars').attr('data-esc','0');
});

$('#jsSend').on('click',function(){
	let id = $(this).attr('data-user');
	let id_pro = $(this).attr('data-pro');
	let nameuser = $(this).attr('data-nameuser');
	let namepro = $(this).attr('data-namepro');	
	let textarea = $('#comment_textarea').val();
	let stars = $('.calificacion-stars').attr('data-esc');
	
	$.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'addvaloration',
            comment: textarea,
        	valoration: stars,
        	id_user: id,
        	id_pro: id_pro,
        	nameuser: nameuser,
			namepro: namepro
        },
        success: function (resp) {
        	location.reload();
        }
    });
});