<?php 

if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'] ) ) { 
	die( 'You are not allowed to call this page directly.' ); 
}

// Form submitted, check the data
if ( isset( $_POST['frm_gNews_display'] ) && $_POST['frm_gNews_display'] == 'yes' ) {
	$did = isset( $_GET['did'] ) ? $_GET['did'] : '0';
	
	if ( ! is_numeric( $did ) ) { 
		die( '<p>Are you sure you want to do this?</p>' ); 
	}
	
	$gNews_success = '';
	$gNews_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare("SELECT COUNT(*) AS `count` 
						 	 FROM " . WP_G_NEWS_ANNOUNCEMENT . "
						 	 WHERE `gNews_id` = %d", array( $did )
	                      );
	$result = '0';
	$result = $wpdb->get_var( $sSql );
	
	if ( $result != '1') {
		?><div class="error fade"><p><strong><?php _e( 'Oops, selected details doesnt exist.', 'news-announcement-scroll' ); ?></strong></p></div><?php
	} else if ( isset( $_GET['ac'] ) && $_GET['ac'] == 'del' && isset( $_GET['did'] ) && $_GET['did'] != '' ) {       // Form submitted, check the action
		
		//	Just security thingy that wordpress offers us
		check_admin_referer( 'gNews_form_show' );
		
		//	Delete selected record from the table
		$sSql = $wpdb->prepare("DELETE FROM `".WP_G_NEWS_ANNOUNCEMENT."`
				                 WHERE `gNews_id` = %d
				                 LIMIT 1", $did 
				              );
		$wpdb->query( $sSql );
		
		//	Set success message
		$gNews_success_msg = TRUE;
		$gNews_success = __( 'Selected record was successfully deleted.', 'news-announcement-scroll' );
	}
	
	if ( $gNews_success_msg == TRUE ) {
		?><div class="updated fade"><p><strong><?php echo $gNews_success; ?></strong></p></div><?php
	}
}
?>
    
<div class="wrap">
    <div > 
	    <div style="float:left">
		    <h2><?php _e( 'News List', 'news-announcement-scroll' ); ?>
			    <a class="add-new-h2" href="<?php echo WP_G_NEWS_ADMIN_URL; ?>&amp;ac=add"><?php _e( 'Add New', 'news-announcement-scroll' ); ?></a>
		        <a class="add-new-h2" href="<?php echo WP_G_NEWS_ADMIN_URL; ?>&amp;ac=set"><?php _e( 'Widget Settings', 'news-announcement-scroll' ); ?></a>
			    <a class="add-new-h2" target="_blank" href="<?php echo WP_G_NEWS_HELP; ?>"><?php _e( 'Help', 'news-announcement-scroll' ); ?></a>
		    </h2>
	    </div>
        <div style="float:right;font-weight:500px" >	    
            <p><?php _e( 'Like our plugin? Please consider', 'news-announcement-scroll' ); ?><a href="<?php echo NAS_DONATE_URL; ?>"><?php _e( '&nbsp;contributing to us.', 'news-announcement-scroll' ); ?></a></p>
        </div>
    </div>        
    <div class="tool-box">
	    <?php
		    $sSql = "SELECT * FROM ".WP_G_NEWS_ANNOUNCEMENT." order by gNews_type, gNews_order";
		    $myData = array();
		    $myData = $wpdb->get_results( $sSql, ARRAY_A );
		?>
		<form name="frm_gNews_display" method="post">
            <table width="100%" class="widefat" id="straymanage">
                <thead>
		            <tr>
			            <th class="check-column" scope="row" style="padding: 8px 2px;"><input type="checkbox" name="gNews_group_item[]" /></th>
						<th scope="col" style="width:30%"><?php _e( 'News', 'news-announcement-scroll' ); ?></th>
						<th scope="col"><?php _e( 'Order', 'news-announcement-scroll' ); ?></th>
			            <th scope="col"><?php _e( 'Display', 'news-announcement-scroll' ); ?></th>
			            <th scope="col" style="width:30%"><?php _e( 'Link', 'news-announcement-scroll' ); ?></th>
						<th scope="col"><?php _e( 'Group', 'news-announcement-scroll' ); ?></th>
						<th scope="col"><?php _e( 'Publish', 'news-announcement-scroll' ); ?></th>
						<th scope="col"><?php _e( 'Expiration', 'news-announcement-scroll' ); ?></th>
		            </tr>
                </thead>
		        <tfoot>
                    <tr>
			            <th class="check-column" scope="row" style="padding: 8px 2px;"><input type="checkbox" name="gNews_group_item[]" /></th>
						<th scope="col"><?php _e( 'News', 'news-announcement-scroll' ); ?></th>
						<th scope="col"><?php _e( 'Order', 'news-announcement-scroll' ); ?></th>
			            <th scope="col"><?php _e( 'Status', 'news-announcement-scroll' ); ?></th>
			            <th scope="col"><?php _e( 'Link', 'news-announcement-scroll' ); ?></th>
						<th scope="col"><?php _e( 'Group', 'news-announcement-scroll' ); ?></th>
						<th scope="col"><?php _e( 'Publish', 'news-announcement-scroll' ); ?></th>
						<th scope="col"><?php _e( 'Expiration', 'news-announcement-scroll' ); ?></th>
                    </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if ( count( $myData ) > 0 ) {
				foreach ($myData as $data) {
					?>
					<tr class="<?php if ( $i&1 ) { echo 'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['gNews_id']; ?>" name="gNews_group_item[]"></th>
						<td>
						<?php echo esc_html( stripslashes($data['gNews_text'] ) ); ?>
						<div class="row-actions">
						<span class="edit">
						<a title="Edit" href="<?php echo WP_G_NEWS_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['gNews_id']; ?>"><?php _e( 'Edit', 'news-announcement-scroll' ); ?></a> | </span>
						<span class="trash">
						<a onClick="javascript:gNews_delete('<?php echo $data['gNews_id']; ?>')" href="javascript:void(0);"><?php _e( 'Delete', 'news-announcement-scroll' ); ?></a></span> 
						</div>
						</td>
						<td><?php echo esc_html( stripslashes( $data['gNews_order'] ) ); ?></td>
						<td><?php echo esc_html( stripslashes( $data['gNews_status'] ) ); ?></td>
						<td><?php echo esc_html( stripslashes( chunk_split( $data['gnews_redirect_link'],20 ) ) ); ?></td>
						<td><?php echo esc_html( stripslashes( $data['gNews_type'] ) ); ?></td>
						<td><?php echo substr( $data['gNews_date'], 0, 10 ); ?></td>
						<td><?php echo substr( $data['gNews_expiration'], 0, 10 ); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				}
			} else {
				?><tr><td colspan="7" align="center"><?php _e( 'No records available.', 'news-announcement-scroll' ); ?></td></tr><?php
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field( 'gNews_form_show' ); ?>
		<input type="hidden" name="frm_gNews_display" value="yes"/>
      </form>	
	  <p style="font-size:14px"><?php _e('<b><i>Note</i></b> : Shortcode for version 8.8 onwards is [news-announcement group="sample"]', 'news-announcement-scroll'); ?></p>
	
	  <p class="description">
		<?php echo NAS_OFFICIAL; ?>
	  </p>
	</div>
</div>