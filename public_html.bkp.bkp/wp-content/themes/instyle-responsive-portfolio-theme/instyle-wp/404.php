<?php $themePath = get_template_directory_uri(); ?>
<?php get_header(); ?>
<div class="span13 offset3" role="main">
    <div id="fullwidth">
        <article class="post">
              <h3 class="posttitle"><?php _e("404 Error", "instyle"); ?></h3>
              <div class="entry-content">
              <h2><?php _e("Page Not Found", "instyle"); ?></h2>
                <p><?php _e("Our Apologies, but the page you are looking for could not be found.", "instyle"); ?></p>
              </div> 
        </article>
    </div>
</div>
<?php get_footer(); ?>