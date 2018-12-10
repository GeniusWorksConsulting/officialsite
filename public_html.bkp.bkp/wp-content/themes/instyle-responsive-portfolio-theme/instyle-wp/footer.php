<?php $themePath = get_template_directory_uri(); ?>
</div>
</div>
<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo $themePath ?>/js/theme.js"></script>
<?php echo get_option_tree ('ga', ''); ?>
</body>
</html>