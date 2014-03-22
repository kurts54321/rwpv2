<div class="wrap">
    <?php    echo "<h2>Random Writing Prompt v2 Options</h2>"; ?>
     
    <form name="rwpv_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <textarea name="rwpv2_prompts"><?php echo $lines; ?></textarea>         
     
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options', 'rwpv2_trdom' ) ?>" />
        </p>
    </form>
</div>