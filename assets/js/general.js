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

        if($('header#generalHeader .custom-switchers.opened').length) {
            if(!$(event.target).is('#toggle-menu-item') && !$(event.target).is('#toggle-menu-item img')) {
                $('header#generalHeader .custom-switchers.opened').removeClass("opened");
            }
        }
    });
    $('header#generalHeader #site-navigation.toggled,header#generalHeader .custom-switchers.opened').on('click touch', function(event) {
        event.stopPropagation();
    });
});