import '../scss/promotions.scss';

const promotions = jQuery('#promotions');
promotions.on('click', '.pagination .nav a', function (event) {
    event.preventDefault();
    const url = jQuery(this).attr('href');
    jQuery.ajax({
        url: url,
        success: function (resp) {
            promotions.find('.pagination').remove();
            promotions.find('.content').append(resp);
        }
    });
});

const filters = jQuery('.filters');
filters.on('click', 'a:not(.active)', function (event) {
    event.preventDefault();
    const _this = jQuery(this);
    const url = _this.attr('href');
    const cat = _this.data('cat');
    jQuery.ajax({
        url: url,
        data: { cat },
        success: function (resp) {
            filters.find('a').removeClass('active');
            _this.addClass('active');
            promotions.find('.content').css('height', promotions.find('.content').height());
            promotions.find('.content').fadeOut({
                complete: function () {
                    promotions.find('.content').html(resp);
                    promotions.find('.content').fadeIn();
                    promotions.find('.content').css('height', 'auto');
                }
            });
        }
    });
});