<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>        
        <input type="search" class="search-field" placeholder="Search CDPI's knowledge base" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
    </label>    
</form>
