<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

class FlormarView {

	private $no_product_message;
	
	function __construct() {
		$this->no_product_message = __( 'No product found', 'flormar' );
	}
		
	function get_no_product_message() {
		return $this->no_product_message;
	}
	
	function render_slider( $product_posts, $options ) {
		if ( empty( $product_posts ) ) return $this->get_no_product_message();

		$title = empty( $options['title'] ) ? '' : "<h1>{$options['title']}</h1>";
		$html = "
			<div class='flormar-carousel-container'>
				$title
				<div class='flormar-carousel-wrap'>
			  		<ul class='flormar-carousel is-set'>";
		
		foreach ( $product_posts as $post_id ) {
			$product = wc_get_product( $post_id );
			$product_name = $product->get_name();
			$product_price = wc_price( $product->get_price() );
			$product_image = $product->get_image();
			$product_href = $product->get_permalink();
			
			$html .= "
				<li class='flormar-carousel-seat'>
					<a href='$product_href' class='image-container'>
						$product_image
					</a>
					<div class='divider'></div>
					<a href='$product_href' class='product-name'>$product_name</a>
					<div class='product-price'>$product_price</div>
				</li>";
		}

		$arrow_left = '<svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g clip-path="url(#clip0_410_126)">
			<path d="M22.3309 31.4307L25.0169 28.6948L15.9596 19.575L25.0169 10.5177L22.3309 7.76926L13.2111 16.889L10.4627 19.575L13.2111 22.3234L22.3309 31.4432L22.3309 31.4307Z" />
			</g>
			<defs>
			<clipPath id="clip0_410_126">
			<rect width="24.9857" height="24.9857" transform="translate(32.0129 32.0129) rotate(-180)"/>
			</clipPath>
			</defs>
		</svg>';
		
		$arrow_right = '<svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g clip-path="url(#clip0_584_165)">
			<path d="M15.9285 6.82865L13.2425 9.56459L22.2998 18.6844L13.2425 27.7417L15.9285 30.4901L25.0483 21.3703L27.7967 18.6844L25.0483 15.936L15.9285 6.81616L15.9285 6.82865Z" />
			</g>
			<defs>
			<clipPath id="clip0_584_165">
			<rect width="24.9857" height="24.9857" transform="translate(6.24646 6.24646)"/>
			</clipPath>
			</defs>
		</svg>';
		
		$html .= "
					</ul>
				</div>
				
				<button class='flormar-carousel-controls prev'>$arrow_left</button>
				<button class='flormar-carousel-controls next'>$arrow_right</button>
			</div>";
		
		return $html;
	}
};