jQuery(document).ready(($)=>{
    $("#toggle-menu-item").click(function(e) {
        $(this).parents(".custom-switchers").toggleClass("opened");
    });
});