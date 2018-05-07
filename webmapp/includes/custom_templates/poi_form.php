<?php 

acf_form_head();

get_header();

?>
<div id="content">
	
	<?php
	
	acf_form(array(
		'post_id'		=> 'new_post',
		'post_title'	=> true,
		'post_content'	=> true,
		'new_post'		=> array(
			'post_type'		=> 'poi',
			'post_status'	=> 'draft'
		)
	));
	
	?>
	
</div>

<?php get_footer(); ?>