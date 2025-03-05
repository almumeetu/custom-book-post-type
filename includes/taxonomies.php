<?php
// Register Genre Taxonomy
function create_book_taxonomies() {
    register_taxonomy(
        'genre',
        'book',
        array(
            'label' => __('Genre', 'textdomain'),
            'rewrite' => array('slug' => 'genre'),
            'hierarchical' => true,
        )
    );

    register_taxonomy(
        'book_tags',
        'book',
        array(
            'label' => __('Tags', 'textdomain'),
            'rewrite' => array('slug' => 'book-tags'),
            'hierarchical' => false,
        )
    );
}
add_action('init', 'create_book_taxonomies');
