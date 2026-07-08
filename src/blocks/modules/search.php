<div class="search-box">
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="d-flex">
        <input type="text" name="s" class="form-control me-2" placeholder="Search..." value="<?php echo get_search_query(); ?>" />
    </form>
</div>