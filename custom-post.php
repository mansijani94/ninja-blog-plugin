<?php
/**
 * Plugin Name: Ninja Blog
 * Description: This plugin creates a page "Ninja Blog" on activation with a shortcode to display posts and prevents editing of the page content. It deletes the page on deactivation.
 * Version: 1.0
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Create page on activation
function ninjablog_create_page() {
    $page_title = 'Ninja Blog';
    $page_content = '[ninja_blog_show_posts]';
    $page_slug = 'ninja-blog';

    // Check if page doesn't exist already
    $page = get_page_by_path($page_slug);
    if (!$page) {
        $page_id = wp_insert_post(array(
            'post_title' => $page_title,
            'post_content' => $page_content,
            'post_type' => 'page',
            'post_name' => $page_slug,
            'post_status' => 'publish',
            'post_author' => 1 // Change this to the desired author ID
        ));

        // Add meta to prevent editing
        if ($page_id) {
            add_post_meta($page_id, '_wp_page_template', 'default'); // Prevent editing page template
            add_post_meta($page_id, '_elementor_edit_mode', 'builder'); // If using Elementor, prevent editing with Elementor
            add_post_meta($page_id, '_wp_page_front_page', 'page'); // Set the page as front page
            add_post_meta($page_id, '_edit_lock', '1:' . time()); // Lock editing
        }
    }
}
register_activation_hook(__FILE__, 'ninjablog_create_page');

// Delete page on deactivation
function ninjablog_delete_page() {
    $page_slug = 'ninja-blog';

    // Get page by slug
    $page = get_page_by_path($page_slug);

    // Check if page exists and delete
    if ($page) {
        wp_delete_post($page->ID, true); // Set second parameter to true to force delete permanently
    }
}
register_deactivation_hook(__FILE__, 'ninjablog_delete_page');

// Prevent editing of Ninja Blog page
function ninjablog_prevent_editing($caps, $cap, $user_id, $args) {
    if ('edit_post' === $cap || 'edit_page' === $cap) {
        $post_id = isset($args[0]) ? $args[0] : 0;
        if ($post_id) {
            $post = get_post($post_id);
            if ($post && $post->post_name === 'ninja-blog') {
                $caps[] = 'do_not_allow';
            }
        }
    }
    return $caps;
}
add_filter('map_meta_cap', 'ninjablog_prevent_editing', 10, 4);

// Shortcode to display posts
function ninjablog_display_posts() {
    ob_start(); ?>
    <div class="ninjablog-posts">
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 10,
            'paged' => $paged
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                // Output post content here
                the_title('<h2>', '</h2>');
                the_excerpt();
            endwhile;
            // Pagination
            echo paginate_links(array(
                'total' => $query->max_num_pages
            ));
        else :
            echo 'No posts found.';
        endif;
        wp_reset_postdata();
        ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ninja_blog_show_posts', 'ninjablog_display_posts');