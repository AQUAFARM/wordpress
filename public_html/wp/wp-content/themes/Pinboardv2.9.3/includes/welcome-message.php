<?php
/**
 * User-set welcome message. Can be filtered using 'themify_welcome_message'.
 * @var String|null
 */
$welcome_message = apply_filters('themify_welcome_message', function_exists( 'icl_t' )? icl_t('Themify', 'setting-homepage_welcome', themify_get('setting-homepage_welcome')) : themify_get('setting-homepage_welcome'));

if($welcome_message != ''){ ?>
	
	<div class="pagewidth">

	<?php themify_welcome_before(); //hook ?>
	
	<div class="welcome-message">
		
    	<?php themify_welcome_start(); //hook ?>
        
		<?php echo do_shortcode($welcome_message); ?>
        
        <?php themify_welcome_end(); //hook ?>
        
	</div>
	<!--/welcome message -->
	
    <?php themify_welcome_after(); //hook ?>
    
    </div>

<?php } ?>