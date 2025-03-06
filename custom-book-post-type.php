<?php
/**
 * Plugin Name: Custom Book Post Type
 * Description: A custom post type for books with metadata, genres, and tags. Includes a grid view and filtering by genre.
 * Version: 1.0
 * Author: Saikat
 * License: GPL2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register Book Custom Post Type
function cbp_register_book_post_type() {
    $args = array(
        'label'               => 'Books',
        'description'         => 'Books and related information',
        'public'              => true,
        'show_in_rest'        => true, // Enable Gutenberg editor
        'supports'            => array('title', 'editor', 'author', 'thumbnail', 'custom-fields'),
        'taxonomies'          => array('genre', 'post_tag'), // Link to your custom taxonomy and default post tags
        'hierarchical'        => false,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'books'),
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
    );
    register_post_type('book', $args);
}
add_action('init', 'cbp_register_book_post_type');




// Register Genre Custom Taxonomy
function cbp_register_genre_taxonomy() {
    $args = array(
        'hierarchical' => true,
        'labels' => array(
            'name'              => 'Genres',
            'singular_name'     => 'Genre',
            'search_items'      => 'Search Genres',
            'all_items'         => 'All Genres',
            'parent_item'       => 'Parent Genre',
            'parent_item_colon' => 'Parent Genre:',
            'edit_item'         => 'Edit Genre',
            'update_item'       => 'Update Genre',
            'add_new_item'      => 'Add New Genre',
            'new_item_name'     => 'New Genre Name',
            'menu_name'         => 'Genre',
        ),
        'show_ui'         => true,
        'show_in_menu'    => true,
        'show_in_rest'    => true, // Gutenberg support
        'query_var'       => true,
        'rewrite'          => array('slug' => 'genre'),
    );
    register_taxonomy('genre', array('book'), $args);
}
add_action('init', 'cbp_register_genre_taxonomy');





// Add Custom Meta Fields to Book Post Type
function cbp_add_book_meta_boxes() {
    add_meta_box('book_details', 'Book Details', 'cbp_book_meta_box', 'book', 'normal', 'high');
}
add_action('add_meta_boxes', 'cbp_add_book_meta_boxes');

function cbp_book_meta_box($post) {
    wp_nonce_field('cbp_book_meta_nonce', 'meta_nonce');
    
    // Retrieve current values of the fields (if any)
    $author_name = get_post_meta($post->ID, '_author_name', true);
    $publish_date = get_post_meta($post->ID, '_publish_date', true);
    $author_email = get_post_meta($post->ID, '_author_email', true);
    
    ?>
    <p><label for="author_name">Author Name</label></p>
    <input type="text" id="author_name" name="author_name" value="<?php echo esc_attr($author_name); ?>" class="widefat">
    
    <p><label for="publish_date">Book Publish Date</label></p>
    <input type="date" id="publish_date" name="publish_date" value="<?php echo esc_attr($publish_date); ?>" class="widefat">
    
    <p><label for="author_email">Author Email</label></p>
    <input type="email" id="author_email" name="author_email" value="<?php echo esc_attr($author_email); ?>" class="widefat">
    <?php
}



// Save custom meta fields data
function cbp_save_book_meta($post_id) {
    if (!isset($_POST['meta_nonce']) || !wp_verify_nonce($_POST['meta_nonce'], 'cbp_book_meta_nonce')) {
        return;
    }
    
    // Save custom fields
    if (isset($_POST['author_name'])) {
        update_post_meta($post_id, '_author_name', sanitize_text_field($_POST['author_name']));
    }
    if (isset($_POST['publish_date'])) {
        update_post_meta($post_id, '_publish_date', sanitize_text_field($_POST['publish_date']));
    }
    if (isset($_POST['author_email'])) {
        update_post_meta($post_id, '_author_email', sanitize_email($_POST['author_email']));
    }
}
add_action('save_post', 'cbp_save_book_meta');



// Enqueue Styles for Grid View
function cbp_enqueue_styles() {
    wp_enqueue_style('cbp-style', plugins_url('assets/style.css', __FILE__), array(), time(), 'all');
}
add_action('wp_enqueue_scripts', 'cbp_enqueue_styles');



// Display Books in Grid View
function cbp_display_books_in_grid() {
    $args = array(
        'post_type'      => 'book',
        'posts_per_page' => 10,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<div class="books-grid">';
        while ($query->have_posts()) : $query->the_post();
            echo '<div class="book-item">';
            if (has_post_thumbnail()) {
                echo '<div class="book-thumbnail">' . get_the_post_thumbnail() . '</div>';
            }
            echo '<h3>' . get_the_title() . '</h3>';
            echo '<p>Author: ' . get_post_meta(get_the_ID(), '_author_name', true) . '</p>';
            echo '<p>Publish Date: ' . get_post_meta(get_the_ID(), '_publish_date', true) . '</p>';
            echo '<p>Author Email: ' . get_post_meta(get_the_ID(), '_author_email', true) . '</p>';
            echo '</div>';
        endwhile;
        echo '</div>';
        wp_reset_postdata();
    else :
        echo 'No books found.';
    endif;
}


// Custom Query for Genre Filter
function cbp_filter_books_by_genre($genre_slug) {
    $args = array(
        'post_type'      => 'book',
        'posts_per_page' => 10,
        'tax_query' => array(
            array(
                'taxonomy' => 'genre',
                'field'    => 'slug',
                'terms'    => $genre_slug,
                'operator' => 'IN',
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<div class="books-grid">';
        while ($query->have_posts()) : $query->the_post();
            echo '<div class="book-item">';
            if (has_post_thumbnail()) {
                echo '<div class="book-thumbnail">' . get_the_post_thumbnail() . '</div>';
            }
            echo '<h3>' . get_the_title() . '</h3>';
            echo '<p>Author: ' . get_post_meta(get_the_ID(), '_author_name', true) . '</p>';
            echo '<p>Publish Date: ' . get_post_meta(get_the_ID(), '_publish_date', true) . '</p>';
            echo '<p>Author Email: ' . get_post_meta(get_the_ID(), '_author_email', true) . '</p>';
            echo '</div>';
        endwhile;
        echo '</div>';
        wp_reset_postdata();
    else :
        echo 'No books found for this genre.';
    endif;
}

// Shortcode for Book Grid Display
function cbp_books_grid_shortcode() {
    ob_start();
    cbp_display_books_in_grid();
    return ob_get_clean();
}
add_shortcode('book_grid', 'cbp_books_grid_shortcode');

// Shortcode for Genre Filtered Display
function cbp_books_filtered_by_genre_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'genre' => '',
        ),
        $atts,
        'book_genre_filter'
    );

    ob_start();
    cbp_filter_books_by_genre($atts['genre']);
    return ob_get_clean();
}
add_shortcode('book_genre_filter', 'cbp_books_filtered_by_genre_shortcode');
