jQuery(document).ready(($)=>{ 
    //how it works slider
    if($('#howSlider').length) {
        $('#howSlider').slick({
            lazyLoad: 'ondemand',
            slidesToShow: 1,
            centerMode: false,
            variableWidth: false,
            autoplay: true,
            arrows: false, 
            dots: true
        });
    }

     //say about us slider
     if($('#sayAboutSlider').length) {
        $('#sayAboutSlider').slick({
            slidesToShow: 1,
            centerMode: false,
            variableWidth: false,
            autoplay: true,
            arrows: true, 
            prevArrow: '<button class="slide-arrow prev-arrow"><img class="lazy" src="" data-src="/wp-content/uploads/2022/03/Path-10.png" /></button>',
            nextArrow: '<button class="slide-arrow next-arrow"><img class="lazy" src="" data-src="/wp-content/uploads/2022/03/Path-9.png" /></button>',
        
        });
    }
});