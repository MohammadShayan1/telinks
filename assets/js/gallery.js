$(document).ready(function() {
    // Initialize Isotope
    var $portfolioItem = $('.portfolio-item').isotope({
        itemSelector: '.item',
        layoutMode: 'fitRows'
    });

    // Filter items on button click
    $('.portfolio-menu ul li').click(function() {
        $('.portfolio-menu ul li').removeClass('active');
        $(this).addClass('active');
        
        var selector = $(this).attr('data-filter');
        $portfolioItem.isotope({
            filter: selector
        });
        return false;
    });

    // Initialize Magnific Popup
    var popup_btn = $('.popup-btn');
    popup_btn.magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });
});
