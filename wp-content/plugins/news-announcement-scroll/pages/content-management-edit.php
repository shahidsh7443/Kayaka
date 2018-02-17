<?php 
    if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'] ) ) { 
	    die( 'You are not allowed to call this page directly.' ); 
	} 
?>
<div class="wrap">
	<?php
	$did = isset( $_GET['did'] ) ? $_GET['did'] : '0';
	if ( ! is_numeric( $did ) ) { 
		die( '<p>Are you sure you want to do this?</p>' ); 
	}

	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_G_NEWS_ANNOUNCEMENT."
		WHERE `gNews_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);

	if ( $result != '1' ) {
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'news-announcement-scroll'); ?></strong></p></div><?php
	} else {
		$gNews_errors = array();
		$gNews_success = '';
		$gNews_error_found = FALSE;
		
		$sSql = $wpdb->prepare("
			SELECT *
			FROM `".WP_G_NEWS_ANNOUNCEMENT."`
			WHERE `gNews_id` = %d
			LIMIT 1
			",
			array($did)
		);
		$data = array();
		$data = $wpdb->get_row($sSql, ARRAY_A);
		
		// Preset the form fields
		$form = array(
			'gNews_text' => $data['gNews_text'],
			'gNews_order' => $data['gNews_order'],
			'gNews_status' => $data['gNews_status'],
			'gnews_redirect_link' => $data['gnews_redirect_link'],
			'gNews_expiration' => $data['gNews_expiration'],
			'gNews_date' => $data['gNews_date'],
			'gNews_type' => $data['gNews_type']
		);
	}
	// Form submitted, check the data
	if (isset($_POST['gNews_form_submit']) && $_POST['gNews_form_submit'] == 'yes') {
		//	Just security thingy that wordpress offers us
		check_admin_referer('gNews_form_edit');
		
		$form['gNews_text'] = isset($_POST['gNews_text']) ? $_POST['gNews_text'] : '';
		if ($form['gNews_text'] == '') {
			$gNews_errors[] = __('Enter announcement text', 'news-announcement-scroll');
			$gNews_error_found = TRUE;
		}
		
		$form['gNews_order'] = isset($_POST['gNews_order']) ? $_POST['gNews_order'] : '';
		if ($form['gNews_order'] == '') {
			$gNews_errors[] = __('Enter display order', 'news-announcement-scroll');
			$gNews_error_found = TRUE;
		}

		$form['gNews_expiration'] = isset($_POST['gNews_expiration']) ? $_POST['gNews_expiration'] : '';
		$form['gNews_status'] = isset($_POST['gNews_status']) ? $_POST['gNews_status'] : '';
		$form['gnews_redirect_link'] = isset($_POST['gnews_redirect_link']) ? $_POST['gnews_redirect_link'] : '';
        $form['gNews_type'] = isset($_POST['gNews_type']) ? $_POST['gNews_type'] : '';
		$form['gNews_date'] = isset($_POST['gNews_type']) ? $_POST['gNews_date'] : '';

		//	No errors found, we can add this Group to the table
		if ($gNews_error_found == FALSE) {	
			$sSql = $wpdb->prepare(
					"UPDATE `".WP_G_NEWS_ANNOUNCEMENT."`
					 SET `gNews_text` = %s,
					`gNews_order` = %s,
					`gNews_status` = %s,
					`gnews_redirect_link` = %s,
					`gNews_date` = %s,
					`gNews_expiration` = %s,
					`gNews_type` = %s
					WHERE gNews_id = %d
					LIMIT 1",
					array($form['gNews_text'], $form['gNews_order'], $form['gNews_status'], $form['gnews_redirect_link'], $form['gNews_date'], $form['gNews_expiration'], $form['gNews_type'], $did)
				);
			$wpdb->query($sSql);	
			$gNews_success = __('Details was successfully updated.', 'news-announcement-scroll');
		}
	}

	if ($gNews_error_found == TRUE && isset($gNews_errors[0]) == TRUE) {
		?>
		<div class="error fade">
			<p><strong><?php echo $gNews_errors[0]; ?></strong></p>
		</div>
		<?php
	}
	if ($gNews_error_found == FALSE && strlen($gNews_success) > 0) {
		?>
		<div class="updated fade">
			<p><strong><?php echo $gNews_success; ?> 
			<a href="<?php echo WP_G_NEWS_ADMIN_URL; ?>"><?php _e('Click here', 'news-announcement-scroll'); ?></a> <?php _e('to view the details', 'news-announcement-scroll'); ?></strong></p>
		</div>
		<?php
	}
	?>
	<div class="form-wrap">
		<h2>
		    <?php _e('Update News Details', 'news-announcement-scroll'); ?>
		    <a class="add-new-h2" href="<?php echo WP_G_NEWS_ADMIN_URL; ?>&amp;ac=add"><?php _e( 'Add New', 'news-announcement-scroll' ); ?></a>
            <a class="add-new-h2" href="<?php echo WP_G_NEWS_ADMIN_URL; ?>&amp;ac=set"><?php _e( 'Widget Settings', 'news-announcement-scroll' ); ?></a>
	        <a class="add-new-h2" target="_blank" href="<?php echo WP_G_NEWS_HELP; ?>"><?php _e( 'Help', 'news-announcement-scroll' ); ?></a>
		</h2>
		 		
		<form name="gNews_form" method="post" action="#" onsubmit="return gNews_submit()"  >
			<label for="tag-image"><?php _e('Enter announcement text', 'news-announcement-scroll'); ?></label>
		    <textarea name="gNews_text" cols="115" rows="6" id="gNews_text"><?php echo esc_html(stripslashes($form['gNews_text'])); ?></textarea>
		    <p><?php _e('Enter your news and announcement text', 'news-announcement-scroll'); ?></p>
			  
			<label for="tag-link"><?php _e('Enter display order', 'news-announcement-scroll'); ?></label>
			<input name="gNews_order" type="text" id="gNews_order" value="<?php echo $form['gNews_order']; ?>" maxlength="2" />
			<p><?php _e('What order should the news be played in. should it come 1st, 2nd, 3rd, etc.', 'news-announcement-scroll'); ?></p>

			<label for="tag-display-status"><?php _e('Display status', 'news-announcement-scroll'); ?></label>
			<select name="gNews_status" id="gNews_status">
				<option value='YES' <?php if($form['gNews_status']=='YES') { echo 'selected="selected"' ; } ?>>Yes</option>
				<option value='NO' <?php if($form['gNews_status']=='NO') { echo 'selected="selected"' ; } ?>>No</option>
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
				$selected = "";
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
				    if(strtoupper($form['gNews_type']) == strtoupper($arrDistinct["gNews_type"]) ) { 
				        $selected = "selected='selected'"; 
				    }
				    ?>
				    <option value='<?php echo $arrDistinct["gNews_type"]; ?>' <?php echo $selected; ?>><?php echo strtoupper($arrDistinct["gNews_type"]); ?></option>
				    <?php $selected = "";
				}
				?>
			</select>
			<p><?php _e('This is to group the news. Select your news group.', 'news-announcement-scroll'); ?></p> 

			<label for="tag-display-order"><?php _e('Publish', 'news-announcement-scroll'); ?></label>
			<input name="gNews_date" type="text" id="gNews_date" value="<?php echo substr($form['gNews_date'],0,10); ?>" maxlength="10" />
			<p><?php _e('Please enter the news publish date in this format YYYY-MM-DD.', 'news-announcement-scroll'); ?></p>       

			<label for="tag-display-order"><?php _e('Expiration date', 'news-announcement-scroll'); ?></label>
			<input name="gNews_expiration" type="text" id="gNews_expiration" value="<?php echo substr($form['gNews_expiration'],0,10); ?>" maxlength="10" />
			<p><?php _e('Please enter the expiration date in this format YYYY-MM-DD', 'news-announcement-scroll'); ?></p>

			<input name="gNews_id" id="gNews_id" type="hidden" value="">
			<input type="hidden" name="gNews_form_submit" value="yes"/>
			<p class="submit">
			<input name="publish" lang="publish" class="button-primary" value="<?php _e('Update', 'news-announcement-scroll'); ?>" type="submit" />
			<input name="publish" lang="publish" class="button-primary" onclick="gNews_redirect()" value="<?php _e('Cancel', 'news-announcement-scroll'); ?>" type="button" />
			</p>
			<?php wp_nonce_field('gNews_form_edit'); ?>
	    </form>
	</div>
	<p class="description">
		<?php echo NAS_OFFICIAL; ?>
	</p>
</div>