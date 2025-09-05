<?php


namespace SBLH\Controllers;

use SBLH\Traits\LinkSnifferTrait;

class AdminController
{
    use LinkSnifferTrait;

    public static function addActions()
    {
        $instance = new self();

        add_action('admin_menu', [$instance, 'addMenuPage']);
        add_action('wp_ajax_find_broken_links', [$instance, 'sblh_find_broken_links']);
    }


    /**
     * manage admin menus from here
     */
    public function addMenuPage()
    {
        add_submenu_page(
            'tools.php',
            'Simple Broken Link Highlighter',       // Page title
            'SBLH',                                 // Menu title
            'manage_options',                       // Capability required to access the menu
            'sblh-manage',                          // Unique menu slug
            [$this, 'sblh_admin_page'],             // Callback function for content
        );
    }

    /**
     * admin page for sblh
     */
    function sblh_admin_page()
    {
        global $wpdb;
        $post_table = $wpdb->prefix . 'posts';
        $sql_qry = 'SELECT ID, post_title 
                    FROM ' . $post_table . ' 
                    WHERE post_type IN ("' . implode('","', SBLH_POST_TYPES) . '") 
                    AND post_status = "publish"
                    ORDER BY post_title ASC';

        $posts = $wpdb->get_results($sql_qry);
        return sblh_loadView('admin/index', compact('posts'));
    }


    /**
     * admin page for sblh
     */
    function sblh_find_broken_links()
    {
        $is_valid = verifyNonce('find_broken_links', 'nonce_field');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_valid) {
            $post_list = $_POST['post_list'];

            if (!empty($post_list)) {
                foreach ($post_list as $post_id) {
                    $html_content = get_post_field('post_content', $post_id);
                    $this->post_id = $post_id;
                    $broken_links = $this->findLinkInContent($html_content);

                    $status = update_post_meta($post_id, 'sblh_broken_links', $broken_links);
                }
            }
        }

        return wp_send_json(['message' => isset($status) ? 'success' : 'failure or nothing to store']);
    }
}
