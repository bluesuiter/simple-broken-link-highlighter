<div class="wrap">
    <table style="width: 90%;margin: 0 auto;text-align: center;">
        <tr>
            <th><?php echo __('URL', 'sblh-url'); ?></th>
            <th><?php echo __('Status Code', 'sblh-status-code'); ?></th>
            <th><?php echo __('Is Ignored?', 'sblh-is-ignored'); ?></th>
        </tr>
        <?php
        if (!empty($meta_data)) {
            foreach ($meta_data as $row) {
                $ignored = getArrayValue($row, 'ignored') === 'true' ? 'Yes' : 'No';
        ?>
                <tr>
                    <td><?php echo $row['url'] ?></td>
                    <td><?php echo $row['code'] ?></td>
                    <td>
                        <button title="Click to set ignored '<?php echo $ignored === false ? 'No' : 'Yes' ?>'" data-ignored="<?php echo (($ignored === 'Yes') ? 'false' : 'true') ?>" data-id="<?php echo $post->ID ?>" data-url="<?php echo $row['url'] ?>" onclick="set_ignorance(this)" type="button" class="button default-button">
                            <?php echo $ignored ?>
                        </button>
                    </td>
                </tr>
        <?php }
        } ?>
    </table>
</div>

<script>
    function set_ignorance(ele) {
        let is_ignore = jQuery(ele).data('ignored');

        jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            method: 'post',
            data: {
                url: jQuery(ele).data('url'),
                post_id: jQuery(ele).data('id'),
                is_ignore: is_ignore,
                action: 'set_ignore_status',
                nonce_field: '<?php echo wp_create_nonce('set_url_ignorance') ?>'
            }
        }).done(function(res) {
            jQuery(ele).html((is_ignore === true ? 'Yes' : 'No'));
            jQuery(ele).data('ignored', !(is_ignore === true));
        }).error(function(res) {

        });
    }
</script>