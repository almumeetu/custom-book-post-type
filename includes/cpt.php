<?php
// Register Custom Post Type: Book
function create_book_post_type() {
    $args = array(
        'label'               => __('Books', 'textdomain'),
        'public'              => true,
        'show_in_rest'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-book',
        'supports'            => array('title', 'editor', 'thumbnail'),
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'books'),
    );
    register_post_type('book', $args);
}
add_action('init', 'create_book_post_type');
