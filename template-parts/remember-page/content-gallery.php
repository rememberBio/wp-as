<?php
    $post_id = get_the_ID();

    //get custom fields
    $gallery_items = get_field("gallery_items",$post_id);
    //start_year
    //end_year
    //albums - repeater
        //name_of_album
        //start_year_of_album
        //end_year_of_album
        //photos - gallery
        //videos - repeater
            //video
    $gallery_items_copy = array();
    $want_hebrew_dates =  get_field("settings_want_hebrew_dates",$post_id);
?>

<section class="gallery">
    <h1><?php _e('Gallery', 'remmember'); ?></h1>
    <div class="wrap-tabs">
        <a class="btn-tab-1 current-gallery-tab" href="" onclick="switchGalleryTab(event,1)"><?php _e('Albums', 'remmember'); ?></a>
        <a class="btn-tab-2" href="" onclick="switchGalleryTab(event,2)"><?php _e('Photos', 'remmember'); ?></a>
        <a class="btn-tab-3" href="" onclick="switchGalleryTab(event,3)"><?php _e('Videos', 'remmember'); ?></a>
    </div>
    <div class="wrap-content tab-1">
        <?php foreach ($gallery_items as $gallery_item) { 
            $start_year = $gallery_item['start_year'];
            $end_year = $gallery_item['end_year'];
            if($end_year == $start_year || $start_year == "")  {
                $start_year = $end_year;
                $end_year = ""; 
                $gallery_item['start_year'] = $start_year;
                $gallery_item['end_year'] = $end_year;

            }
            $start_year_he = '';
            $end_year_he = '';
            //he dates
            if($want_hebrew_dates) {
                $start_year_he = get_year_str_converted_hebrew_date(gregorian_to_hebrew($start_year,1,1));
                $end_year_he = get_year_str_converted_hebrew_date(gregorian_to_hebrew($end_year,1,1));
            }
            $albums = $gallery_item['albums'];
            $gallery_item['photos_arr'] = array(); 
            $gallery_item['videos_arr'] = array(); 
        ?>
           <div class="wrap-item-gallery">
               <span class="top-item-gallery">
                    <?php if($want_hebrew_dates) { ?>
                        <span class="wrap-he-dates">
                            <span class="year"><?php echo $start_year_he; ?></span>
                            <?php if($end_year !== "") { ?>
                                <span>-</span>
                                <span class="year"><?php echo $end_year_he; ?></span>   
                            <?php } ?>
                            <span class="space">|</span>
                        </span>
                    <?php } ?>
                    <?php if($end_year !== "") { ?>
                        <span class="year"><?php echo $end_year; ?></span>
                        <span>-</span>
                    <?php } ?>
                    <span class="year"><?php echo $start_year; ?></span>
                  
               </span>
                <?php foreach ($albums as $album) { 
                    
                    $album_start_year = $album['start_year_of_album'];
                    $album_end_year = $album['end_year_of_album'];
                    $album_years = $album_start_year;
                    if($album_end_year == $album_start_year || $album_start_year == "")  {
                        $album_start_year = $album_end_year;
                        $album_end_year = ""; 
                    } else {
                        $album_years = $album_years . " - " . $album_end_year;
                    }

                    $album_end_year_he = '';
                    $album_start_year_he = '';
                    //he dates
                    if($want_hebrew_dates) {
                        $album_start_year_he = get_year_str_converted_hebrew_date(gregorian_to_hebrew($album_start_year,1,1));
                        $album_end_year_he = get_year_str_converted_hebrew_date(gregorian_to_hebrew($album_end_year,1,1));
                        
                        $album_years_he =  $album_start_year_he;

                        if($album_end_year !== $album_start_year && $album_end_year !== "")  {
                            $album_years_he .= " - " . $album_end_year_he ;
                        }
                        $album_years = $album_years . ' | ' . $album_years_he;
                    }

                    $album_photos = $album['photos'];
                    $album_videos = $album['videos'];
                    $gallery_item['photos_arr'] = array_merge($gallery_item['photos_arr'],$album_photos);
                    $gallery_item['videos_arr'] = array_merge($gallery_item['videos_arr'],$album_videos);
                    $album_photos_json = urlencode(json_encode($album_photos));
                    $album_videos_json = urlencode(json_encode($album_videos));
                ?>
                    <div class="wrap-album-item" onclick='openAlbumTab(`<?= $album_photos_json ?>`,`<?= $album_videos_json ?>`,`<?= $album_years ?>`,`<?php echo urlencode($album["name_of_album"]); ?>`)'>
                        <?php if( is_array($album_photos) && count($album_photos) > 0 )  { ?>
                         <img src="<?php echo($album_photos[0]['url']); ?>" alt="">
                       <?php } else { ?>
                         <img src="/wp-content/uploads/woocommerce-placeholder.png" alt="">
                       <?php } ?>
                       <div class="wrap-album-bottom">
                           <span class="name"><?php echo $album['name_of_album']; ?></span>
                           <div class="wrap-date">
                                <?php if($want_hebrew_dates) { ?>
                                    <span class="wrap-he-dates">
                                        <span class="year"><?php echo $album_start_year_he; ?></span>
                                        <?php if($album_end_year !== "") { ?>
                                            <span>-</span>
                                            <span class="year"><?php echo $album_end_year_he; ?></span>
                                        <?php } ?>
                                           
                                        <span class="space">|</span>
                                    </span>
                                <?php } ?>
                                <?php if($album_end_year !== "") { ?>
                                    <span class="year"><?php echo $album_end_year; ?></span>
                                    <span>-</span>
                                <?php } ?>
                                <span class="year"><?php echo $album_start_year; ?></span>
                                
                            </div>
                        </div>
                    </div>
                <?php } ?>
           </div>
        <?php 
            $gallery_items_copy[] = $gallery_item;
        } //end gallery items foreach 
        $gallery_items = $gallery_items_copy;
        ?>
       
    </div>
   
    <div class="wrap-content tab-2" style="display:none;">
        <?php 
        $all_photos = array();
        $index_img = 0;

        foreach ($gallery_items as $gallery_item) { 
            $start_year = $gallery_item['start_year'];
            $end_year = $gallery_item['end_year'];
            $photos = $gallery_item['photos_arr'];

            //he dates
            $start_year_he = '';
            $end_year_he = '';
            
            if($want_hebrew_dates) {
                $start_year_he = get_year_str_converted_hebrew_date(gregorian_to_hebrew($start_year,1,1));
                $end_year_he = get_year_str_converted_hebrew_date(gregorian_to_hebrew($end_year,1,1));
            }
            if($photos)
                $all_photos = array_merge($all_photos,$photos);
        ?>
           <div class="wrap-item-gallery">
               <span class="top-item-gallery">
                    <?php if($want_hebrew_dates) { ?>
                        <span class="wrap-he-dates">
                            <span class="year"><?php echo $start_year_he; ?></span>
                            <?php if($end_year !== "") { ?>
                                <span>-</span>
                                <span class="year"><?php echo $end_year_he; ?></span>
                            <?php } ?>
                            <span class="space">|</span>
                        </span>
                    <?php } ?>
                    <?php if($end_year !== "") { ?>
                        
                        <span class="year"><?php echo $end_year; ?></span>
                        <span>-</span>
                    <?php } ?>
                    <span class="year"><?php echo $start_year; ?></span>
                   
               </span>
                <?php foreach ($photos as $photo) {  ?>
                    <div class="wrap-photo-item  <?php if($photo['caption'] !== "") { echo 'has-caption'; } ?>">
                        <?php if($photo && !empty( $photo )) { ?>
                            <img data-index="<?= $index_img ?>" src="<?php echo $photo['url']; ?>" alt="">
                            <?php if($photo['caption'] !== "") { ?>
                            <span class="caption" style="display:none;"><?php echo $photo['caption']; ?></span>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php 
                    $index_img = $index_img + 1;
                    } ?>
           </div>
        <?php }
            $all_photos = json_encode($all_photos);
        ?>
    </div>
    <div class="wrap-content tab-3" style="display:none;">
        <?php foreach ($gallery_items as $gallery_item) { 
            $start_year = $gallery_item['start_year'];
            $end_year = $gallery_item['end_year'];

            //he dates
            $start_year_he = '';
            $end_year_he = '';
            
            if($want_hebrew_dates) {
                $start_year_he = get_year_str_converted_hebrew_date(gregorian_to_hebrew($start_year,1,1));
                $end_year_he = get_year_str_converted_hebrew_date(gregorian_to_hebrew($end_year,1,1));
            }

            $videos = $gallery_item['videos_arr'];

        ?>
            <?php if(is_array($videos)) { ?>
                <div class="wrap-item-gallery">
                    <span class="top-item-gallery">
                            <?php if($want_hebrew_dates) { ?>
                                <span class="wrap-he-dates">
                                   
                                    <span class="year"><?php echo $start_year_he; ?></span>
                                    <?php if($end_year !== "") { ?>
                                        <span>-</span>
                                        <span class="year"><?php echo $end_year_he; ?></span>
                                    <?php } ?>
                                    <span class="space">|</span>
                                </span>
                            <?php } ?>
                            <?php if($end_year !== "") { ?>
                                
                                <span class="year"><?php echo $end_year; ?></span>
                                <span>-</span>
                            <?php } ?>
                            <span class="year"><?php echo $start_year; ?></span>
                           
                    </span>
                        <?php foreach ($videos as $video) {  ?>
                            <a href="" onclick="openVideoGalleryPopup(event,'<?= $video['video'] ?>')" class="wrap-video-item">
                                <video src="<?php echo $video['video']; ?>"></video>
                            </a>
                        <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="wrap-content tab-album" style="display:none;">
        <div class="wrap-item-gallery">
            <span class="top-item-gallery">
                <a href="" onclick="closeAlbumTab(event)" class="prev-to-page"></a>
                <span class="years">
                    <span class="album-name"></span>
                    <span class="year"></span>
                </span>
            </span>
        </div>
    </div>
</section> 
<!-- video popup -->
<div class="video-gallery-popup gallery-popup" style="display:none;" >
    <div class="wrap-popup">
        <div class="top">
            <a href="" onclick="closeGalleryPopup(event)" class="close"></a>
        </div>
        <div class="body">
            <video controls autoplay src=""></video>
        </div>
    </div>
</div>
<!-- video popup -->
<!-- photos popup -->
<div class="photo-gallery-popup gallery-popup" style="display:none;" >
    <div class="wrap-popup">
        <div class="top">
            <a href="" onclick="closeGalleryPopup(event)" class="close"></a>
        </div>
        <div class="body">
            <div class="prev"></div>
            <div class="images-container"></div>
            <div class="next"></div>
        </div>
    </div>
</div>
<!-- photos popup -->

<script>

    const imagesArr = <?= $all_photos ?>;
    jQuery(document).ready(($)=>{
        $(".wrap-photo-item").click(function(){
            imgInd = $(this).find("img").attr("data-index");
            openVideoPhotoPopup(imagesArr,imgInd);
        });
    });

</script>