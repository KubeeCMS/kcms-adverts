	
	<?php
	/*<footer>
		<div class="footer_content">
			<section class="footer_description">

			</section>
			<nav>
				<!-- footer menu area -->
			</nav>
			
			
			<section class="footer_bottom">
				<div class="info_line">Adning, Lightning fast Advertising - Modern "All In One" Wordpress advertising plugin.</div>

				<div class="legal">
					<div class="copyright">
						Copyright &copy; <?php echo date('Y'); ?> <a href="http://adning.com/" target="_blank">Adning</a>
						
						<div class="legal-menu"></div>
					</div>
				</div>
				
			</section>

		</div>
	</footer>
	*/
	?>
	
   
    
	<?php if(function_exists('wp_footer')) { wp_footer(); } ?>
	<script type="text/javascript" src="<?php echo ADNI_ASSETS_URL; ?>/dist/angwp_admin.bundle.js"></script>
	<script type="text/javascript" src="<?php echo ADNI_ASSETS_URL; ?>/dist/angwp_frontend_manager.bundle.js"></script>
</body>
</html>

<script>
jQuery(document).ready(function($){
	$(".top_bar").stick_in_parent();
});
</script>