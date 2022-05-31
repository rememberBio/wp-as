<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package remmember
 */
$current_tab = "";
if(isset($_GET["tab"])) { 
    $current_tab = $_GET["tab"];
}

$remmember_submit_url = get_field("remmember_form_submit_img","option");

$remmember_too_text = __('Love? Want to remember?', 'remmember');

if($current_tab == "")
	$remmember_too_text = __('Want to remember too?', 'remmember');

$post_id = get_the_ID();

//Get counts of register emails to this post and other translated;
$pages_ids = get_all_translated_post_ids($post_id);
$emails_register_count = db_get_uniqe_remember_pages_register($pages_ids);

$email = "";
if(isset($_POST['email']))
	{ 
		$email = $_POST['email'];
		if($email != "" && strpos($email, "@") && strpos($email, ".") ) {
			//save customer in sendinblue
			register_email_to_spec_remmember_page($email,$post_id);
			//save customer and rememebr page to our db
			db_add_customer_remember_page($email,$post_id);
			//save customer to our db
			db_create_customer($email,'','From lead');
			//after complete reoland page
			header('Location: '.$_SERVER['REQUEST_URI']);
		}
	}

?>
	</div>
	<footer id="colophon" class="site-footer remmember-item-footer <?php if($current_tab == '') { echo ("main-tab"); } ?>" >
		<span class="text"><?=  $remmember_too_text ?></span>
		<div class="wrap-form-footer">
			<form method="post" name="registerForm" id="registerForm" action="">
				<input type="email" name="email" value="" id="email" placeholder="<?php  _e('enter your email', 'remmember'); ?>" >
				<button type="submit"><img src="/wp-content/uploads/2022/03/remember-1.svg" alt=""></button>
			</form>
			<div class="agree">
			<?php  _e('On signing up I Agree to', 'remmember'); ?> <a href="" class="open-agree-popup"><?php  _e('Privacy Policy and term', 'remmember'); ?> </a> 
			</div>
		</div>
		<?php if($current_tab == "") { ?>
		<div class="remmber-footer-count-of-remmbers">
			<span class="num"><?php echo $emails_register_count; ?></span>
			<span class="text"><?php  _e('People remember', 'remmember'); ?></span>
		</div>
		<?php } ?>
	</footer><!-- #colophon -->
	
</div><!-- #page -->
<div id="agreePopup" class="popup" style="display:none">
	<div class="header-popup">
		<a href="" class="close close-agree-popup">
			<img src="/wp-content/uploads/2022/02/Group-956.svg" alt="">
		</a>
		<h2><?php _e('Privacy Policy','remmember') ?></h2>
	</div>
	<div class="header-body">
		<p>
			<?php _e('a few words about him.a few words about him. lorem ipsum dolor s
			it amet, consectetur iuung elit, sed do eiusmod tempor inc
			ididunt ut magna aliqua. sit amet porttitor eget. in hendrgravida
			rutrum quueon tellus orci. quistus nulla at.
			tortor at risus erra adipiscing at in. diam','remmember') ?>
		</p>
	</div>
</div>
<?php wp_footer(); ?>

</body>
</html>
