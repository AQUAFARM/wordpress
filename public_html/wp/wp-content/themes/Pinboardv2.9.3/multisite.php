<?php
/*
Plugin Name: WordPress MU Sitewide Tags Pages
Plugin URI: http://ocaoimh.ie/wordpress-mu-sitewide-tags/
Description: Creates a blog where all the most recent posts on a WordPress network may be found.
Version: 0.4.2
Author: Donncha O Caoimh
Author URI: http://ocaoimh.ie/
*/
/*  Copyright 2008 Donncha O Caoimh (http://ocaoimh.ie/)
    With contributions by Ron Rennick(http://wpmututorials.com/), Thomas Schneider(http://www.im-web-gefunden.de/) and others.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function themify_add_multisite_menu() {
	if( current_user_can('manage_network_options') && is_main_site() )
		call_user_func( 'add_sub' . 'menu_page', 'themify', __( 'Multisite Setup', 'themify' ), __( 'Multisite Setup', 'themify' ), 'manage_options', 'themify_multisite', 'themify_multisite_ui' );
}

function themify_multisite_ui() {
	global $wpdb, $current_site;
	
	if( $current_site->blog_id )
		$id = $current_site->blog_id;
	else
		$id = $wpdb->get_var( "SELECT blog_id FROM {$wpdb->blogs} WHERE domain = '{$current_site->domain}' AND path = '{$current_site->path}'" );
	
	if( $id ) {
		update_themify_ms_option( 'tags_blog_id', $id, true );
	}
	
	?>
	<div class="wrap">
		<h2> <?php _e( 'Multisite Setup', 'themify' ); ?></h2>
		
		<form name="themify_enable_multisite_posting" action="" method="POST">
			<p>
				<?php _e( 'Enable Multisite Posting', 'themify' ); ?>
				<select name="enable_multisite" id="enable_multisite">
					<?php $themify_enable_multisite = get_site_option('themify_enable_multisite', 'disable') ?>
					<option value="disable" <?php selected( $themify_enable_multisite, 'disable' ); ?>>
						<?php _e('Disable', 'themify'); ?>
					</option>
					<option value="enable" <?php selected( $themify_enable_multisite, 'enable' ); ?>>
						<?php _e('Enable', 'themify'); ?>
					</option>
				</select>
			</p>
		</form>
		<style type="text/css" media="screen">
			#enable_multisite{ margin-left: 10px; }
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#enable_multisite').change(function() {
					var thisVal = $(this).val();
					$.post(
						ajaxurl,
						{
							'action': 'themify_enable_multisite',
							'data': thisVal,
							'nonce' : '<?php echo wp_create_nonce('themify_enable_multisite'); ?>'
						},
						function(data) {
							$('#themify_collect_posts_form').fadeToggle();
						}
					);
				});
			});
		</script>
		<?php if( 'disable' == $themify_enable_multisite ) $disable_style = 'style="display:none;"'; ?>
		<form id="themify_collect_posts_form" <?php echo $disable_style; ?> name="global_tags" action="" method="GET">
			<input type="hidden" name="page" value="themify_multisite" />
			<?php wp_nonce_field('themify_multisite', '_wpnonce', true) ?>
			<p><?php _e( 'Press "Collect Posts" to import the existing posts from all micro sites to the main site.', 'themify' ) ?></p>
			<div class="submit">
				<input class="button-primary" type="submit" value="<?php _e( 'Collect Posts', 'themify' ); ?>" />
			</div>
		</form>
	</div>
	<?php
}

function themify_enable_multisite() {
	check_ajax_referer( 'themify_enable_multisite', 'nonce' );
	if($_POST['data']){
		update_site_option('themify_enable_multisite', $_POST['data']);
		echo $_POST['data'];
	}
	die;
};

function themify_ms_update_options() {
	global $wpdb, $current_site, $current_user, $wp_version;

	$valid_nonce = isset($_REQUEST['_wpnonce']) ? wp_verify_nonce($_REQUEST['_wpnonce'], 'themify_multisite') : false;
	if ( !$valid_nonce ) return false;
	
	$c = isset( $_GET[ 'c' ] ) ? (int)$_GET[ 'c' ] : 0; // blog count
	$p = isset( $_GET[ 'p' ] ) ? (int)$_GET[ 'p' ] : 0; // post count
	
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	if( !$tags_blog_id )
		return false;
		
	$blogs = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs ORDER BY blog_id DESC LIMIT %d,5", $c ) );

	foreach( $blogs as $blog ) {
		if( $blog != $tags_blog_id ) {
			$details = get_blog_details( $blog );
			$url = add_query_arg( array( 'p' => $p, 'action' => 'themify_multisite-populate', 'key' => md5( serialize( $details ) ) ), $details->siteurl );
			$p = 0;
			$post_count = 0;

			$result = wp_remote_get( $url, array('timeout' => 10000) );
			if ( is_wp_error( $result ) ) {
				var_dump($result->errors);
				die( '<p>'.__('Error redirecting url.', 'themify').'</p>' );
			}
			if( isset( $result['body'] ) )
				$post_count = (int)$result['body'];
				
			if( $post_count ) {
				$p = $post_count;
				break;
			}
		}
		$c++;
	}
	if( !empty( $blogs ) ) {
		$url = admin_url( 'admin.php' );
		wp_redirect( wp_nonce_url( $url , 'themify_multisite' ) . "&page=themify_multisite&action=populateblogs&c=$c&p=$p" );
		die();
	}
	$tags_blog_url = get_blog_option($tags_blog_id, 'siteurl ');
	wp_die( sprintf(
		'%s <a href="'.$tags_blog_url.'">' . get_bloginfo( 'name' ) . '</a>
		<br/>
	 	<small>
	 	<a href="'.admin_url('edit.php').'">%s</a>
	 	</small>
		',
		__('Finished importing posts into', 'themify'),
		__('Back to main site posts', 'themify')
	),	__('Post Collection', 'themify') );		

}

/*
run populate function in local blog context because get_permalink does not produce the correct permalinks while switched
*/
function themify_ms_populate_posts() {
	global $wpdb;

	$valid_key = isset( $_REQUEST['key'] ) ? $_REQUEST['key'] == md5( serialize( get_blog_details( $wpdb->blogid ) ) ) : false;
	if ( !$valid_key )
		return false;
		
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	
	if( !$tags_blog_id || $tags_blog_id == $wpdb->blogid )
		exit( '0' );

	$posts_done = 0;
	$p = isset( $_GET[ 'p' ] ) ? (int)$_GET[ 'p' ] : 0; // post count
	while ( $posts_done < 300 ) {
		$posts = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' LIMIT %d, 50", $p + $posts_done ) );

		if ( empty( $posts ) ) exit( '0' );
		
		foreach ( $posts as $post ) {
			if ( $post != 1 && $post != 2 )
				themify_ms_post( $post, get_post( $post ) );
		}
		$posts_done += 50;
	}
	exit( $posts_done );
}

function themify_ms_post( $post_id, $post ) {
	global $wpdb;

	// wp_insert_category()
	include_once(ABSPATH . 'wp-admin/includes/admin.php');

	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	if( !$tags_blog_id || $wpdb->blogid == $tags_blog_id )
		return;
	
	$allowed_post_types = apply_filters( 'themify_collect_post_types', array( 'post' => true ) );
	if ( !$allowed_post_types[$post->post_type] ) 
		return;

	$post_blog_id = $wpdb->blogid;

	$post->post_category = wp_get_post_categories( $post_id );
	$cats = array();
	foreach( $post->post_category as $c ) {
		$cat = get_category( $c );
		$cats[] = array( 'name' => esc_html( $cat->name ), 'slug' => esc_html( $cat->slug ) );
	}

	$post->tags_input = implode( ', ', wp_get_post_tags( $post_id, array('fields' => 'names') ) );

	$post->guid = $post_blog_id . '.' . $post_id;
	
	/**
	 * Get all images attached to post
	 */
	$att_images = get_posts(array(
		'order' => 'ASC',
		'numberposts' => -1,
		'post_parent' => $post_id,
		'orderby' => 'menu_order',
		'post_mime_type' => 'image',
		'post_type' => 'attachment'
	));
	$att_images_url = '';
	if($att_images) {
		foreach ( $att_images as $image ) {
			$img = wp_get_attachment_image_src($image->ID, 'full');
			$att_images_url .= $img[0] . '|';
		}
		$att_images_url = substr($att_images_url, 0, -1);
	}

	$global_meta = array();
	$global_meta['permalink'] = get_permalink( $post_id );
	$global_meta['blogid'] = $org_blog_id = $wpdb->blogid; // blog_id of the microsite
	$global_meta['postid'] = $post_id; // post id in the microsite
	$global_meta['termscat'] = get_the_category_list( ', ', '', $post_id );
	$global_meta['authorlink'] = site_url();
	$global_meta['authorname'] = get_the_author_meta('display_name', $post->post_author); //get_bloginfo('name');
	$global_meta['att_images_url'] = $att_images_url;
	
	
	$thekeys = array(
		'post_image',
		'feature_size',
		'image_width',
		'layout',
		'image_height',
		'hide_post_title',
		'unlink_post_title',
		'hide_post_meta',
		'hide_post_date',
		'hide_post_image',
		'unlink_post_image',
		'video_url',
		'external_link',
		'lightbox_link'
	);
	$meta_keys = apply_filters( 'themify_ms_meta_keys', $thekeys );
	if( is_array( $meta_keys ) && !empty( $meta_keys ) ) {
		foreach( $meta_keys as $key )
			$global_meta[$key] = get_post_meta( $post->ID, $key, true );
	}
	unset( $meta_keys );
	
	if( !get_post_meta( $post->ID, 'post_image', true ) ) {
		if( $thumb_id = get_post_meta( $post->ID, '_thumbnail_id', true ) ) {
			$thumb_src = wp_get_attachment_image_src($thumb_id, 'full');
			$global_meta['post_image'] = $thumb_src[0];
			$global_meta['_thumbnail_id'] = $thumb_id;
		}
	}
	
	//Original code
	/*if( $thumb_id = get_post_meta( $post->ID, '_thumbnail_id', true ) ) {
		$thumb_size = apply_filters( 'themify_ms_thumb_size', 'thumbnail' );
		$global_meta['thumbnail_html'] = wp_get_attachment_image( $thumb_id, $thumb_size );
	}*/

	// custom taxonomies 
	$taxonomies = apply_filters( 'themify_ms_custom_taxonomies', array() );
	if( !empty( $taxonomies ) && $post->post_status == 'publish' ) {
		$registered_tax = array_diff( get_taxonomies(), array( 'post_tag', 'category', 'link_category', 'nav_menu' ) );
		$custom_tax = array_intersect( $taxonomies, $registered_tax );
		$tax_input = array();
		foreach( $custom_tax as $tax ) {
			$terms = wp_get_object_terms( $post_id, $tax, array( 'fields' => 'names' ) );
			if( empty( $terms ) )
				continue;
			if( is_taxonomy_hierarchical( $tax ) )
				$tax_input[$tax] = $terms;
			else
				$tax_input[$tax] = implode( ',', $terms );
		}
		if( !empty( $tax_input ) )
				$post->tax_input = $tax_input;
	}
	/**
	 * Start Post in Main Blog
	 */
	switch_to_blog( $tags_blog_id );
	if( is_array( $cats ) && !empty( $cats ) && $post->post_status == 'publish' ) {
		foreach( $cats as $t => $d ) {
			$term = get_term_by( 'slug', $d['slug'], 'category' );
			if( $term && $term->parent == 0 ) {
				$category_id[] = $term->term_id;
				continue;
			}
			/* Here is where we insert the category if necessary */
			wp_insert_category( array('cat_name' => $d['name'], 'category_description' => $d['name'], 'category_nicename' => $d['slug'], 'category_parent' => '') );

			/* Now get the category ID to be used for the post */
			$category_id[] = $wpdb->get_var( "SELECT term_id FROM " . $wpdb->get_blog_prefix( $tags_blog_id ) . "terms WHERE slug = '" . $d['slug'] . "'" );
		}
	}

	$global_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE guid IN (%s,%s)", $post->guid, esc_url( $post->guid ) ) );
	if( $post->post_status != 'publish' && is_object( $global_post ) ) {
		wp_delete_post( $global_post->ID );
	} else {
		if( $global_post->ID != '' ) {
			$post->ID = $global_post->ID; // editing an old post
			
			foreach( array_keys( $global_meta ) as $key )
				delete_post_meta( $global_post->ID, $key );
		} else {
			unset( $post->ID ); // new post
		}
	}
	
	if( 'publish' == $post->post_status ) {
		$post->ping_status = 'closed';
		//$post->comment_status = 'closed';

		/* Use the category ID in the post */
	        $post->post_category = $category_id;

		$p = wp_insert_post( $post );
		foreach( $global_meta as $key => $value ){
			if( $value )
				add_post_meta( $p, $key, $value );
		}
		
		/************************* Insert image *****************************/
		if( '' != get_post_meta($p, 'att_images_url', true) ){
			if( $attachments_id = get_post_meta( $p, 'attachments_id', true ) ){
				$attachments_id = explode('|', $attachments_id );
				$attachments_id_count = count($attachments_id);
				for($index = 0; $index < $attachments_id_count; $index++){
					wp_delete_attachment($attachments_id[$index], true);
				}
			}
			$attachments_id = '';
			
			$img_urls  = explode('|', get_post_meta($p, 'att_images_url', true));
			$img_count = count($img_urls);
			
			for($index = 0; $index < $img_count; $index++){
	
				$tmp = download_url( $img_urls[$index], 10000 );
		
				// Set variables for storage
				// fix file filename for query strings
				preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $img_urls[$index], $matches);
				$file_array['name'] = basename($matches[0]);
				$file_array['tmp_name'] = $tmp;
		
				// If error storing temporarily, unlink
				if ( is_wp_error( $tmp ) ) {
					@unlink($file_array['tmp_name']);
					$file_array['tmp_name'] = '';
					var_dump($tmp->errors);
					die( '<p>'.__('Error retrieving image from blog.', 'themify').'</p>' );
				}
		
				// do the validation and storage stuff
				$attachment_id = media_handle_sideload( $file_array, $p );
				// If error storing permanently, unlink
				if ( is_wp_error($attachment_id) ) {
					@unlink($file_array['tmp_name']);
					var_dump($attachment_id->errors);
					die( '<p>'.__('Error sideloading the image.', 'themify').'</p>' );
				}
				
				$attachments_id .= $attachment_id . '|';
			}
			update_post_meta( $p, 'attachments_id', substr($attachments_id, 0, -1) );
		}
		/************************* End Insert image **************************/
		
		// por lo menos tiene una imagen en post_image
		if( '' != $global_meta['post_image'] ){
			// get images de este post (ya están adjuntadas las nuevas)
			$attached = get_posts( array(
				'order' => 'ASC',
				'numberposts' => -1,
				'post_parent' => $p,
				'orderby' => 'menu_order',
				'post_mime_type' => 'image',
				'post_type' => 'attachment'
			));
			
			if($attached) {
				// loopear comparando el nombre base de las adjuntadas y post_image
				foreach ( $attached as $image ) {
					//$attached_img = wp_get_attachment_image_src($image->ID, 'full');
					// si es igual, tomar el id y el guid
					if( basename($image->guid) == basename($global_meta['post_image']) ){
						$thumbnail_id = $image->ID;
					}
				}
				if( isset($thumbnail_id) && '' != $thumbnail_id ){
					// set_post_thumbnail con id y guid
					set_post_thumbnail( $p, $thumbnail_id );
				}
			}
		}
	}
	restore_current_blog();
}

function themify_ms_post_delete( $post_id ) {
	/*
	 * what should we do if a post will be deleted and the tags blog feature is disabled?
	 * need an check if we have a post on the tags blog and if so - delete this
	 */
	global $wpdb;
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	if( null === $tags_blog_id )
		return;

	if( $wpdb->blogid == $tags_blog_id )
		return;

	$post_blog_id = $wpdb->blogid;
	switch_to_blog( $tags_blog_id );
	$guid = "{$post_blog_id}.{$post_id}";
	$global_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid IN (%s,%s)", $guid, esc_url( $guid ) )  );
	if( null !== $global_post_id )
		wp_delete_post( $global_post_id );

	restore_current_blog();
}

/**
 * remove all posts from a given blog ($blog_id != 0)
 * - used if a blog is deleted or marked as deactivat, spam, archive, mature
 * - also runs if a blog is switched to a none public blog (called by
 *   themify_ms_public_blog_update), more details on themify_ms_public_blog_update
 * removes some posts if the limit is reached ($blog_id == 0)
 * - triggered by other actions but without an given blog_id
 * - number of posts to delete in $max_to_del
 * 
 * @param $blog_id
 */
function themify_ms_remove_posts($blog_id = 0) {
	global $wpdb;
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	$max_to_del = 10;
	
	if( !$tags_blog_id )
		return;

	/* actions on the tags blog */
	if ( ($blog_id == 0) && ($wpdb->blogid == 1) )
		return;		
	if ( 1 == $blog_id )
		return;

	switch_to_blog( 1 );

	if ( $blog_id != 0 ) {
		$posts = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE guid LIKE '" . $blog_id . ".%' OR guid LIKE '" . esc_url( $blog_id ) . ".%'" );
		if( is_array( $posts ) && !empty( $posts ) ) {
			foreach( $posts as $p_id ) {
				wp_delete_post( $p_id );
			}
		}
	} else {
		/* delete all posts over the max limit */
		if( mt_rand( 0, 10 ) ) {
			$allowed_post_types = apply_filters( 'themify_collect_post_types', array( 'post' => true ) );
			if( is_array( $allowed_post_types ) && !empty( $allowed_post_types ) ) {
				$post_types = array();
				foreach( $allowed_post_types as $k => $v ) {
					if( $v ) {
						$post_types[] = $k;
					}
				}
				if( is_array( $post_types ) && !empty( $post_types ) ) {
					if( count( $post_types ) > 1 ) 
						$where = "IN ('" . join( "','", $post_types ) . "') ";
					else
						$where = "= '" . $post_types[0] . "' ";
				} else {
					$where = "= 'post' ";
				}
				$posts = $wpdb->get_results( "SELECT ID, guid FROM {$wpdb->posts} WHERE post_status='publish' AND post_type {$where} ORDER BY ID DESC limit " . apply_filters( 'themify_multisite_max_posts', 100000 ) . ", " . $max_to_del );
				if( is_array( $posts ) && !empty( $posts ) ) {
					foreach( $posts as $p ) {
						if( preg_match('|^.*\.([0-9]+)$|', $p->guid, $matches) && intval( $matches[1] ) > 0 )
							wp_delete_post( $p->ID );
					}
				}
			}
		}
	}
	restore_current_blog();
}

/**
 * called as an action if the public state for a blog is switched
 * - if a blog becomes not public - all posts in the tags blog will be removed
 * - bug on 1.5.1: update_option_blog_public is only triggered if the public state 
 *   is changed from the backend - from edit blog as siteadmin the action isn't
 *   running and the state in the blogs backend isn't changed
 *
 * @param int $old - old public state
 * @param int $new - new state, public == 1, not public == 0
 */
function themify_ms_public_blog_update($old, $new) {
	global $wpdb;
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );

	if( !$tags_blog_id )
		return;

	/* the tags blog */
	if ( $tags_blog_id == $wpdb->blogid )
		return;
	
	if ($new == 0 ) {
		themify_ms_remove_posts($wpdb->blogid);
	}
}

function themify_ms_post_link( $link, $post ) {
	global $wpdb;
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	if( !$tags_blog_id )
		return $link;

	if( $wpdb->blogid == $tags_blog_id ) {
		if( is_numeric( $post ) )
			$url = get_post_meta( $post, 'permalink', true );
		else
			$url = get_post_meta( $post->ID, "permalink", true );

		if( $url )
			return $url;
	}

	return $link;
}

function themify_ms_thumbnail_link( $html, $post_id ) {
	global $wpdb;
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	if( !$tags_blog_id )
		return $html;

	if( $wpdb->blogid == $tags_blog_id ) {
		$thumb = get_post_meta( $post_id, 'thumbnail_html', true );
		if( $thumb )
			return $thumb;
	}
	return $html;
}

function get_themify_ms_option( $key, $default = false ) {
	static $tags_options = '1';
	if( $tags_options == '1' ) {
		$tags_options = get_site_option('themify_ms_blog');
	}
	if( is_array( $tags_options ) ) {
		if( $key == 'all' )
			return $tags_options;
		elseif( isset( $tags_options[$key] ) )
			return $tags_options[$key];
	}
	return get_site_option($key, $default);
}

function update_themify_ms_option( $key, $value = '', $flush = false ) {
	static $tags_options = '1';
	if( $tags_options == '1' ) {
		
		$tags_options = get_site_option('themify_ms_blog');
	}
	if( !$tags_options ) {
		$tags_options = array();
	}
	if( $key !== true)
		$tags_options[$key] = $value;
	if( $flush || $key === true )
		return update_site_option( 'themify_ms_blog', $tags_options );
}

/**
 * Sync comment when insert comment
 * @return type
 */
function themify_sitewide_insert_comment($id, $comment) {
	global $wpdb;
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	if( null === $tags_blog_id )
		return;

	if( $wpdb->blogid == $tags_blog_id )
		return;

	$my_id = $comment->comment_post_ID;

	$post_blog_id = $wpdb->blogid;
	switch_to_blog( $tags_blog_id );
	$guid = $post_blog_id.".".$my_id;
	$global_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid IN (%s,%s)", $guid, esc_url( $guid ) )  );
	
	// check if it has parent id
	$global_comment_id = 0;
	if($comment->comment_parent > 0){
		$private_com_guid = $post_blog_id.'.'.$comment->comment_parent;
		$global_comment_id = $wpdb->get_var( $wpdb->prepare( "SELECT comment_id FROM {$wpdb->commentmeta} WHERE meta_key = 'site_id' AND meta_value='%s'", $private_com_guid)  );
	}

	$data = array(
	  'comment_post_ID' => $global_post_id,
	  'comment_author' => $comment->comment_author,
	  'comment_author_email' => $comment->comment_author_email,
	  'comment_author_url' => $comment->comment_author_url,
	  'comment_content' => $comment->comment_content,
	  'comment_type' => '',
	  'comment_parent' => $global_comment_id,
	  'user_id' => $comment->user_id,
	  'comment_author_IP' => $comment->comment_author_IP,
	  'comment_agent' => $comment->comment_agent,
	  'comment_date' => $comment->comment_date,
	  'comment_approved' => $comment->comment_approved,
	);

	$new_comment_id = wp_insert_comment($data);
	$com_guid = $post_blog_id.'.'.$comment->comment_ID;

	// insert site information
	add_comment_meta( $new_comment_id, 'site_id', $com_guid);

	restore_current_blog();
}

/**
 * Comment status changes
 * @param type $new_status 
 * @param type $old_status 
 * @param type $comment 
 * @return type
 */
function themify_sitewide_approve_comment_callback($new_status, $old_status, $comment) {
	global $wpdb;
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	if( null === $tags_blog_id )
		return;

	if( $wpdb->blogid == $tags_blog_id )
		return;

	$my_id = $comment->comment_post_ID;

	$post_blog_id = $wpdb->blogid;
	switch_to_blog( $tags_blog_id );
	$guid = $post_blog_id.".".$my_id;
	$global_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid IN (%s,%s)", $guid, esc_url( $guid ) )  );
	$global_comment_id = $wpdb->get_var( $wpdb->prepare( "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_post_ID = '%d' AND comment_date = '%s' AND user_id = '%d'", $global_post_id, $comment->comment_date, $comment->user_id )  );
	restore_current_blog();

	if($old_status != $new_status) {

		switch ( $new_status ) {
			case 'unapproved':
				$new_status = '0';
				break;
			case 'approved':
				$new_status = '1';
				break;
		}

    switch_to_blog( $tags_blog_id );
		$commentarr = get_comment($global_comment_id, ARRAY_A);
		$commentarr['comment_approved'] = $new_status;
		$commentarr['comment_author'] = $comment->comment_author;
		$commentarr['comment_author_email'] = $comment->comment_author_email;
		$commentarr['comment_author_url'] = $comment->comment_author_url;
		$commentarr['comment_content'] = $comment->comment_content;
		wp_update_comment($commentarr);
		restore_current_blog();
	}

}

/**
 * Edit comment sitewide sync
 * @param type $comment_ID 
 * @return type
 */
function themify_sitewide_edit_comment($comment_ID) {
	global $wpdb;
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	if( null === $tags_blog_id )
		return;

	if( $wpdb->blogid == $tags_blog_id )
		return;

	$temp_commentarr = get_comment($comment_ID);
	
	$post_blog_id = $wpdb->blogid;
	switch_to_blog( $tags_blog_id );
	$guid = $post_blog_id.".".$temp_commentarr->comment_post_ID;
	$global_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid IN (%s,%s)", $guid, esc_url( $guid ) )  );
	$global_comment_id = $wpdb->get_var( $wpdb->prepare( "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_post_ID = '%d' AND comment_date = '%s' AND user_id = '%d'", $global_post_id, $temp_commentarr->comment_date, $temp_commentarr->user_id )  );
	
	$commentarr = get_comment($global_comment_id, ARRAY_A);
	$commentarr['comment_approved'] = $temp_commentarr->comment_approved;
	$commentarr['comment_author'] = $temp_commentarr->comment_author;
	$commentarr['comment_author_email'] = $temp_commentarr->comment_author_email;
	$commentarr['comment_author_url'] = $temp_commentarr->comment_author_url;
	$commentarr['comment_content'] = $temp_commentarr->comment_content;
	wp_update_comment($commentarr);

	restore_current_blog();
}

/**
 * Delete comment sitewide sync
 * @param type $comment_ID 
 * @return type
 */
function themify_sitewide_delete_comment($comment_ID) {
	global $wpdb;
	$tags_blog_id = get_themify_ms_option( 'tags_blog_id' );
	if( null === $tags_blog_id )
		return;

	if( $wpdb->blogid == $tags_blog_id )
		return;

	$temp_commentarr = get_comment($comment_ID);
	
	$post_blog_id = $wpdb->blogid;
	switch_to_blog( $tags_blog_id );
	$guid = $post_blog_id.".".$temp_commentarr->comment_post_ID;
	$global_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid IN (%s,%s)", $guid, esc_url( $guid ) )  );
	$global_comment_id = $wpdb->get_var( $wpdb->prepare( "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_post_ID = '%d' AND comment_date = '%s' AND user_id = '%d'", $global_post_id, $temp_commentarr->comment_date, $temp_commentarr->user_id )  );
	
	wp_delete_comment($global_comment_id, true);

	restore_current_blog();
}

/* Add menu entry */
add_action('admin_menu', 'themify_add_multisite_menu', 12);

/* Setup multisite posting */
add_action('wp_ajax_themify_enable_multisite', 'themify_enable_multisite');

// Get multisite posting status
$themify_enable_multisite = get_site_option('themify_enable_multisite', 'disable');

// Is multisite posting enabled?
if( 'enable' == $themify_enable_multisite ){
	/* Save options */
	if ( !empty( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'themify_multisite' )
		add_action( 'admin_init', 'themify_ms_update_options' );
	
	/* Collect posts */
	if ( !empty( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'themify_multisite-populate' )
		add_action( 'init', 'themify_ms_populate_posts' );
	
	/* Add/Update/Trash/Delete Post */
	add_action('save_post', 'themify_ms_post', 10, 2);
	add_action( 'trash_post', 'themify_ms_post_delete' );
	add_action( 'delete_post', 'themify_ms_post_delete' );
	
	/* Complete blog actions ($blog_id != 0) */
	add_action('delete_blog', 'themify_ms_remove_posts', 10, 1);
	add_action('archive_blog', 'themify_ms_remove_posts', 10, 1);
	add_action('deactivate_blog', 'themify_ms_remove_posts', 10, 1);
	add_action('make_spam_blog', 'themify_ms_remove_posts', 10, 1);
	add_action('mature_blog', 'themify_ms_remove_posts', 10, 1);
	
	/* Single post actions ($blog_id == 0) */
	add_action('transition_post_status', 'themify_ms_remove_posts');
	add_action('update_option_blog_public', 'themify_ms_public_blog_update', 10, 2);
	
	/* Post permalink */
	add_filter( 'post_link', 'themify_ms_post_link', 10, 2 );
	add_filter( 'page_link', 'themify_ms_post_link', 10, 2 );
	
	/* Get featured images */
	add_filter('post_thumbnail_html', 'themify_ms_thumbnail_link', 10, 2);

	/* Comment hooks site wide */
	add_action('wp_insert_comment', 'themify_sitewide_insert_comment', 10, 2);
	add_action('transition_comment_status', 'themify_sitewide_approve_comment_callback', 10, 3);
	add_action('edit_comment', 'themify_sitewide_edit_comment', 10, 1);
	add_action('delete_comment', 'themify_sitewide_delete_comment', 10, 1);

}

if ( ! class_exists( 'ThemifyMultisite' ) ) {
	/**
	 * Class to create a multisite hub in a page collecting posts from other blogs in the network
	 */	
	class ThemifyMultisite{
	
		function __construct(){
			add_action( 'init', array(&$this, 'init') );
			add_action( 'wp_enqueue_scripts', array(&$this, 'enqueue_scripts') );
		}
				
		/**
		 * Initialization function
		 */
		function init() {
			add_shortcode('login_box', array($this, 'login_box'));
			add_shortcode('themify_login_box', array($this, 'login_box'));
			add_shortcode('signup_box', array($this, 'signup_box'));
			add_shortcode('themify_signup_box', array($this, 'signup_box'));

			add_action('wp_ajax_themify_check_user', array(&$this, 'themify_check_user'));
			add_action('wp_ajax_nopriv_themify_check_user', array(&$this, 'themify_check_user'));
			
			add_action('wp_ajax_themify_check_site', array(&$this, 'themify_check_site'));
			add_action('wp_ajax_nopriv_themify_check_site', array(&$this, 'themify_check_site'));
			
			add_action('wp_ajax_themify_add_user_and_site', array(&$this, 'themify_add_user_and_site'));
			add_action('wp_ajax_nopriv_themify_add_user_and_site', array(&$this, 'themify_add_user_and_site'));
			
			add_action( 'activate_header', array(&$this, 'activate_header') );
			add_action( 'wp_head', array(&$this, 'activate_markup') );
			
			add_action('wpmu_activate_blog', array(&$this, 'set_user_role'), 99, 2);
			add_filter('wpmu_signup_user_notification', array(&$this, 'no_signup_user_notification'), 10, 4 );
		}

		function no_signup_user_notification($user, $user_email, $key, $meta) {
			return false;
		}
		
		function set_user_role($blog_id, $user_id) {
			switch_to_blog($blog_id);
			$user = new WP_User($user_id);
			$user->set_role('administrator');
			restore_current_blog();
		}
		
		function activate_header() {
			global $is_activation_screen;
			$is_activation_screen = true;
		}
		
		function activate_markup() {
			global $is_activation_screen;
			if( isset($is_activation_screen) && $is_activation_screen )
				echo '
				<!-- Activate Markup -->
				<script type="text/javascript">
				jQuery(document).ready(function($){
					$("#content").wrap("<div id=\'layout\' class=\'pagewidth clearfix sidebar-none\'/>");
				});
				</script>
				';
		}
		
		function themify_check_site() {
			check_ajax_referer( 'ajax_nonce', 'nonce' );
				
			$result = $this->wpmu_validate_blog_signup($_POST['blogname'], $_POST['blog_title']);
			extract($result);
			
			if ( $errors->get_error_code() ) {
				echo json_encode($errors);
				die();
			} else {
				echo json_encode( array('success', array(
					'domain' => $mydomain,
					'path' => $path,
					'blogname' => $blogname,
					'blog_title' => $blog_title
				)));
			}
			
			die();
		}

		function themify_check_user() {
			check_ajax_referer( 'ajax_nonce', 'nonce' );
				
			$result = $this->wpmu_validate_user_signup($_POST['user_name'], $_POST['user_email']);
			extract($result);
			
			if ( $errors->get_error_code() ) {
				echo json_encode($errors);
				die();
			} else {
				echo json_encode( array('success', array(
					'user_name' => $user_name,
					'user_email' => $user_email
				)));
			}
			
			die();
		}
		
		function wpmu_validate_user_signup($user_name, $user_email) {
			global $wpdb;
		
			$errors = new WP_Error();
		
			$orig_username = $user_name;
			$user_name = preg_replace( '/\s+/', '', sanitize_user( $user_name, true ) );
		
			if ( $user_name != $orig_username || preg_match( '/[^a-z0-9]/', $user_name ) ) {
				$errors->add( 'user_name', __( 'Only lowercase letters (a-z) and numbers are allowed.', 'themify' ) );
				$user_name = $orig_username;
			}
		
			$user_email = sanitize_email( $user_email );
		
			if ( empty( $user_name ) )
			   	$errors->add('user_name', __( 'Please enter a username.', 'themify' ) );
		
			$illegal_names = get_site_option( 'illegal_names' );
			if ( is_array( $illegal_names ) == false ) {
				$illegal_names = array(  'www', 'web', 'root', 'admin', 'main', 'invite', 'administrator' );
				add_site_option( 'illegal_names', $illegal_names );
			}
			if ( in_array( $user_name, $illegal_names ) == true )
				$errors->add('user_name',  __( 'That username is not allowed.', 'themify' ) );
		
			if ( is_email_address_unsafe( $user_email ) )
				$errors->add('user_email',  __('You cannot use that email address to signup. We are having problems with them blocking some of our email. Please use another email provider.', 'themify'));
		
			if ( strlen( $user_name ) < 4 )
				$errors->add('user_name',  __( 'Username must be at least 4 characters.', 'themify' ) );
		
			if ( strpos( ' ' . $user_name, '_' ) != false )
				$errors->add( 'user_name', __( 'Sorry, usernames may not contain the character &#8220;_&#8221;!', 'themify' ) );
		
			// all numeric?
			$match = array();
			preg_match( '/[0-9]*/', $user_name, $match );
			if ( $match[0] == $user_name )
				$errors->add('user_name', __('Sorry, usernames must have letters too!', 'themify'));
		
			if ( !is_email( $user_email ) )
				$errors->add('user_email', __( 'Please enter a correct email address.', 'themify' ) );
		
			$limited_email_domains = get_site_option( 'limited_email_domains' );
			if ( is_array( $limited_email_domains ) && empty( $limited_email_domains ) == false ) {
				$emaildomain = substr( $user_email, 1 + strpos( $user_email, '@' ) );
				if ( in_array( $emaildomain, $limited_email_domains ) == false )
					$errors->add('user_email', __('Sorry, that email address is not allowed!', 'themify'));
			}
		
			// Check if the username has been used already.
			if ( username_exists($user_name) )
				$errors->add('user_name', __('Sorry, that username is already taken.', 'themify'));
		
			// Check if the email address has been used already.
			if ( email_exists($user_email) )
				$errors->add('user_email', __('Sorry, that email address is already used.', 'themify'));
		
			// Has someone already signed up for this username?
			$signup = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->signups WHERE user_login = %s", $user_name) );
			if ( $signup != null ) {
				$registered_at =  mysql2date('U', $signup->registered);
				$now = current_time( 'timestamp', true );
				$diff = $now - $registered_at;
				// If registered more than two days ago, cancel registration and let this signup go through.
				if ( $diff > 172800 )
					$wpdb->delete( $wpdb->signups, array( 'user_login' => $user_name ) );
				else
					$errors->add('user_name', __('That username is taken.', 'themify'));
		
				if ( $signup->active == 0 && $signup->user_email == $user_email )
					$errors->add('user_email_used', __('Username and email used', 'themify'));
			}
		
			$signup = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->signups WHERE user_email = %s", $user_email) );
			if ( $signup != null ) {
				$diff = current_time( 'timestamp', true ) - mysql2date('U', $signup->registered);
				// If registered more than two days ago, cancel registration and let this signup go through.
				if ( $diff > 172800 )
					$wpdb->delete( $wpdb->signups, array( 'user_email' => $user_email ) );
				else
					$errors->add('user_email', __('That email address has already been used.', 'themify'));
			}
		
			$result = array('user_name' => $user_name, 'orig_username' => $orig_username, 'user_email' => $user_email, 'errors' => $errors);
		
			return apply_filters('wpmu_validate_user_signup', $result);
		}
		
		function wpmu_validate_blog_signup($blogname, $blog_title, $user = '') {
			global $wpdb, $domain, $base, $current_site;
		
			$blog_title = strip_tags( $blog_title );
			$blog_title = substr( $blog_title, 0, 50 );
		
			$errors = new WP_Error();
			$illegal_names = get_site_option( 'illegal_names' );
			if ( $illegal_names == false ) {
				$illegal_names = array( 'www', 'web', 'root', 'admin', 'main', 'invite', 'administrator' );
				add_site_option( 'illegal_names', $illegal_names );
			}
		
			// On sub dir installs, Some names are so illegal, only a filter can spring them from jail
			if (! is_subdomain_install() )
				$illegal_names = array_merge($illegal_names, apply_filters( 'subdirectory_reserved_names', array( 'page', 'comments', 'blog', 'files', 'feed' ) ) );
		
			if ( empty( $blogname ) )
				$errors->add('blogname', __( 'Please enter a site name.', 'themify' ) );
		
			if ( preg_match( '/[^a-z0-9]+/', $blogname ) )
				$errors->add('blogname', __( 'Only lowercase letters and numbers allowed.', 'themify' ) );
		
			if ( in_array( $blogname, $illegal_names ) == true )
				$errors->add('blogname',  __( 'That name is not allowed.', 'themify' ) );
		
			if ( strlen( $blogname ) < 4 && !is_super_admin() )
				$errors->add('blogname',  __( 'Site name must be at least 4 characters.', 'themify' ) );
		
			if ( strpos( ' ' . $blogname, '_' ) != false )
				$errors->add( 'blogname', __( 'Sorry, site names may not contain the character &#8220;_&#8221;!', 'themify' ) );
		
			// do not allow users to create a blog that conflicts with a page on the main blog.
			if ( !is_subdomain_install() && $wpdb->get_var( $wpdb->prepare( "SELECT post_name FROM " . $wpdb->get_blog_prefix( $current_site->blog_id ) . "posts WHERE post_type = 'page' AND post_name = %s", $blogname ) ) )
				$errors->add( 'blogname', __( 'Sorry, you may not use that site name.', 'themify' ) );
		
			// all numeric?
			$match = array();
			preg_match( '/[0-9]*/', $blogname, $match );
			if ( $match[0] == $blogname )
				$errors->add('blogname', __('Sorry, site names must have letters too!', 'themify'));
		
			$blogname = apply_filters( 'newblogname', $blogname );
		
			$blog_title = stripslashes(  $blog_title );
		
			if ( empty( $blog_title ) )
				$errors->add('blog_title', __( 'Please enter a site title.', 'themify' ) );
		
			// Check if the domain/path has been used already.
			if ( is_subdomain_install() ) {
				$mydomain = $blogname . '.' . preg_replace( '|^www\.|', '', $domain );
				$path = $base;
			} else {
				$mydomain = "$domain";
				$path = $base.$blogname.'/';
			}
			
			if ( username_exists( $blogname ) || domain_exists($mydomain, $path) ) {
				if ( is_object( $user ) == false
				|| ( is_object($user) && ( $user->user_login != $blogname ) )
				|| domain_exists($mydomain, $path) )
					$errors->add( 'blogname', __( 'Sorry, that site URL is already taken.', 'themify' ) );
			}
		
			// Has someone already signed up for this domain?
			$signup = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->signups WHERE domain = %s AND path = %s", $mydomain, $path) ); // TODO: Check email too?
			if ( ! empty($signup) ) {
				$diff = current_time( 'timestamp', true ) - mysql2date('U', $signup->registered);
				// If registered more than two days ago, cancel registration and let this signup go through.
				if ( $diff > 172800 )
					$wpdb->delete( $wpdb->signups, array( 'domain' => $mydomain , 'path' => $path ) );
				else
					$errors->add('blogname', __('That site is currently reserved.', 'themify'));
			}
		
			$result = array('domain' => $mydomain, 'path' => $path, 'blogname' => $blogname, 'blog_title' => $blog_title, 'errors' => $errors);
			return apply_filters('wpmu_validate_blog_signup', $result);
		}
		
		public function signup_box($atts='') {
			$html = '';
			
			// Main
			$active_signup = get_site_option( 'registration' );
			if ( !$active_signup )
				$active_signup = 'all';
			
			$active_signup = apply_filters( 'wpmu_active_signup', $active_signup ); // return "all", "none", "blog" or "user"
			
			// Make the signup type translatable.
			$i18n_signup['all'] = _x('all', 'Multisite active signup type');
			$i18n_signup['none'] = _x('none', 'Multisite active signup type');
			$i18n_signup['blog'] = _x('blog', 'Multisite active signup type');
			$i18n_signup['user'] = _x('user', 'Multisite active signup type');
			
			if ( is_super_admin() ){
				$html .= '<div class="mu_alert">';
				$html .= '<p>' . sprintf(
					__('Greetings Site Administrator! You are currently allowing &#8220;%s&#8221; registrations.', 'themify'),
				 	$i18n_signup[$active_signup]
					) . '</p>
						<p>' . sprintf(
					__('Registration of both sites and user accounts must be enabled for this to work. To change or disable registration go to your <a href="%s">Options page</a>.', 'themify'),
					esc_url(network_admin_url('settings.php'))
					) . '</p>';
				$html .= '</div>';
			}
			
			if ( $active_signup != 'all' ){
				$html .= '<p class="registration-disabled">' . __('Registration has been disabled.', 'themify') . '</p>';
				return $html;
			}
			
	        if ( !is_user_logged_in() ){
	            
				$html .= '<form id="themify_setupform" class="signup-box" method="post" action="' . admin_url( 'admin-ajax.php' ) . '">';
				$html .= '
				<p class="user_email-field">
					<label for="user_email">' . __('E-Mail', 'themify') . ':</label>
					<input name="user_email" type="text" id="user_email" value="">
					<small id="email_check"></small>
				</p>';
				$html .= '
				<p class="user_name-field">
					<label for="user_name">' . __('User name', 'themify') . ':</label>
					<input name="user_name" type="text" id="user_name" value="">
					<small id="username_check"></small>
				</p>';
				$html .= '
				<p class="blog_title-field">
					<label for="blog_title">' . __('Site Title', 'themify') . ':</label>
					<input name="blog_title" type="text" id="blog_title" value="">
					<small id="sitetitle_check"></small>
				</p>';
				$html .= '
				<p class="blogname-field">
					<label for="blogname">' . __('Site URL', 'themify') . ': </label>
					<span class="prefix_address">' . site_url() . '/</span>
					<input name="blogname" type="text" id="blogname" value="" maxlength="60">
					<small id="sitename_check"></small>
				</p>';
				$html .= '
				<p class="submit">
					<input type="submit" name="submit" class="submit-field" value="' . __('Submit', 'themify') . '">
				</p>';
				$html .= '</form>';
				$html .= '<div class="signup-messages"></div>';
				
	        } else {
	        	
				$html .= '<p class="is_logged_in">' . __('You are already logged in.', 'themify') . '</p>';
				
				if ( is_super_admin( get_current_user_id() ) ){
					$html .= '<p>'.sprintf( __('Go to %sDashboard%s?', 'themify'), '<a href="'.get_admin_url().'">', '</a>' ).'</p>';
				} else {
					$blogs = get_blogs_of_user( get_current_user_id() );

					if ( wp_list_filter( $blogs, array( 'userblog_id' => get_current_blog_id() ) ) )
						return;
				
					$blog_name = get_bloginfo( 'name' );
					
					foreach ( $blogs as $blog ) {
						$link .= "<a href='" . esc_url( get_admin_url( $blog->userblog_id ) ) . "'>" . __( 'Dashboard' ,'themify') . "</a>";
					}
					
					$html .= '<p>' . __('Go to', 'themify') . ' ' . $link . __('?', 'themify') . '</p>';
				}
	            
	        }
	        $html .= '<!-- Sign Up Box end -->';
	        return $html;
		}
		
		public function themify_add_user_and_site() {
			check_ajax_referer( 'ajax_nonce', 'nonce' );
			
			// Extra user validation and actual sign up
			$result = $this->wpmu_validate_user_signup($_POST['user_name'], $_POST['user_email']);
			extract($result);
			if ( $errors->get_error_code() ) {
				echo json_encode($errors);
				die();
			}
			
			// Extra blog validation and actual sign up
			$result = $this->wpmu_validate_blog_signup($_POST['blogname'], $_POST['blog_title']);
			extract($result);
			if ( $errors->get_error_code() ) {
				echo json_encode($errors);
				die();
			}
			
			$meta = array ('lang_id' => 1, 'public' => 1);
			$meta = apply_filters( 'add_signup_meta', $meta );
			
			wpmu_signup_user($user_name, $user_email, apply_filters( 'add_signup_meta', array() ) );
			wpmu_signup_blog(trailingslashit($domain), $path, $blog_title, $user_name, $user_email, $meta);
			echo json_encode( array('success', array(
					'domain' => trailingslashit($domain),
					'path' => $path,
					'blog_title' => $blog_title,
					'user_name' => $user_name,
					'user_email' => $user_email
				))
			);
			
			die();
		}
		
		function get_blog_paths() {
			global $wpdb;
			
			if ( false === ( $blog_paths = get_transient( 'blog_paths' ) ) ) {
				$raw_blog_paths = $wpdb->get_col($wpdb->prepare("
					SELECT path
					FROM $wpdb->blogs
					WHERE public = '1' AND
					archived = '0' AND
					spam = '0' AND
					deleted = '0'")
				);
				foreach ($raw_blog_paths as $blog_path) {
					$blog_paths[] = basename($blog_path);
				}
			set_transient( 'blog_paths', $blog_paths, 10 ); }
			
			return $blog_paths;
		}
		
		function login_box() {
	        global $user_login;
	        $html = '';
	        if ( !is_user_logged_in() ){
	            $html .= '
	            <form class="login-box" action="' . wp_login_url() . '" method="post">
		            <input type="text" placeholder="' . __('Email or Username', 'themify') . '" name="log" id="log" value="" size="20" />
		            <input type="password" placeholder="' . __('Password', 'themify') . '" name="pwd" id="pwd" size="20" />
		            <input type="submit" name="submit" value="' . __('Log In', 'themify') . '" class="button" />
		            <label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> ' . __('Remember me', 'themify') . '</label>
		            <input type="hidden" name="redirect_to" value="' . admin_url() . '" />
				</form>
				<p class="recover-pass">
					<a href="' . site_url('/wp-login.php?action=lostpassword') . '">' . __('Recover password &raquo;', 'themify') . '</a>
				</p>';
	        } else {
	            $html .= '<p class="is_logged_in">' . __('You are already logged in.', 'themify') . ' <a href="' . wp_logout_url($_SERVER['REQUEST_URI']) . '">' . __('Log Out?', 'themify') . '</a></p>';
	        }
	        $html .= '<!-- Login Box end -->';
	        return $html;
	    }
		
		function enqueue_scripts() {
			wp_enqueue_script( 'jquery-form' );
		}

	}
}

/**
 * Init ThemifyMultisite class
 */
$GLOBALS['themify_multisite'] = new ThemifyMultisite();

?>