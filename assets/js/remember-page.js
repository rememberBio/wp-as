

jQuery(document).ready(($)=>{
    $(document).on('click touch', function(event) {

        if($('section.gallery').length) {
            if ( !$(event.target).is('.wrap-photo-item img') && !$(event.target).is('.wrap-photo-item') && !$(event.target).is('.wrap-video-item') && !$(event.target).is('.wrap-video-item video') ) {
                closeGalleryPopup();
            }
        }

        if($('section.comments').length) {
            if(!$(event.target).is('.write-comment-btn') && !$(event.target).is('.write-comment-btn span')) {
                $("section.comments .write-comment.opened").removeClass("opened");
                let container = $(".wrap-write-comment-form");
                container.children("form").show();
                container.children(".wrap-after-submit-comment").hide();
            }
        }

        if($("section.candles-flowers").length) {
            if(!$(event.target).is('.write-candles-flowers-btn a')) {
                $(".write-candles-flowers.opened").removeClass("opened");
                closeCandleFlowerPopup();
            }
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
        if(jQuery(".current-image img").length) {
            if(jQuery(".current-image img").height() > window.innerHeight || jQuery(".current-image img").height() > window.innerHeight * 0.75) {
                jQuery(".current-image img").css("height",window.innerHeight * 0.75 );
                jQuery(".current-image img").css("width","auto" );
                jQuery(".current-image").addClass("adaptive-height");
            }
        } else {
            if(jQuery(".current-image").height() > window.innerHeight || jQuery(".current-image").height() > window.innerHeight * 0.75) {
                jQuery(".current-image").css("height",window.innerHeight * 0.75 );
                jQuery(".current-image").css("width","auto" );
            }
        }
    });

    $('.gallery-popup .wrap-popup,.wrap-write-comment-form,.wrap-write-candles-flowers-form form').on('click touch', function(event) {
        event.stopPropagation();
    });

    $(".wrap-gallery img").hover(
        function() {
            if(!$(this).hasClass("hovered-current-img")) {
                $(".hovered-current-img").removeClass("hovered-current-img");
                thisEl = $(this);
                src = thisEl.attr("src");
                currenyViewImage = thisEl.siblings(".current-gallery-image");
                if(currenyViewImage.length) {
                currenyViewImage.attr("src",src);
                }
                thisEl.addClass("hovered-current-img");
            }
        }, function() {
        }
    );

    //toggle comment form in comments tab
    $(".write-comment-btn").click(function(event) {
        event.preventDefault();
        $(this).parents(".write-comment").toggleClass("opened");
        let container = $(this).siblings(".wrap-write-comment-form");
        container.children("form").show();
        container.children(".wrap-after-submit-comment").hide();
    });

    //candles and flowers
    $(".write-candles-flowers-btn a:not(.main-cf-btn)").click(function(event) {
        event.preventDefault();
        let dataProduct = $(this).attr("data-product");

        let wrapRadios = $(this).parents(".write-candles-flowers").find(".step-1 .wrap-radio");
        let candleRadio = wrapRadios.first();
        let flowerRadio = wrapRadios.last();

        if(dataProduct == "flower") { 
            candleRadio.removeClass("current");
            candleRadio.find("input").attr('checked', false);
            flowerRadio.addClass("current");
            flowerRadio.find("input").attr('checked', true);
        } else {
            flowerRadio.removeClass("current");
            flowerRadio.find("input").attr('checked', false);
            candleRadio.addClass("current");
            candleRadio.find("input").attr('checked', true);
        }

        $(this).parents(".write-candles-flowers").addClass("opened");

    });

    $(".wrap-write-candles-flowers-form a.close").click(function(event) {
        event.preventDefault();
        $(this).parents(".write-candles-flowers").removeClass("opened");
        closeCandleFlowerPopup();
    });

    $(".wrap-write-candles-flowers-form input[type=radio]").change(function(event) {
        $(".wrap-radio").removeClass("current");
        $(this).parents(".wrap-radio").addClass("current");
    });

    $(".wrap-write-candles-flowers-form #next-button").click(function(event) {
        let parent = $(this).parents(".step-1");
        let required_inputs = parent.find("input[required]");
        let error_container = parent.find(".wrap-error");
        let required_to_focus = [];
        required_inputs.each(function(i,el){
            if(jQuery(this).val() == "" || !validateInput(jQuery(this))) {
                required_to_focus.push(jQuery(this));
            }
        });
        if(required_to_focus.length) {
            required_to_focus[0].focus(); //the customer see default required error message
            error_container.show();
        } else {
            error_container.hide();
            let is_candle = true;
            let checked_radio = parent.find("input[type=radio]:checked");
            if(checked_radio.val() == 'flower' ) is_candle = false;
            parent.hide();
            part2 = parent.next('.step-2');
            if(is_candle) {
                part2.find(".flower-image").hide();
                part2.find(".price-flower").hide();
            } else {
                part2.find(".candle-image").hide();
                part2.find(".price-candle").hide();
            }
            part2.show();
        }
      
    });

    //main tab gallery slider
    if($('#galley-main-slider').length) {
        $('#galley-main-slider').slick({
            //lazyLoad: 'ondemand',
            slidesToShow: 2,
            centerMode: false,
            variableWidth: true,
            autoplay: true,
            arrows: true, 
            prevArrow: '<button class="slide-arrow prev-arrow"><img src="/wp-content/uploads/2022/02/Path-119.svg" /></button>',
            nextArrow: '<button class="slide-arrow next-arrow"><img src="/wp-content/uploads/2022/02/Path-119.svg" /></button>',
            responsive: [
            {
                breakpoint: 1100,
                settings: {
                    slidesToShow: 2
                },
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1
                },
            }]
        });
    }

    //comments
    jQuery('.comment-form-attachment.attach-img #attachment').on("change", function(){
        val = jQuery(this).val();
        wrapper = jQuery(this).parents(".comment-form-attachment.attach-img");
        label = wrapper.find("label");
        if(val != "") {
            wrapper.addClass("success");
        }
        else {
            wrapper.removeClass("success");
        }
    });
});
function closeCandleFlowerPopup() {

    jQuery("#cfform").trigger("reset").show();
    jQuery('.wrap-write-candles-flowers-form .step-1').show();
    jQuery('.wrap-write-candles-flowers-form .step-2,.wrap-thank-section').hide();

    //remove payment param from url
    url = new URL(window.location.href);
    if (url.searchParams.get('payment')) {
        url.searchParams.set('payment','');
        window.location.href = url
    }
}
function validateInput(input) {
    var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    var testPhone = /[0-9\-\(\)\s]+./;
    if(input.attr("type") == "email" && !testEmail.test(input.val())) return false;
    if(input.attr("type") == "tel" && !testPhone.test(input.val())) return false;
    return true;
}
function cancelUploadFile(e) {
    e.preventDefault();
    var wrapper = jQuery(".comment-form-attachment.attach-img");
    var input = wrapper.find("input");
    input.val("");
    wrapper.removeClass("success");
}

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
    photosArr = decodeURIComponent(photosArr);
    videosArr = decodeURIComponent(videosArr);
    photosArr = JSON.parse(photosArr);
    videosArr = JSON.parse(videosArr);
    tabElement = jQuery(".wrap-content.tab-album");

    tabElement.find(".wrap-photo-item").remove();
    tabElement.find(".wrap-video-item").remove();

    tabElement.find('span.year').text(years);
    tabElement.find('span.album-name').text(decodeURIComponent(AlbumName).replaceAll("+"," "));
    
    imagesArrToSend = [];
    if(videosArr && photosArr) {
        imagesArrToSend = photosArr.concat(videosArr);
    } else if(videosArr) {
        imagesArrToSend = videosArr;
    } else if(photosArr) {
        imagesArrToSend = photosArr;
    }


    let container = tabElement.find(".wrap-item-gallery");
    let indexEl = 0;
    for (let index = 0; index < photosArr.length; index++) {
        const element = photosArr[index];
        if(element['url'] != "") {
            let wrapItem = jQuery("<div class='wrap-photo-item'></div>");
            let item = jQuery("<img />").attr({ "src":element['url'],"data-index":indexEl});
            let caption = "";
            if(element['caption'] != "") {
                element['caption'] = element['caption'].replaceAll("+"," ");
                caption = jQuery("<span class='caption' style='display:none;'></span>").text(element['caption']);
            }
            wrapItem.append(item);
            if(caption != "") {
                wrapItem.append(caption);
                wrapItem.addClass("has-caption");
            }
            container.append(wrapItem);
            indexEl = indexEl + 1;
        }
    }

    for (let index = 0; index < videosArr.length; index++) {
        const element = videosArr[index];
        if(element['url'] != "") {
            let wrapItem = jQuery("<a href='' class='wrap-video-item'></a>");
            let item = jQuery("<video></video>").attr({"src":element['video'],"data-index":indexEl});
            wrapItem.append(item);
            container.append(wrapItem);
            indexEl = indexEl + 1;
        }
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
                if(element) {
                    let video = element['video'];
                    if((video != undefined && video != "") || element['url'] != "") {
                        let item = "";
                        let caption = "";
                        if(video != undefined) {
                            item = jQuery("<video class='video-popup-photo' controls > </video>").attr({ "src":video,"data-index":index,"data-length-all":imagesLength });
                        } else  {
                            item = jQuery('<div class="wrap-image-popup-el"></div>').attr({ "data-index":index,"data-length-all":imagesLength });
                            img = jQuery("<img />").attr({ "src":element['url']});
                            item.append(img);
                            if(element['caption'] != "") {
                                element['caption'] = element['caption'].replaceAll("+"," ");
                                caption = jQuery("<span class='caption' style='display:none;'></span>").text(element['caption']);
                                item.append(caption);
                                item.addClass("has-caption");
                            }
                       }
                        if(index == currentIndex) item.addClass("current-image");
                        container.append(item);
                    }
                }
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
        if(jQuery(".current-image img").length) {
            if(jQuery(".current-image img").height() > window.innerHeight || jQuery(".current-image img").height() > window.innerHeight * 0.75) {
                jQuery(".current-image img").css("height",window.innerHeight * 0.75 );
                jQuery(".current-image img").css("width","auto" );
                jQuery(".current-image").addClass("adaptive-height");
            }
        } else {
            if(jQuery(".current-image").height() > window.innerHeight || jQuery(".current-image").height() > window.innerHeight * 0.75) {
                jQuery(".current-image").css("height",window.innerHeight * 0.75 );
                jQuery(".current-image").css("width","auto" );
            }
        }

    }
}

function closeGalleryPopup(e) {
    if( e != undefined ) 
        e.preventDefault();
    popupEl = jQuery(".gallery-popup");
    
    items = popupEl.find(".wrap-image-popup-el,video.video-popup-photo");
    if(items.length)
        items.remove();

    popupEl.hide();
}

