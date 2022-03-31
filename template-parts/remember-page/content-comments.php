<?php
    $post_id = get_the_ID();
    $url = get_permalink();
    //get comments
    $comments = get_remember_post_comments($post_id);
    $comments_desktop = array();
    $comments_left = array();
    $comments_right = array();
    $flag = true;
    foreach ($comments as $comment) {
        if($flag) {
            $flag = false;
            $comments_left[] = $comment;
        } else {
            $flag = true;
            $comments_right[] = $comment;
        }
    }

    $after_submit_comment = false;
    if(isset($_GET["submit"])) { 
        $after_submit_comment = true;
    }
?>

<section class="comments">
    <h1><?php _e('Comments', 'remmember'); ?></h1>
    <div class="write-comment <?php if($after_submit_comment) { echo "after-sub-comment opened"; } ?>">
        <a href="" class="write-comment-btn" ><span><?php _e('New Comment', 'remmember'); ?></span></a>
        <div class="wrap-write-comment-form">
            <form <?php if($after_submit_comment)  { echo "style=display:none"; } ?> action="<?php echo url() . '/wp-comments-post.php';  ?>" id="commentform" method="POST" enctype="multipart/form-data">
                <input required type="text" placeholder="<?php _e('Name', 'remmember'); ?>" id="name" name="name" size="30" maxlength="245">
                <input required type="text" placeholder="<?php _e('Relationship', 'remmember'); ?>" id="rel" name="rel">
                <textarea maxlength="65525" required name="comment" id="comment" cols="30" rows="10" aria-required="true" placeholder="<?php _e('Write A Comment', 'remmember'); ?>"></textarea>
                <p class="comment-form-attachment attach-img">
                    <label class="comment-form-attachment__label" for="attachment"> <?php _e('Attach A Picture', 'remmember'); ?> </label>
                    <label class="comment-form-attachment__label success" for="attachment"> <?php _e('Successfully Attached', 'remmember'); ?> <a href="" onclick="cancelUploadFile(event)" class="cancel"><?php _e('cancel', 'remmember'); ?></a></label>
                    <input class="comment-form-attachment__input" id="attachment" name="attachment" type="file" accept=".jpg,.jpeg,.jpe,.gif,.png,.bmp,.tiff,.tif,.webp,.ico,.heic">	
                    
                </p>

                <input type="submit" id="submit" value="<?php _e('Submit A Response', 'remmember'); ?>">
                <input type="hidden" name="comment_post_ID" value="<?php echo $post_id; ?>" id="comment_post_ID">
                <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                <input type="hidden" name="dome_redirect_to" value="<?php echo $url . '/?tab=comments&submit=true'; ?>"; />

            </form>
            <?php if($after_submit_comment)  { ?>
            <div class="wrap-after-submit-comment">
                <span class="head"><?php _e('Thank You For Your Comment!', 'remmember'); ?></span>
                <span class="text"><?php _e('The Comment Has Been Sent For Manager Approval And Will Be Displayed As Soon As Possible', 'remmember'); ?></span>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="wrap-content desktop-only">
        <div class="left-col">
            <?php foreach ($comments_left as $comment) { 

                $text = $comment->comment_content;
            
                $img_id = get_comment_meta( $comment->comment_ID, 'attachment_id', true );
                $img = wp_get_attachment_url($img_id);
                
                $owner_name = get_field("name_of_the_author_of_the_comment",$comment);
                $owner_rel = get_field("relationship_of_the_author_of_the_comment",$comment);

                //manipulate comment date
                $comment_date = $comment->comment_date;  
                $date = date_create($comment_date);
                //$date = date_timezone_set($date, timezone_open('Asia/Jerusalem'));
                $date = date_format($date,"d/m/Y");

            ?>
                <div class="wrap-comment">
                    <div class="wrap-top-comment">
                        <div class="wrap-img">
                            <img src="<?php echo($img); ?>" alt="">
                        </div>
                        <p class="text" ><?php echo($text); ?></p>
                    </div>
                    <div class="wrap-bottom-comment">
                        <div class="wrap-desc-comment">
                            <div class="wrap-desc-date-story">
                                <span class="name"><?php echo($owner_name); ?></span>
                                <span class="rel"><?php echo($owner_rel); ?></span>
                            </div>
                        </div>
                        <span class="date"><?php echo($date); ?></span>
                    </div>
                    
                </div>
            <?php } ?>
        </div>
        <div class="right-col">
            <?php foreach ($comments_right as $comment) { 

                $text = $comment->comment_content;
            
                $img_id = get_comment_meta( $comment->comment_ID, 'attachment_id', true );
                $img = wp_get_attachment_url($img_id);
                
                $owner_name = get_field("name_of_the_author_of_the_comment",$comment);
                $owner_rel = get_field("relationship_of_the_author_of_the_comment",$comment);

                //manipulate comment date
                $comment_date = $comment->comment_date;  
                $date = date_create($comment_date);
                //$date = date_timezone_set($date, timezone_open('Asia/Jerusalem'));
                $date = date_format($date,"d/m/Y");

            ?>
                <div class="wrap-comment">
                    <div class="wrap-top-comment">
                        <div class="wrap-img">
                            <img src="<?php echo($img); ?>" alt="">
                        </div>
                        <p class="text" ><?php echo($text); ?></p>
                    </div>
                    <div class="wrap-bottom-comment">
                        <div class="wrap-desc-comment">
                            <div class="wrap-desc-date-story">
                                <span class="name"><?php echo($owner_name); ?></span>
                                <span class="rel"><?php echo($owner_rel); ?></span>
                            </div>
                        </div>
                        <span class="date"><?php echo($date); ?></span>
                    </div>
                    
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="wrap-content mobile-only">
        <?php foreach ($comments as $comment) { 

            $text = $comment->comment_content;
           
            $img_id = get_comment_meta( $comment->comment_ID, 'attachment_id', true );
            $img = wp_get_attachment_url($img_id);
            
            $owner_name = get_field("name_of_the_author_of_the_comment",$comment);
            $owner_rel = get_field("relationship_of_the_author_of_the_comment",$comment);

            //manipulate comment date
            $comment_date = $comment->comment_date;  
            $date = date_create($comment_date);
            //$date = date_timezone_set($date, timezone_open('Asia/Jerusalem'));
            $date = date_format($date,"d/m/Y");

        ?>
            <div class="wrap-comment">
                <div class="wrap-top-comment">
                    <div class="wrap-img">
                        <img src="<?php echo($img); ?>" alt="">
                    </div>
                    <p class="text" ><?php echo($text); ?></p>
                </div>
                <div class="wrap-bottom-comment">
                    <div class="wrap-desc-comment">
                        <div class="wrap-desc-date-story">
                            <span class="name"><?php echo($owner_name); ?></span>
                            <span class="rel"><?php echo($owner_rel); ?></span>
                        </div>
                    </div>
                    <span class="date"><?php echo($date); ?></span>
                </div>
                
            </div>
        <?php } ?>
    </div>
</section>

<script>
    jQuery(document).ready( ($)=> {
        /*if($(window).width() > 1100 && $(".wrap-comment").length) {
            let magicGrid = new MagicGrid({
                container: '.wrap-content',
                animate: false,
                gutter: 25,
                static: true,
                useMin: true,
                useTransform: true
            });

            magicGrid.listen();
        }*/
    });
</script>