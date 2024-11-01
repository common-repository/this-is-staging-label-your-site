<?php
	$tis_options 	  = get_option('tis-options');
	$tis_admin_enable = $tis_options['tis-admin-enable'];
	$tis_front_enable = $tis_options['tis-front-enable'];
	$tis_text_color	  = $tis_options['tis-text-color'];
	$tis_box_color 	  = $tis_options['tis-box-color'];
	$tis_label_text	  = $tis_options['tis-text'];
	$tis_position 	  = $tis_options['tis-position'];
	$tis_spartan 	  = $tis_options['tis-spartan'];
	$tis_visibility   = $tis_options['tis-visibility'];
	$tis_users_list   = $tis_options['tis-userslist'];
?>

<div class="tis-container">
	
	<div class="tis-admin-options">
		<h1><?php _e('Label my WordPress web site','tis') ?></h1>
		<div class="succes_log"></div>
		<div class="overall-error"></div>
		<caption><?php _e('This plugin provides the ability to add a label in the admin bar or labeled box on the font end with text by your choice. The idea of the plugin is to give every developer an easy way to identify on current site without checking the url all the time.','tis') ?></caption>
		<p>	
			<label>
				<strong><?php _e('Give name to your label:','tis') ?></strong>
			</label>
			</br>
			<input type="text" id="tis-label-name" name="tis-label-name" value="<?php echo $tis_label_text ?>">
		</p>

		<p>
			<label for="tis-admin-bar">
				<strong><?php _e('Show label in admin bar?','tis') ?></strong>
			</label>
			</br>
			<input type="radio" name="tis-adminbar" value='1' <?php if($tis_admin_enable==1){echo "checked";} ?>>
			<?php _e('Yes, please! Put some red stuff with the label!','tis'); ?></br>
			<input type="radio" name="tis-adminbar" value='0' <?php if($tis_admin_enable!=1){echo "checked";} ?>>
			<?php _e('No, no need to identify the admin panel! Thank you.','tis');  ?></br>
		</p>
		<p>
			<label for="tis-visibility">
				<strong><?php _e('You want to make the labels visible only for some users? </br>This make sense. The client might not like to see the Spartan on the fron page, so set your preferences below.','tis') ?></strong>
			</label>
			</br>
			<input type="radio" name="tis-visibility" value='1' <?php if($tis_visibility==1){echo "checked";} ?>>
			<?php _e('I am the only developer, so I am the ONLY ONE to see the label!','tis'); ?></br>
			<input type="radio" name="tis-visibility" value='2' <?php if($tis_visibility==2){echo "checked";} ?>>
			<?php _e('Ammm, "yell" at all administrators of the site that THIS IS STAGING!','tis'); ?></br>
			<input type="radio" name="tis-visibility" value='3' <?php if($tis_visibility==3){echo "checked";} ?>>
			<?php _e('Ok, make the label visible to a list of user ids(comma separated, no white spaces).','tis'); ?>
			<input type="text" style="width:250px; <?php if($tis_visibility!=3) {echo 'display:none';}?>" name="tis-list-of-users" id="tis-list-of-users" placeholder="List the ids ." value="<?php echo $tis_users_list; ?>">
		</p>
	</div>
	<div class="tis-front-end-option">
		<p>
			<label for="tis-front-end">
				<strong><?php _e('Show identification on the front end with fixed label on each page?','tis') ?></strong>
			</label>
			</br>
			<input type="radio" name="tis-front-end" value='1' <?php if($tis_front_enable==1){echo 'checked';} ?>>
			<?php _e('Yes, dude, it will be very useful!','tis');  ?></br>
			<input type="radio" name="tis-front-end" value='0' <?php if($tis_front_enable!=1){echo 'checked';}  ?>>
			<?php _e('No! I have the admin bar identification. That\'s enough, thank you!','tis');?></br>
		</p>
		<div class="tis-front-end-advanced-option" <?php if($tis_front_enable!=1){echo 'style="display:none"';} ?>>
			<p>
				<label for="tis-front-position">
					<strong><?php _e('Where do you like the label box to be positioned?','tis') ?></strong>
				</label>
				</br>
				<input type="radio" name="tis-front-position" value='top-letf'	<?php if($tis_position=="top-left"){echo 'checked';} ?>>
				<?php _e('Top left','tis') ?></br>
				<input type="radio" name="tis-front-position" value='top-right'	<?php if($tis_position=="top-right"){echo 'checked';} ?>>
				<?php _e('Top Right','tis') ?></br>
				<input type="radio" name="tis-front-position" value='bot-left'	<?php if($tis_position=="bot-left"){echo 'checked';} ?>>
				<?php _e('Bottom left','tis') ?></br>
				<input type="radio" name="tis-front-position" value='bot-right'	<?php if($tis_position=="bot-right"){echo 'checked';} ?>>
				<?php _e('Bottom right','tis') ?></br>
			</p>
			<p>
				<label for="tis-admin-bar">
					<strong><?php _e('Ok, be serious! Remove the King Leonidas\'s face and put an ordinary box with label.','tis') ?></strong>
				</label>
				</br>
				<input type="radio" name="tis-spartan" value='1'<?php if($tis_spartan==1){echo 'checked';} ?>>
				<?php _e('Nooooo [drama cry]! Keep the Spartan!','tis') ?></br>
				<input type="radio" name="tis-spartan" value='0' <?php if($tis_spartan!=1){echo 'checked';} ?>>
				<?php _e('Nah! Just an ordinary labeled box, please!','tis') ?></br>
			</p>
			<p>	
				<labe>
					<strong><?php _e('Pick up a color for your label box (front-end only):','tis') ?></strong>
				</label>
				</br>
				<input type="text" id="tis-label-box-color"  value="<?php echo $tis_box_color ?>">
			</p>
			<p>	
				<label>
					<strong><?php _e('Pick up a color for your label text (front-end only):','tis') ?></strong>
				</label>
				</br>
				<input type="text" id="tis-label-text-color" value="<?php echo $tis_text_color ?>">
			</p>
		</div>
		<p>
			<input type="submit" id="tis-save-settings" value="<?php _e('Save settings!','tis') ?>">
		</p>
	</div>
</div>