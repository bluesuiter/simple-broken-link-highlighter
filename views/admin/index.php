<div class="wrap">
    <h1>Identify Broken Links</h1>

    <form class="form" name="sblh_identifier" style="max-width:300px; width: 100%;" action="" method="">
        <label><b>Post List</b></label>
        <select style="width: 100%;" name="post_list[]" multiple required>
            <?php foreach ($posts as $item) {
                echo '<option value="' . $item->ID . '">' . $item->post_title . '</option>';
            } ?>

        </select>
        <input type="hidden" name="action" value="find_broken_links" />
        <?php wp_nonce_field('find_broken_links', 'nonce_field') ?>
        <button type="submit" onclick="" style="margin-top: 10px;" class="button button-primary">Submit</button>
    </form>
</div>

<script>
    jQuery(function($) {
        const form = jQuery('form[name="sblh_identifier"]');

        $(form).on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                method: 'post',
                data: $(form).serialize()
            }).done(function(res) {
                alert(res.message);
            })
        })
    })
</script>