import '../scss/last_results.scss';
import jQuery from 'jquery';

const max_results = parseInt(jQuery('#max_results').text());
let paged = jQuery('#paged');

const pagination = jQuery('.pagination');

function changeDataFunction(){
    jQuery('.jsVideoEmisor').on('click',function(e){
        e.preventDefault();
        let $this = jQuery(this);
        let iframe = $this.closest('.jsResult').find('.jsVideoReceptor').attr('data-iframe');
        let date = $this.closest('.jsResult').find('.jsVideoReceptor').attr('data-date');
        let title = $this.closest('.jsResult').find('.jsVideoReceptor').attr('data-title');
        let description = $this.closest('.jsResult').find('.jsVideoReceptor').attr('data-description');
        jQuery('.videoJs').html('');
        jQuery('.videoJs').html(iframe);
        jQuery('.dateJs').html('');
        jQuery('.dateJs').html(date);
        jQuery('.titleJs').html('');
        jQuery('.titleJs').html(title);
        jQuery('.contentJs').html('');
        jQuery('.contentJs').html(description);
    });
}

pagination.on('click', '.next-pagination', function (event) {
    event.preventDefault();
    let next_page = parseInt(paged.text()) + 1;
    let term = jQuery(this).data('term');
    jQuery.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'last_results',
            term_id: term,
            paged: next_page
        },
        success: function (resp) {
            jQuery('.last-results-list').html(resp);
            paged.text(next_page);
            jQuery('.prev-pagination').removeClass('disabled');
            if (next_page === max_results) {
                jQuery('.next-pagination').addClass('disabled');
            }
            changeDataFunction();
        }
    })
});

pagination.on('click', '.prev-pagination', function (event) {
    event.preventDefault();
    let prev_page = parseInt(paged.text()) - 1;
    let term = jQuery(this).data('term');
    jQuery.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'last_results',
            term_id: term,
            paged: prev_page
        },
        success: function (resp) {
            jQuery('.last-results-list').html(resp);
            paged.text(prev_page);
            jQuery('.next-pagination').removeClass('disabled');
            if (prev_page === 1) {
                jQuery('.prev-pagination').addClass('disabled');
            }
            changeDataFunction();
        }
    })
});

jQuery('#search').on('keypress',function(){
    let val = jQuery('#search').val();
    let term = jQuery('#search').attr('data-term');
    if (val.length > 2) {
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'get',
            data: {
                action: 'all_result',
                term_id: term,
                title: val
            },
            success: function (resp) {
                jQuery('#post-list').html('');
                jQuery('#post-list').html(resp);
                changeDataFunction();
            }
        });
    } else {
        console.log(val.length);
        jQuery('#post-list').html('');
    }    
});

changeDataFunction();