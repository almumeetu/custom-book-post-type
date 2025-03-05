<?php
// Add Meta Boxes
function book_add_custom_meta_boxes() {
    add_meta_box('book_details', 'Book Details', 'book_meta_fields_callback', 'book', 'normal', 'high');
}
add_action('add_meta_boxes', 'book_add_custom_meta_boxes');

// Meta Fields Callback
function book_meta_fields_callback($post) {
    $author_name = get_post_meta($post->ID, 'author_name', true);
    $publish_date = get_post_meta($post->ID, 'publish_date', true);
    $author_email = get_post_meta($post->ID, 'author_email', true);

    ?>
    <p>
        <label>Author Name:</label>
        <input type="text" name="author_name" value="<?php echo esc_attr($author_name); ?>" />
    </p>
    <p>
        <label>Publish Date:</label>
        <input type="date" name="publish_date" value="<?php echo esc_attr($publish_date); ?>" />
    </p>
    <p>
        <label>Author Email:</label>
        <input type="email" name="author_email" value="<?php echo esc_attr($author_email); ?>" />
    </p>
    <?php
}

// Save Meta Fields
function book_save_meta_fields($post_id) {
    if (isset($_POST['author_name'])) {
        update_post_meta($post_id, 'author_name', sanitize_text_field($_POST['author_name']));
    }
    if (isset($_POST['publish_date'])) {
        update_post_meta($post_id, 'publish_date', sanitize_text_field($_POST['publish_date']));
    }
    if (isset($_POST['author_email'])) {
        update_post_meta($post_id, 'author_email', sanitize_email($_POST['author_email']));
    }
}
add_action('save_post', 'book_save_meta_fields');
