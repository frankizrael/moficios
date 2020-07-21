import '../scss/tutorial.scss';

import 'slick-carousel';

jQuery('.slider').slick({
    lazyLoad: 'ondemand',
    prevArrow: '<div class="arrow prev">‹</div>',
    nextArrow: '<div class="arrow next">›</div>',
    dots: true,
    responsive: [
        {
            breakpoint: 990,
            settings: {
                arrows: false,
                dots: true
            }
        }
    ]
});