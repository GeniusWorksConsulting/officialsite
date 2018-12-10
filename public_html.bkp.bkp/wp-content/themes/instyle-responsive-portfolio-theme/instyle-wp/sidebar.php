<aside class="span4 sidebar">	
	<?php 
    if (is_page()) {
		generated_dynamic_sidebar(); 
    } else {
		dynamic_sidebar('instyle-blog');
    }
    ?>
</aside>