<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

function get_course_image_product($product) {
	//get list of all courses
	$custom_courses = get_courses_list();
	$product_course;
	$product_id = $product->get_id();
	//for on course list
	foreach ($custom_courses as $course) {
		//check if course product acf is equal to this product
		$course_product_id = get_field("course_product",$course->ID);
		if($course_product_id == $product_id) {
			$product_course = $course;
			break; 
		}
	}

	//if yes, get course post image
	//else return product image 
	if($product_course) {
		echo get_the_post_thumbnail($product_course->ID);
	} else {
		echo $product->get_image();
	}
}
?>
<div class="shop_table woocommerce-checkout-review-order-table">
	
	<h3 id="order_review_custom_heading">פרטי ההזמנה שלי</h3>
	<div class="wrap-;9checkout-product-list">
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="wrap-right-checkout-product">
						<span class="product-image">
							<?php get_course_image_product($_product); ?>
						</span>
					</div>
					<div class="wrap-left-checkout-product">
						<span class="product-name">
							<span class="checkout-product-list-label">שם הקורס:</span>
							<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
							<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<span class="product-total">
							<span class="checkout-product-list-label" >מחיר:</span>
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					</div>
				</div>
				<?php
			}
		}
	?>
	</div>
	
	<div class="wrap-checkout-coupon-link">	
		<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>
	</div>
	<div class="checkout-cart-total-price">
		<div class="cart-subtotal">
			<span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
			<span><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<span><b><?php wc_cart_totals_coupon_label( $coupon ); ?></b></span>
				<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php /* if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; */ ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<div class="order-total">
			<th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</div>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</div>
</div>