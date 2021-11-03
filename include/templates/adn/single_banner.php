<?php
/* Template Name: ADning single banner */
require_once(ADNI_TPL_DIR.'/adn/adn_header.php');
$html = '';

/*ADNI_Init::enqueue(
	array(
		'files' => array(
			array('file' => '_ning_css', 'type' => 'style'),
			array('file' => '_ning_jquery_plugins', 'type' => 'script')
		)
	)
);*/

if ( have_posts() ) : while ( have_posts() ) : the_post();
	//$html.= apply_filters( 'the_content', get_the_content());
	//$html.= ADNI_Templates::banner_tpl(get_the_ID());
	$html.= ADNI_Multi::do_shortcode('[adning id="'.get_the_ID().'" filter=0]');

endwhile; else:
	$html.= '<center><h2>'.__('This banner does not exists.', 'adn').'</h2></center>';
endif;
wp_reset_query();

echo $html;


require_once(ADNI_TPL_DIR.'/adn/adn_footer.php');
?>