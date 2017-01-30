<?php themify_sidebar_before(); //hook ?>
<aside id="sidebar" itemscope="itemscope" itemtype="https://schema.org/WPSidebar">
	<?php themify_sidebar_start(); //hook ?>

	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-main') ); ?>

	<?php themify_sidebar_alt_end(); //hook ?>
</aside>
<!-- /#sidebar -->
<?php themify_sidebar_alt_after(); //hook ?>
