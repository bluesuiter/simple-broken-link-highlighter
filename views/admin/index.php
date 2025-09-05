<div class="wrap">
    <h1>Identify Broken Links</h1>

    <form class="" name="sblh_identifier" action="" method="">
        <input type="text" name="post_list" placeholder="example, 12, 13, 14" />
        <input type="hidden" name="action" value="find_broken_links" />
        <?php wp_nonce_field('find_broken_links', 'nonce_field') ?>
        <button type="submit" onclick="" class="button primary-button">Submit</button>
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
                console.log(res)
            })
        })
    })
</script>