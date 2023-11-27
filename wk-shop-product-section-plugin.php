<?php
/*
 * Plugin Name: Wk Shop Product Section
 * Description: Displays a section with products. Displays a title, description and products. 3 per row.
 * Version: 0.1.2
 * Author: Wictor K
 * 
 * This plugin is designed to work well with Tailwind CSS for styling.
 * Recommend including Tailwind CSS in your WordPress project to ensure a consistent and visually appealing user interface.
 * 
 * For more information on Tailwind CSS, please refer to the official documentation: [Tailwind CSS Documentation](https://tailwindcss.com/docs).
 */


function wk_shop_product_section_shortcode($atts) {

    $atts = shortcode_atts(
        array(
            'title' => 'Featured Products',
            'description' => 'Check out our featured products',
            'limit' => 4, // Default number of products to display
            'products' => '', // Comma-separated list of product IDs
        ),
        $atts,
        'wk_shop_product_section'
    );

    // Product IDs to an array
    $product_ids = explode(',', $atts['products']);


    // Query 
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['limit'],
        'post__in' => $product_ids,
    );

    $query = new WP_Query($args);

    ob_start();
    ?>
    <div class="wk-shop-product-section-wrapper w-full flex flex-col justify-center items-center">
        <hr />
        <h2 class="text-2xl text-center font-roboto">
            <?php echo esc_html($atts['title']); ?>
        </h2>
        <p class="text-[0.85rem] leading-5 w-full max-w-[760px]  mt-4 font-roboto text-[#555] font-light">
            <?php echo esc_html($atts['description']); ?>
        </p>

        <?php
        if ($query->have_posts()) :
            ?>
            <ul class="flex flex-wrap gap-4 flex-row mt-6 justify-center max-w-[1140px]">
            <?php
            while ($query->have_posts()) : $query->the_post();
            global $product;
            if ( empty( $product ) || ! $product->is_visible() ) {
                continue;
            }
            ?>
            <li class="list-none flex-shrink-0 flex-1/3">
                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                    <?php echo wp_kses_post($product->get_image()); ?>
                    <div class="w-full border-t border-solid border-black text-center border-b font-roboto font-light py-2 text-[12px]">
                        <?php echo esc_html($product->get_title()); ?>
                    </div>
                </a>

            </li>
            <?php
            endwhile;
            ?>
            </ul>
            <?php
        else :
            echo 'No products found';
        endif;

        // Restore global post data
        wp_reset_postdata();
        ?>
    </div>
    <?php

    return ob_get_clean();
}

add_shortcode('wk_shop_product_section', 'wk_shop_product_section_shortcode');
