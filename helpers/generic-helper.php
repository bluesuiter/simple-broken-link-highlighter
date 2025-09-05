<?php

/**
 * return index value from array/object
 */
if (!function_exists('getArrayValue')) {
    function getArrayValue($arr, $key)
    {
        if (is_array($arr)) {
            if (isset($arr[$key]) && !empty($arr[$key])) {
                return $arr[$key];
            }
        } else if (is_object($arr)) {
            if (isset($arr->$key) && !empty($arr->$key)) {
                return $arr->$key;
            }
        }
        return false;
    }
}


/**
 * check if url exists or not
 * @param string | $url - the url to identify
 * @return bool | true - if url exists
 */
if (!function_exists('is_url_exists')) {
    function is_url_exists($url)
    {
        $host = parse_url($url);
        $status = 404;
        $url_exists = false;
        $host = ($host['host'] === gethostbyname($host['host'])) ? false : true;

        if ($host === true) {
            $context = stream_context_create(
                [
                    'http' => [
                        'method' => 'HEAD',
                        'timeout' => 5
                    ]
                ]
            );


            $h = get_headers($url, true, $context);

            if (!empty($h)) {
                $status = array();
                preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0], $status);
            }

            if (!empty($status)) {
                if (!strpos($status[1], '40') || !strpos($status[1], '50')) {
                    $url_exists = true;
                    $status = $status[1];
                }
            }
        }

        return ['exists' => $url_exists, 'status_code' => $status];
    }
}


if (!function_exists('verifyNonce')) {
    /**
     * verifyNonce
     */
    function verifyNonce($actionName, $actionField)
    {
        $nonce = getArrayValue($_POST, $actionField);

        if (!wp_verify_nonce($nonce, $actionName) && !check_admin_referer($actionName, $actionField)) {
            return false;
        }

        return true;
    }
}


if (!function_exists('sblh_loadView')) {
    /**
     * loads view file
     */
    function sblh_loadView($view, $fields = array())
    {
        if (!empty($fields)) {
            foreach ($fields as $key => $field) {
                $$key = $field;
            }
        }

        $view = SBLH_VIEWS . $view . '.php';
        if (!file_exists($view)) {
            echo 'View not found!';
            return false;
        }

        require_once($view);
    }
}
