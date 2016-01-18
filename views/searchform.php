<!-- <div class="ui stackable search">
  <div class="ui icon input">
    <form role="search" method="post" class="search-form" action="'.home_url( "search/" ).'">
      <label>
          <input type="search" class="search-field" placeholder="'.esc_attr_x( 'Search …', 'placeholder' ).'" value="'.get_search_query().'" name="s" title="'.esc_attr_x( 'Search for:', 'label' ).'" />
      </label>
    </form>
    <i class="search icon"></i>
  </div>
</div> -->

<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
    <label>
        <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
        <input type="search" class="search-field"
            placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>"
            value="<?php echo get_search_query() ?>" name="s"
            title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
    </label>
    <input type="submit" class="search-submit"
        value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
</form>
