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

$remmember_too_text = get_field("want_to_remmember_text_for_inner_pages","option");

if($current_tab == "")
	$remmember_too_text = get_field("want_to_remmember_text_for_home","option");

$post_id = get_the_ID();

$email = "";
if(isset($_POST['email']))
	{ 
		$email = $_POST['email'];
		if($email != "" && strpos($email, "@") && strpos($email, ".") ) {
			register_email_to_spec_remmember_page($email,$post_id);
		}
	}

?>
	</div>
	<footer id="colophon" class="site-footer remmember-item-footer <?php if($current_tab == '') { echo ("main-tab"); } ?>" >
		<span class="text"><?=  $remmember_too_text ?></span>
		<div class="wrap-form-footer">
			<form method="post" name="registerForm" id="registerForm" action="">
				<input type="email" name="email" value="" id="email" placeholder="<?= get_field("enter_email_text","option"); ?>" >
				<button type="submit"><img src="/wp-content/uploads/2022/02/Group-884.png" alt=""></button>
			</form>
		</div>
		<?php if($current_tab == "") { ?>
		<div class="remmber-footer-count-of-remmbers">
			<span class="num"><?php echo get_register_email_to_spec_remmember_page($post_id); ?></span>
			<span class="text"><?= get_field("people_remmember_text","option"); ?></span>
		</div>
		<?php } ?>
	</footer><!-- #colophon -->
	
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
