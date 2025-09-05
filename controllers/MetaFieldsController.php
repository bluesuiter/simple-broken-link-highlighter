<?php

namespace SBLH\Controllers;

use SBLH\Traits\LinkSnifferTrait;

class MetaFieldsController
{
    use LinkSnifferTrait;

    /**
     * define actions to be called
     */
    public static function addActions()
    {
        $instance = new self();

        add_action('add_meta_boxes', [$instance, 'sblh_register_meta_boxes']);
        add_action('save_post', [$instance, 'sblh_save_meta']);
        add_action('wp_ajax_set_ignore_status', [$instance, 'set_ignore_status']);
    }


    /**
     * Register meta boxes.
     */
    function sblh_register_meta_boxes()
    {
        add_meta_box('post_meta_field', __('Broken Links', 'sblh'), [$this, 'sblh_display_callback'], SBLH_POST_TYPES);
    }


    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    function sblh_display_callback($post)
    {
        $meta_data = get_post_meta($post->ID, 'sblh_broken_links', true);
        return sblh_loadView('meta-fields', compact('post', 'meta_data'));
    }

    /**
     * Save meta box content.
     *
     * @param int $post_id Post ID
     */
    function sblh_save_meta($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || !in_array(getArrayValue($_POST, 'post_type'), SBLH_POST_TYPES)) return;

        // Get the HTML content (e.g., from a post, page, or custom field)
        $html_content = get_the_content(); // Example: retrieves the current post's content
        $this->post_id = $post_id;
        $broken_links = $this->findLinkInContent($html_content);
        update_post_meta($post_id, 'sblh_broken_links', $broken_links);
    }


    /**
     * set ignore status to broken link
     */
    function set_ignore_status()
    {
        $message = 'failed';
        $status = 500;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyNonce('set_url_ignorance', 'nonce_field')) {
            $post_id = getArrayValue($_POST, 'post_id');

            $meta_data = get_post_meta($post_id, 'sblh_broken_links', true);

            if (!empty($meta_data)) {
                foreach ($meta_data as $key => $row) {
                    if ($row['url'] === $_POST['url']) {
                        $row['ignored'] = $_POST['is_ignore'];
                    }

                    $meta_data[$key] = $row;
                }

                update_post_meta($post_id, 'sblh_broken_links', $meta_data);
            }


            $message = 'success';
            $status = 200;
        }

        wp_send_json(['message' => $message], $status);
    }
}
