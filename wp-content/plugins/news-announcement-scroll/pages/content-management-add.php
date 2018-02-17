<?php 

if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) { 
	die( 'You are not allowed to call this page directly.' ); 
} 

?>
<div class="wrap"> 
	<?php
	$gNews_errors = array();
	$gNews_success = '';
	$gNews_error_found = FALSE;

    // Preset the form fields
	$form = array(
		'gNews_text' => '',
		'gNews_order' => '',
		'gNews_status' => '',
		'gnews_redirect_link' => '',
		'gNews_expiration' => '',
		'gNews_date' => '',
		'gNews_type' => ''
		);

// Form submitted, check the data
	if ( isset( $_POST['gNews_form_submit'] ) && $_POST['gNews_form_submit'] == 'yes' ) {
	    //	Just security thingy that wordpress offers us
		check_admin_referer( 'gNews_form_add' );
		
		$form['gNews_text'] = isset( $_POST['gNews_text'] ) ? $_POST['gNews_text'] : '';
		if ( $form['gNews_text'] == '' ) {
			$gNews_errors[] = __( 'Enter announcement text', 'news-announcement-scroll' );
			$gNews_error_found = TRUE;
		}
		
		$form['gNews_order'] = isset( $_POST['gNews_order'] ) ? $_POST['gNews_order'] : '';
		if ( $form['gNews_order'] == '' ) {
			$gNews_errors[] = __( 'Enter display order', 'news-announcement-scroll' );
			$gNews_error_found = TRUE;
		}

		$form['gNews_expiration'] = isset( $_POST['gNews_expiration'] ) ? $_POST['gNews_expiration'] : '';
		$form['gNews_status'] = isset( $_POST['gNews_status'] ) ? $_POST['gNews_status'] : '';
		$form['gnews_redirect_link'] = isset($_POST['gnews_redirect_link']) ? $_POST['gnews_redirect_link'] : '';
		$form['gNews_type'] = isset( $_POST['gNews_type'] ) ? $_POST['gNews_type'] : '';
		$form['gNews_date'] = isset( $_POST['gNews_type'] ) ? $_POST['gNews_date'] : '';

	    //	No errors found, we can add this Group to the table
		if ( $gNews_error_found == FALSE ) {
			$cur_date = date('Y-m-d G:i:s'); 
			$sql = $wpdb->prepare("INSERT INTO `".WP_G_NEWS_ANNOUNCEMENT."`
				(`gNews_text`, `gNews_order`, `gNews_status`, `gnews_redirect_link`, `gNews_date`, `gNews_expiration`, `gNews_type`)
				VALUES(%s, %s, %s, %s, %s, %s, %s)",
				array($form['gNews_text'], $form['gNews_order'], $form['gNews_status'], $form['gnews_redirect_link'], $form['gNews_date'], $form['gNews_expiration'], $form['gNews_type'])
				);
			$wpdb->query($sql);
			
			$gNews_success = __('Details was successfully added.', 'news-announcement-scroll');
			
		// Reset the form fields
			$form = array(
				'gNews_text' => '',
				'gNews_order' => '',
				'gNews_status' => '',
				'gnews_redirect_link' => '',
				'gNews_expiration' => '',
				'gNews_date' => '',
				'gNews_type' => ''
				);
		}
	}

	if ( $gNews_error_found == TRUE && isset($gNews_errors[0]) == TRUE ) {
		?>
		<div class="error fade">
			<p><strong><?php echo $gNews_errors[0]; ?></strong></p>
		</div>
		<?php
	}
	
	if ( $gNews_error_found == FALSE && strlen($gNews_success) > 0 ) {
		?>
		<div class="updated fade">
			<p>
			    <strong><?php echo $gNews_success; ?> 
				    <a href="<?php echo WP_G_NEWS_ADMIN_URL; ?>"><?php _e('Click here', 'news-announcement-scroll'); ?></a> <?php _e('to view the details', 'news-announcement-scroll'); ?>
				</strong>
			</p>
		</div>
		<?php
	}
	?>
	<div class="form-wrap">
		<h2>
			<?php _e('Add News Details', 'news-announcement-scroll'); ?>
            <a class="add-new-h2" href="<?php echo WP_G_NEWS_ADMIN_URL; ?>&amp;ac=set"><?php _e( 'Widget Settings', 'news-announcement-scroll' ); ?></a>
	        <a class="add-new-h2" target="_blank" href="<?php echo WP_G_NEWS_HELP; ?>"><?php _e( 'Help', 'news-announcement-scroll' ); ?></a>
		</h2>
		
		<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
		<form name="gAnnouncefrm" method="post" action="#" onsubmit="return _gAnnounce()"  >
			
			<label for="tag-image"><?php _e('Enter announcement text', 'news-announcement-scroll'); ?></label>
			<textarea name="gNews_text" cols="115" rows="6" id="txt_news"></textarea>
			<p><?php _e('Enter your news and announcement text', 'news-announcement-scroll'); ?></p>
			
			<label for="tag-link"><?php _e('Enter display order', 'news-announcement-scroll'); ?></label>
			<input name="gNews_order" type="text" id="gNews_order" value="0" maxlength="2" />
			<p><?php _e('What order should the news be played in. should it come 1st, 2nd, 3rd, etc.', 'news-announcement-scroll'); ?></p>
			
			<label for="tag-display-status"><?php _e('Display status', 'news-announcement-scroll'); ?></label>
			<select name="gNews_status" id="gNews_status">
				<option value='YES'>Yes</option>
				<option value='NO'>No</option>
			</select>
			<p><?php _e('Do you want to show this news in front end?', 'news-announcement-scroll'); ?></p>

			<label for="tag-news-link"><?php _e('Redirect Link', 'news-announcement-scroll'); ?></label>
			<input name="gnews_redirect_link" type="text" id="gnews_redirect_link" style='width:25%' value="<?php echo $form['gnews_redirect_link']; ?>" />
            <p><?php _e('Enter the redirect link for the news.', 'news-announcement-scroll'); ?></p>

            <label for="tag-select-gallery-group"><?php _e('Select news group', 'news-announcement-scroll'); ?></label>
			<select name="gNews_type" id="gNews_type">
				<?php
					$sSql = "SELECT distinct(gNews_type) as gNews_type FROM `".WP_G_NEWS_ANNOUNCEMENT."` order by gNews_type";
					$myDistinctData = array();
					$arrDistinctDatas = array();
					$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
					$i = 0;
					
					foreach ($myDistinctData as $DistinctData) {
						$arrDistinctData[$i]["gNews_type"] = strtoupper($DistinctData['gNews_type']);
						$i = $i+1;
					}
					
					for($j=$i; $j<$i+5; $j++) {
					
						$arrDistinctData[$j]["gNews_type"] = "GROUP" . $j;
					}
					
					$arrDistinctData[$j+1]["gNews_type"] = "WIDGET";
					$arrDistinctData[$j+2]["gNews_type"] = "SAMPLE";
					$arrDistinctDatas = array_unique($arrDistinctData, SORT_REGULAR);
					
					foreach ($arrDistinctDatas as $arrDistinct) {
					
						?><option value='<?php echo $arrDistinct["gNews_type"]; ?>'><?php echo $arrDistinct["gNews_type"]; ?></option><?php
					}
				?>
			</select>
			<p><?php _e('This is to group the news. Select your news group.', 'news-announcement-scroll'); ?></p>        
			
			<label for="tag-display-order"><?php _e('Publish', 'news-announcement-scroll'); ?></label>
			<input name="gNews_date" type="text" id="gNews_date" value="2014-07-01" maxlength="10" />
			<p><?php _e('Please enter the news publish date in this format YYYY-MM-DD.', 'news-announcement-scroll'); ?></p>
			
			<label for="tag-display-order"><?php _e('Expiration date', 'news-announcement-scroll'); ?></label>
			<input name="gNews_expiration" type="text" id="gNews_expiration" value="9999-12-30" maxlength="10" />
			<p><?php _e('Please enter the expiration date in this format YYYY-MM-DD <br /> 9999-12-30 : Is equal to no expire.', 'news-announcement-scroll'); ?></p>
			
			<input name="gNews_id" id="gNews_id" type="hidden" value="">
			<input type="hidden" name="gNews_form_submit" value="yes"/>
			<p class="submit">
				<input name="publish" lang="publish" class="button-primary" value="<?php _e('Save', 'news-announcement-scroll'); ?>" type="submit" />
				<input name="publish" lang="publish" class="button-primary" onclick="gNews_redirect()" value="<?php _e('Cancel', 'news-announcement-scroll'); ?>" type="button" />
			</p>
			<?php wp_nonce_field('gNews_form_add'); ?>
		</form>
	</div>
	<p class="description">
		<?php echo NAS_OFFICIAL; ?>
	</p>
</div>