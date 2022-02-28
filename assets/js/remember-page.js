

jQuery(document).ready(($)=>{
    $(document).on('click touch', function(event) {
        if (!$(event.target).is('.wrap-photo-item img') && !$(event.target).is('.wrap-photo-item') && !$(event.target).is('.wrap-video-item') && !$(event.target).is('.wrap-video-item video') ) {
            closeGalleryPopup();
        }
    });

    $(".photo-gallery-popup .next,.photo-gallery-popup .prev").click(function(){
        //take attrs
        currentImage = $(this).siblings(".images-container").find(".current-image");
        let index = currentImage.attr("data-index");
        let arrayLen = currentImage.attr("data-length-all");
        const thisEl = $(this);

        //if is next btn
        if($(this).hasClass("next")) {
            if( Number(index) + 1 < arrayLen) {
                currentImage.removeClass("current-image");
                currentImage.siblings("[data-index=" + ( Number(index) + 1 ) +"]").addClass("current-image");
                if(Number(index) + 1 == arrayLen - 1) thisEl.hide();
                if(Number(index) + 1 >= 0) thisEl.siblings(".prev").show();
            }
        } else if(thisEl.hasClass("prev")) { //if is prev btn
            if( Number(index) - 1 >= 0) {
                currentImage.removeClass("current-image");
                currentImage.siblings("[data-index=" + ( index - 1 ) +"]").addClass("current-image");
                if(Number(index) - 1 == 0) thisEl.hide();
                if(Number(index) - 1 < arrayLen - 1) thisEl.siblings(".next").show();
            }
        } 
        if(jQuery(".current-image").height() > window.innerHeight || jQuery(".current-image").height() > window.innerHeight * 0.75) {
            jQuery(".current-image").css("height",window.innerHeight * 0.75 );
            jQuery(".current-image").css("width","auto" );
        }
    });

    $('.gallery-popup .wrap-popup').on('click touch', function(event) {
        event.stopPropagation();
    });

});

function hideMoreStoryText(e) {
    e.preventDefault();

    e.target.parentElement.parentElement.children[0].setAttribute("style","display:block;");
    e.target.parentElement.parentElement.children[1].setAttribute("style","display:none;");

    e.target.setAttribute("style","display:none;");
    e.target.parentElement.children[1].setAttribute("style","display:block;");
}

function showMoreStoryText(e) {
    e.preventDefault();
   
    e.target.parentElement.parentElement.children[0].setAttribute("style","display:none;");
    e.target.parentElement.parentElement.children[1].setAttribute("style","display:block;");

    e.target.setAttribute("style","display:none;");
    e.target.parentElement.children[2].setAttribute("style","display:block;");
}

//gallery functions
function closeAlbumTab(event) {
    event.preventDefault();
    tabElement = jQuery(".wrap-content.tab-album");
    tabElement.find(".wrap-photo-item").remove();
    tabElement.find(".wrap-video-item").remove();
    tabElement.hide();
    jQuery(".wrap-content.tab-1").show();
}

function openAlbumTab(photosArr,videosArr,years,AlbumName) {
    photosArr = JSON.parse(photosArr);
    videosArr = JSON.parse(videosArr);
    tabElement = jQuery(".wrap-content.tab-album");

    tabElement.find(".wrap-photo-item").remove();
    tabElement.find(".wrap-video-item").remove();

    tabElement.find('span.year').text(years);
    tabElement.find('span.album-name').text(decodeURI(AlbumName).replaceAll("+"," "));

    imagesArrToSend = photosArr.concat(videosArr);


    let container = tabElement.find(".wrap-item-gallery");
    let indexEl = 0;
    for (let index = 0; index < photosArr.length; index++) {
        const element = photosArr[index];
        let wrapItem = jQuery("<div class='wrap-photo-item'></div>");
        let item = jQuery("<img />").attr({ "src":element,"data-index":indexEl});
        wrapItem.append(item);
        container.append(wrapItem);
        indexEl = indexEl + 1;
    }

    for (let index = 0; index < videosArr.length; index++) {
        const element = videosArr[index];
        let wrapItem = jQuery("<a href='' class='wrap-video-item'></a>");
        let item = jQuery("<video></video>").attr({"src":element['video'],"data-index":indexEl});
        wrapItem.append(item);
        container.append(wrapItem);
        indexEl = indexEl + 1
    }
   

    jQuery(".tab-album .wrap-photo-item,.tab-album .wrap-video-item").click(function(event){
        if(event != undefined) event.preventDefault();
        itemInd = jQuery(this).find("img,video").attr("data-index");
        openVideoPhotoPopup(imagesArrToSend,itemInd);
    });

    tabElement.show();
    jQuery(".wrap-content:not(.tab-album)").hide();

}

function switchGalleryTab(e,index) {
    e.preventDefault();

    jQuery('.current-gallery-tab').removeClass("current-gallery-tab");
    jQuery('[class*=btn-tab-' + index + ']' ).addClass("current-gallery-tab");

    jQuery(".gallery .wrap-content:not(tab-" + index + ")").hide();
    jQuery(".gallery .wrap-content.tab-" + index).show();
}

function openVideoGalleryPopup(e,videoUrl) {
    if( videoUrl != "" && videoUrl != undefined ) {
        e.preventDefault();
        popupEl = jQuery(".video-gallery-popup");
        video = popupEl.find("video");
        video.attr("src",videoUrl);
        popupEl.show();
    }
}

function openVideoPhotoPopup(imagesArrToPopup,currentIndex) {

    jQuery(".current-image").removeClass("current-image");

    currentIndex = Number(currentIndex);

    if( imagesArrToPopup != "" && imagesArrToPopup != undefined ) {
        let popupEl = jQuery(".photo-gallery-popup");
        let imagesLength = imagesArrToPopup.length;
        if(imagesLength) {
            container = popupEl.find(".images-container");
            for (let index = 0; index < imagesArrToPopup.length; index++) {
                const element = imagesArrToPopup[index];
                let video = element['video'];
                let item = "";
                if(video != undefined) {
                    item = jQuery("<video class='video-popup-photo' controls > </video>").attr({ "src":video,"data-index":index,"data-length-all":imagesLength });
                } else  {
                    item = jQuery("<img />").attr({ "src":element,"data-index":index,"data-length-all":imagesLength });
                }
                
                if(index == currentIndex) item.addClass("current-image");
                container.append(item);
                
            }

            if( currentIndex ==  imagesLength - 1 ) { 
                jQuery(".photo-gallery-popup .next").hide(); 
            } else {
                jQuery(".photo-gallery-popup .next").show();
            }
            if( currentIndex ==  0 ) { 
                jQuery(".photo-gallery-popup .prev").hide(); 
            } else {
                jQuery(".photo-gallery-popup .prev").show(); 
            };
        }

        popupEl.show();

        if(jQuery(".current-image").height() > window.innerHeight || jQuery(".current-image").height() > window.innerHeight * 0.75) {
            jQuery(".current-image").css("height",window.innerHeight * 0.75 );
            jQuery(".current-image").css("width","auto" );
        }

    }
}

function closeGalleryPopup(e) {
    if( e != undefined ) 
        e.preventDefault();
    popupEl = jQuery(".gallery-popup");
    
    items = popupEl.find("img,video.video-popup-photo");
    if(items.length)
        items.remove();

    popupEl.hide();
}
