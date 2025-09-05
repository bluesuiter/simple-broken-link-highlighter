<?php

/**
 * finds broken links and share
 */

namespace SBLH\Traits;

trait LinkSnifferTrait
{
    private $meta_data;
    public $post_id = 0;

    public function findLinkInContent($html_content)
    {
        // Create a new DOMDocument object
        $dom = new \DOMDocument();

        // Load the HTML content into the DOMDocument, suppressing errors for malformed HTML
        @$dom->loadHTML($html_content);

        // Get all <a> (anchor) tags
        $links = $dom->getElementsByTagName('a');

        // Initialize an array to store the hrefs
        $hrefs = array();

        // Iterate through the links and extract the href attribute
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (!empty($href)) { // Ensure the href attribute is not empty
                $hrefs[] = $href;
            }
        }

        // Now $hrefs array contains all the extracted href values
        // You can then process or display them as needed
        $meta_data = array();
        foreach ($hrefs as $url) {
            $result = is_url_exists($url);
            $host = parse_url($url);

            if (gettype(strpos($result['status_code'], '40')) === 'integer' || gettype(strpos($result['status_code'], '50')) === 'integer') {
                $meta_data[$host['host']] = ['url' => $url, 'code' => $result['status_code'], 'ignored' => false];
            }
        }

        $this->meta_data = $meta_data;
        return $this->create_links_array();
    }

    /**
     * create links of broken links
     */
    private function create_links_array()
    {
        $broken_links = get_post_meta($this->post_id, 'sblh_broken_links', true);

        if (!empty($broken_links)) {
            foreach ($this->meta_data as $key => $link) {
                if (!isset($broken_links[$key])) {
                    $broken_links[$key] = $link;
                }
            }
        } else {
            $broken_links = $this->meta_data;
        }

        return $broken_links;
    }
}
