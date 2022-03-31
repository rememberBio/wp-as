jQuery(document).ready(($)=>{ 

    const bodyRTL = jQuery("body.rtl");
    //how it works slider
    if($('#howSlider').length) {
        let slickAttrs = {
            lazyLoad: 'ondemand',
            slidesToShow: 1,
            centerMode: false,
            variableWidth: false,
            autoplay: true,
            arrows: false, 
            dots: true       
        };
        if(bodyRTL.length) slickAttrs['rtl'] = true;
        $('#howSlider').slick(slickAttrs);
    }

     //say about us slider
     if($('#sayAboutSlider').length) {
        let slickAttrs = {
            slidesToShow: 1,
            centerMode: false,
            variableWidth: false,
            autoplay: true,
            arrows: true, 
            prevArrow: '<button class="slide-arrow prev-arrow"><img src="/wp-content/uploads/2022/03/Path-10.png" /></button>',
            nextArrow: '<button class="slide-arrow next-arrow"><img src="/wp-content/uploads/2022/03/Path-9.png" /></button>',
        };
        if(bodyRTL.length) slickAttrs['rtl'] = true;
        $('#sayAboutSlider').slick(slickAttrs);
    }
});