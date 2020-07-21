import '../scss/how_to_play.scss';

const list_categories = jQuery('.list-categories');
const list_pages = jQuery('.list-pages');

list_categories.find('a').click(function (event) {
    event.preventDefault();
    let category = jQuery(this).data('category');
    const _this = jQuery(this);
    jQuery.ajax({
        url: '/wp-admin/admin-ajax.php',
        method: 'get',
        data: {
            action: 'how_to_play',
            category: category
        },
        success: function (resp) {
            list_categories.find('li').removeClass('active');
            _this.parents('li').addClass('active');
            list_pages.css('height', list_pages.height());
            list_pages.fadeOut({
                complete: function () {
                    list_pages.html(resp);
                    list_pages.fadeIn({
                        complete: function () {
                            list_pages.css('height', 'auto');
                        }
                    });
                }
            });
        }
    });
});