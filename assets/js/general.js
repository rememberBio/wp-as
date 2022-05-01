jQuery(document).ready(($)=>{
    $("#toggle-menu-item").click(function(e) {
        $(this).parents(".custom-switchers").toggleClass("opened");
    });

    $(document).on('click touch', function(event) {

        if($('header#generalHeader #site-navigation.toggled').length) {
            if ( !$(event.target).is('button.menu-toggle-header') && !$(event.target).is('button.menu-toggle-header img')) {
                $('header#generalHeader #site-navigation.toggled').removeClass('toggled');
            }
        }

        if($('.custom-switchers.opened').length) {
            if(!$(event.target).is('#toggle-menu-item') && !$(event.target).is('#toggle-menu-item img')) {
                $('.custom-switchers.opened').removeClass("opened");
            }
        }

        if($('header#masthead #site-navigation.toggled').length) {
            if(!$(event.target).is('.button.menu-toggle') && !$(event.target).is('button.menu-toggle img')) {
                $('header#masthead #site-navigation.toggled').removeClass("toggled");
            }
        }
    });
    $('#site-navigation.toggled,#site-navigation.toggled,.custom-switchers.opened').on('click touch', function(event) {
        event.stopPropagation();
    });
});