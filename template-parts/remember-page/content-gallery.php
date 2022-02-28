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
?>

<section class="gallery">
    <h1>Gallery</h1>
    <div class="wrap-tabs">
        <a class="btn-tab-1 current-gallery-tab" href="" onclick="switchGalleryTab(event,1)">Albums</a>
        <a class="btn-tab-2" href="" onclick="switchGalleryTab(event,2)">Photos</a>
        <a class="btn-tab-3" href="" onclick="switchGalleryTab(event,3)">Videos</a>
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
            $albums = $gallery_item['albums'];
            $gallery_item['photos_arr'] = array(); 
            $gallery_item['videos_arr'] = array(); 
        ?>
           <div class="wrap-item-gallery">
               <span class="top-item-gallery">
                    <span class="year"><?php echo $start_year; ?></span>
                    <?php if($end_year !== "") { ?>
                        <span>-</span>
                        <span class="year"><?php echo $end_year; ?></span>
                    <?php } ?>
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

                    $album_photos = $album['photos'];
                    $album_videos = $album['videos'];
                    $gallery_item['photos_arr'] = array_merge($gallery_item['photos_arr'],$album_photos);
                    $gallery_item['videos_arr'] = array_merge($gallery_item['videos_arr'],$album_videos);
                    $album_photos_json = json_encode($album_photos);
                    $album_videos_json = json_encode($album_videos);
                ?>
                    <div class="wrap-album-item" onclick='openAlbumTab(`<?= $album_photos_json ?>`,`<?= $album_videos_json ?>`,`<?= $album_years ?>`,`<?php echo urlencode($album["name_of_album"]); ?>`)'>
                        <?php if( is_array($album_photos) && count($album_photos) > 0 )  { ?>
                         <img src="<?php echo($album_photos[0]); ?>" alt="">
                       <?php } else { ?>
                         <img src="/wp-content/uploads/woocommerce-placeholder.png" alt="">
                       <?php } ?>
                       <div class="wrap-album-bottom">
                           <span class="name"><?php echo $album['name_of_album']; ?></span>
                           <div class="wrap-date">
                                <span class="year"><?php echo $album_start_year; ?></span>
                                <?php if($album_end_year !== "") { ?>
                                    <span>-</span>
                                    <span class="year"><?php echo $album_end_year; ?></span>
                                <?php } ?>
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
            $all_photos = array_merge($all_photos,$photos);
        ?>
           <div class="wrap-item-gallery">
               <span class="top-item-gallery">
                    <span class="year"><?php echo $start_year; ?></span>
                    <?php if($end_year !== "") { ?>
                        <span>-</span>
                        <span class="year"><?php echo $end_year; ?></span>
                    <?php } ?>
               </span>
                <?php foreach ($photos as $photo) {  ?>
                    <div class="wrap-photo-item">
                        <img data-index="<?= $index_img ?>" src="<?php echo $photo; ?>" alt="">
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
            $videos = $gallery_item['videos_arr'];
        ?>
            <?php if(is_array($videos)) { ?>
                <div class="wrap-item-gallery">
                    <span class="top-item-gallery">
                            <span class="year"><?php echo $start_year; ?></span>
                            <?php if($end_year !== "") { ?>
                                <span>-</span>
                                <span class="year"><?php echo $end_year; ?></span>
                            <?php } ?>
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