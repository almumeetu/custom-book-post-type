<?php
// Shortcode for Books Grid (Display All Books)
function display_books_grid($atts) {
    ob_start();
    
    $query = new WP_Query(array('post_type' => 'book', 'posts_per_page' => -1));
    
    echo '<div class="book-grid">';
    while ($query->have_posts()) : $query->the_post();
        $author = get_post_meta(get_the_ID(), 'author_name', true);
        echo '<div class="book-item">';
        echo '<h2>' . get_the_title() . '</h2>';
        echo '<p><strong>Author:</strong> ' . esc_html($author) . '</p>';
        echo '<p>' . get_the_excerpt() . '</p>';
        echo '</div>';
    endwhile;
    echo '</div>';
    
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('books_grid', 'display_books_grid');

// Shortcode for Genre-Wise Filtering (Filter Books by Genre)
function filter_books_by_genre($atts) {
    $atts = shortcode_atts(array('genre' => ''), $atts);  // Genre filter from shortcode attributes

    // Ensure genre is provided
    if (empty($atts['genre'])) {
        return 'No genre selected';
    }

    $query = new WP_Query(array(
        'post_type' => 'book',
        'tax_query' => array(
            array(
                'taxonomy' => 'genre',
                'field' => 'slug',
                'terms' => $atts['genre'],  // Genre passed in the shortcode
            ),
        ),
    ));

    ob_start();
    echo '<div class="filtered-books">';
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            echo '<div class="book-item">';
            echo '<h3>' . get_the_title() . '</h3>';
            echo '<p><strong>Author:</strong> ' . esc_html(get_post_meta(get_the_ID(), 'author_name', true)) . '</p>';
            echo '<p>' . get_the_excerpt() . '</p>';
            echo '</div>';
        endwhile;
    else :
        echo '<p>No books found in this genre.</p>';
    endif;
    echo '</div>';
    
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('books_by_genre', 'filter_books_by_genre');
