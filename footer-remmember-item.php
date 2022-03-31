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
$emails_register_count = get_register_emails_for_remember_pages($pages_ids);

$email = "";
if(isset($_POST['email']))
	{ 
		$email = $_POST['email'];
		if($email != "" && strpos($email, "@") && strpos($email, ".") ) {
			register_email_to_spec_remmember_page($email,$post_id);
			db_add_customer_remember_page($email,$post_id);
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
		</div>
		<?php if($current_tab == "") { ?>
		<div class="remmber-footer-count-of-remmbers">
			<span class="num"><?php echo $emails_register_count; ?></span>
			<span class="text"><?php  _e('People remember', 'remmember'); ?></span>
		</div>
		<?php } ?>
	</footer><!-- #colophon -->
	
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
