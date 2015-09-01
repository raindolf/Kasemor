<form class="property-search-form" action="<?php if ( tt_page_id_template_search() ) { echo get_permalink( tt_page_id_template_search() ); } ?>">
	<?php 
	if ( !tt_page_id_template_search() ) {
		echo '<p class="alert alert-info">' . __( 'Please create a page that is using page template "Property Search".', 'tt' ) . '</p>';			
	}
	?>
	<div class="row">
	
		<?php		
		global $realty_theme_option;
				
		$acf_field_array = array();
		
		if ( isset($realty_theme_option['property-search-features']) && !empty($realty_theme_option['property-search-features']) ) {
	    $property_search_features = $realty_theme_option['property-search-features'];
    }
    else {
	    $property_search_features = '';
    }

		$search_parameters = $realty_theme_option['property-search-mini-parameter'];
		$search_fields = $realty_theme_option['property-search-mini-field'];		
		$search_labels = $realty_theme_option['property-search-mini-label'];
		
		$default_search_fields_array = array( 
			'estate_search_by_keyword',
			'estate_property_id', 
			'estate_property_location', 
			'estate_property_type', 
			'estate_property_status', 
			'estate_property_price', 
			'estate_property_pricerange',
			'estate_property_size',
			'estate_property_rooms',
			'estate_property_bedrooms',
			'estate_property_bathrooms',
			'estate_property_garages',
			'estate_property_available_from'
		);
			
		$i = 0;
		
		$count_search_fields = count($search_fields);
		
		if ( $count_search_fields == 1 ) {
			$columns = "col-xs-12";
		}
		else if ( $count_search_fields == 2 ) {
			$columns = "col-xs-12 col-sm-6 col-md-4";
		}
		else {
			$columns = "col-xs-12 col-sm-4 col-md-3";
		}
		
		// Do we have any search parameters defined?
		if ( isset( $search_parameters ) && !empty( $search_parameters[0] ) ) {
				
			foreach ( $search_fields as $search_field ) {
				
				$search_parameter = $search_parameters[$i];
				
				// Check If Search Field Is Filled Out
				if ( !empty( $search_field ) ) {
				
					// Default Property Field
					if ( in_array( $search_field, $default_search_fields_array ) ) {
											
						switch ( $search_field ) {
							
							case 'estate_search_by_keyword' : 
							case 'estate_property_id' : 
							?>
							<div class="<?php echo $columns; ?> form-group">
								<input type="text" name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" value="<?php echo isset( $_GET[$search_parameter])?$_GET[$search_parameter]:''; ?>" placeholder="<?php echo __( $search_labels[$i], 'tt' ); ?>" class="form-control" />
							</div>
							<?php
							break;
							
							case 'estate_property_location' : ?>
							<div class="<?php echo $columns; ?> form-group select">	
								<?php // http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list#answer-14658 ?>
								<select name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" class="form-control">
									<option value="all"><?php _e( 'Any Location', 'tt' ); ?></option>
							    <?php 
							    $location = get_terms('property-location', array( 'orderby' => 'slug', 'parent' => 0, 'hide_empty' => false) ); 
							    if ( isset( $_GET[$search_parameter] ) ) {
										$get_location = $_GET[$search_parameter];
									}
									else {
										$get_location = '';
									}
									?>
							    <?php foreach ( $location as $key => $location ) : ?>
					        <option value="<?php echo $location->slug; ?>" <?php selected( $location->slug, $get_location ); ?>>
				            <?php 
				            echo $location->name;
				            $location2 = get_terms( 'property-location', array( 'orderby' => 'slug', 'parent' => $location->term_id ) );
				            if( $location2 ) : 
				            ?>
				            <optgroup>
				              <?php foreach( $location2 as $key => $location2 ) : ?>
				                  <option value="<?php echo $location2->slug; ?>" class="level2" <?php selected( $location2->slug, $get_location ); ?>>
				                  	<?php 
				                  	echo $location2->name;
				                  	$location3 = get_terms( 'property-location', array( 'orderby' => 'slug', 'parent' => $location2->term_id ) );
				                  	if( $location3 ) : ?>
				                  	<optgroup>
				                  		<?php foreach( $location3 as $key => $location3 ) : ?>
				                    		<option value="<?php echo $location3->slug; ?>" class="level3" <?php selected( $location3->slug, $get_location ); ?>>
				                    		<?php 
				                    		echo $location3->name;
					                    	$location4 = get_terms( 'property-location', array( 'orderby' => 'slug', 'parent' => $location3->term_id ) );
					                    	if( $location4 ) :
				                    		?>
				                    		<optgroup>
				                    			<?php foreach( $location4 as $key => $location4 ) : ?>
				                    			<option value="<?php echo $location4->slug; ?>" class="level4" <?php selected( $location4->slug, $get_location ); ?>>
																	<?php echo $location4->name; ?>
				                    			</option>
				                    			<?php endforeach; ?>
				                    		</optgroup>
				                    		<?php endif; ?>
				                    		</option>
				                  		<?php endforeach; ?>
				                  	</optgroup>
				                  	<?php endif; ?>
				                  </option>
				              <?php endforeach; ?>
				            </optgroup>
				            <?php endif; ?>
					        </option>
							    <?php endforeach; ?>
								</select>
							</div>
							<?php
							break;
							
							case 'estate_property_status' : ?>
							<div class="<?php echo $columns; ?> form-group select">	
								<?php // http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list#answer-14658 ?>
								<select name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" class="form-control">
									<option value="all"><?php _e( 'Any Status', 'tt' ); ?></option>
							    <?php 
							    $status = get_terms('property-status', array( 'orderby' => 'slug', 'parent' => 0 ) ); 
							    if ( isset( $_GET[$search_parameter] ) ) {
										$get_status = $_GET[$search_parameter];
									}
									else {
										$get_status = '';
									}
									?>
							    <?php foreach ( $status as $key => $status ) : ?>
					        <option value="<?php echo $status->slug; ?>" <?php selected( $status->slug, $get_status ); ?>>
				            <?php 
				            echo $status->name;
				            $status2 = get_terms( 'property-status', array( 'orderby' => 'slug', 'parent' => $status->term_id ) );
				            if( $status2 ) : 
				            ?>
				            <optgroup>
				              <?php foreach( $status2 as $key => $status2 ) : ?>
				                  <option value="<?php echo $status2->slug; ?>" class="level2" <?php selected( $status2->slug, $get_status ); ?>>
				                  	<?php 
				                  	echo $status2->name;
				                  	$status3 = get_terms( 'property-status', array( 'orderby' => 'slug', 'parent' => $status2->term_id ) );
				                  	if( $status3 ) : ?>
				                  	<optgroup>
				                  		<?php foreach( $status3 as $key => $status3 ) : ?>
				                    		<option value="<?php echo $status3->slug; ?>" class="level3" <?php selected( $status3->slug, $get_status ); ?>>
				                    		<?php 
				                    		echo $status3->name;
					                    	$status4 = get_terms( 'property-status', array( 'orderby' => 'slug', 'parent' => $status3->term_id ) );
					                    	if( $status4 ) :
				                    		?>
				                    		<optgroup>
				                    			<?php foreach( $status4 as $key => $status4 ) : ?>
				                    			<option value="<?php echo $status4->slug; ?>" class="level4" <?php selected( $status4->slug, $get_status ); ?>>
																	<?php echo $status4->name; ?>
				                    			</option>
				                    			<?php endforeach; ?>
				                    		</optgroup>
				                    		<?php endif; ?>
				                    		</option>
				                  		<?php endforeach; ?>
				                  	</optgroup>
				                  	<?php endif; ?>
				                  </option>
				              <?php endforeach; ?>
				            </optgroup>
				            <?php endif; ?>
					        </option>
							    <?php endforeach; ?>
								</select>
							</div>
							<?php
							break;
							
							case 'estate_property_type' : ?>
							<div class="<?php echo $columns; ?> form-group select">	
								<?php // http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list#answer-14658 ?>
								<select name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" class="form-control">
									<option value="all"><?php _e( 'Any Type', 'tt' ); ?></option>
							    <?php 
							    $type = get_terms('property-type', array( 'orderby' => 'slug', 'parent' => 0 ) ); 
							    if ( isset( $_GET[$search_parameter] ) ) {
										$get_type = $_GET[$search_parameter];
									}
									else {
										$get_type = '';
									}
									?>
							    <?php foreach ( $type as $key => $type ) : ?>
					        <option value="<?php echo $type->slug; ?>" <?php selected( $type->slug, $get_type ); ?>>
				            <?php 
				            echo $type->name;
				            $type2 = get_terms( 'property-type', array( 'orderby' => 'slug', 'parent' => $type->term_id ) );
				            if( $type2 ) : 
				            ?>
				            <optgroup>
				              <?php foreach( $type2 as $key => $type2 ) : ?>
				                  <option value="<?php echo $type2->slug; ?>" class="level2" <?php selected( $type2->slug, $get_type ); ?>>
				                  	<?php 
				                  	echo $type2->name;
				                  	$type3 = get_terms( 'property-type', array( 'orderby' => 'slug', 'parent' => $type2->term_id ) );
				                  	if( $type3 ) : ?>
				                  	<optgroup>
				                  		<?php foreach( $type3 as $key => $type3 ) : ?>
				                    		<option value="<?php echo $type3->slug; ?>" class="level3" <?php selected( $type3->slug, $get_type ); ?>>
				                    		<?php 
				                    		echo $type3->name;
					                    	$type4 = get_terms( 'property-type', array( 'orderby' => 'slug', 'parent' => $type3->term_id ) );
					                    	if( $type4 ) :
				                    		?>
				                    		<optgroup>
				                    			<?php foreach( $type4 as $key => $type4 ) : ?>
				                    			<option value="<?php echo $type4->slug; ?>" class="level4" <?php selected( $type4->slug, $get_type ); ?>>
																	<?php echo $type4->name; ?>
				                    			</option>
				                    			<?php endforeach; ?>
				                    		</optgroup>
				                    		<?php endif; ?>
				                    		</option>
				                  		<?php endforeach; ?>
				                  	</optgroup>
				                  	<?php endif; ?>
				                  </option>
				              <?php endforeach; ?>
				            </optgroup>
				            <?php endif; ?>
					        </option>
							    <?php endforeach; ?>
								</select>
							</div>
							<?php
							break;
							
							case 'estate_property_price' : 
							case 'estate_property_size' : 
							case 'estate_property_rooms' : 
							case 'estate_property_bedrooms' : 
							case 'estate_property_bathrooms' : 
							case 'estate_property_garages' : 
							?>
							<div class="<?php echo $columns; ?> form-group">
								<input type="number" name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" value="<?php echo isset( $_GET[$search_parameter])?$_GET[$search_parameter]:''; ?>" placeholder="<?php echo __( $search_labels[$i], 'tt' ); ?>" min="0" class="form-control" />
							</div>
							<?php
							break;
							
							case 'estate_property_available_from' : 
							?>
							<div class="<?php echo $columns; ?> form-group">
								<input type="number" name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" value="<?php echo isset( $_GET[$search_parameter])?$_GET[$search_parameter]:''; ?>" placeholder="<?php echo __( $search_labels[$i], 'tt' ); ?>" min="0" class="form-control datepicker" />
							</div>
							<?php
							break;
							
							case 'estate_property_pricerange' : 
							$pricerange_min = $realty_theme_option['property-search-price-range-min'];
							$pricerange_max = $realty_theme_option['property-search-price-range-max'];
							?>
							<div class="<?php echo $columns; ?> form-group price-range">
								<input type="number" name="price_range_min" class="property-search-price-range-min hide" value="<?php if ( isset( $_GET['price_range_min'] ) ) { echo $_GET['price_range_min']; } else { echo $pricerange_min; } ?>" />
								<input type="number" name="price_range_max" class="property-search-price-range-max hide" value="<?php if ( isset( $_GET['price_range_max'] ) ) { echo $_GET['price_range_max']; } else { echo $pricerange_max; } ?>" />
								<label><?php echo __( $search_labels[$i], 'tt' ); ?> <span class="price-range-min"></span> <?php _e( 'to', 'tt' ); ?> <span class="price-range-max"></span></label>
								<div class="price-range-slider"></div>
							</div>
							<?php
							break;
							
						}
						
					}
					
					// ACF: Custom Property Field
					else if ( tt_acf_active() ) {
				
					// Get ACF Field Type
					$acf_field_position = array_search( $search_field, tt_acf_fields_name( tt_acf_group_id_property() ) );
					$acf_field_type_key = tt_acf_fields_type( tt_acf_group_id_property() );
					$acf_field_type = $acf_field_type_key[$acf_field_position];
					
					// Single value based ACF fields, that appear next to default fields. Arrays such as checkboxes & radio buttons are shown under "more".
					// $acf_supported_field_types = array( 'text', 'number', 'email', 'date_picker', 'select' );
					
					//if ( in_array( $acf_field_type, $acf_supported_field_types ) ) {
						echo '<div class="' . $columns . ' form-group">';
					//}
					
						// Field Type: Select, Checkbox
						if ( $acf_field_type == 'select' || $acf_field_type == 'checkbox' || $acf_field_type == 'radio' ) {
							
							$acf_custom_keys = get_post_custom_keys( tt_acf_group_id_property() );
							
							$acf_object = get_field_object($search_field);
							
							// ACF: Loop through field keys, as we can't output choices by name, but only by their key
							foreach ( $acf_custom_keys as $key => $value ) {
								
						    if ( stristr( $value, 'field_' ) ) {
						      $acf_field = get_field_object( $value, tt_acf_group_id_property() );
						      if ( $acf_field['name'] == $search_field ) {
										
										// Select
										if ( $acf_field_type == 'select' ) {
										
										echo '<select name="' . $acf_field['name'] . '" data-placeholder="' . __( $search_labels[$i], 'tt' ) . '">';
										
											echo '<option value=""></option>';
											foreach( $acf_field['choices'] as $key => $value ) {
												// Default value
												if ( $acf_object['value'] == $key ) {
													$selected = "selected";
												}
												else {
													$selected = "";
												}
												echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
											}
											
										echo '</select>';
										
										}				
										
										// Checkbox, Radio
										if ( $acf_field_type == 'checkbox' || $acf_field_type == 'radio' ) {
											
											$output  = '<h6>' . __( $acf_field['name'], 'tt' ) . '</h6>';
											$output .= '<div class="row">';
											
											foreach( $acf_field['choices'] as $key => $value ) {
												
												// Default value
												if ( $acf_object['value'] == $key ) {
													$checked = "checked";
												}
												else {
													$checked = "";
												}
												
												if ( !empty( $_GET[ $search_parameter ] ) ) {
													if ( $_GET[ $search_parameter ] == $key ) {
														$checked = "checked";
													}
												}
												
												// Output under "more"
												$output .= '<div class="' . $columns . ' form-group">';
												$output .= '<input type="' . $acf_field_type . '" name="' . $search_field . '" id="' . $search_field . '-' . $key . '" value="' . $key . '" ' . $checked . ' />';
												$output .= '<label for="' . $search_field . '-' . $key . '">' .  __( $value, 'tt' ) . '</label>';
												$output .= '</div>';
											
											}
											
											$output .= '</div>';
												
											$acf_field_array[] = $output;
											
										}
							
									}
						    }
							}
						
						}
						
						// Text, Number, Email, Date Picker
						else if ( $acf_field_type == 'text' || $acf_field_type == 'number' || $acf_field_type == 'email' || $acf_field_type == 'date_picker' ) {
							
							$datepicker_class = '';
							
							switch ( $acf_field_type ) {
								case 'text' : $acf_field_type_output = 'text'; break;
								case 'number' : $acf_field_type_output = 'number'; break;
								case 'email' : $acf_field_type_output = 'email'; break;
								case 'date_picker' : $acf_field_type_output = 'number'; $datepicker_class = 'datepicker'; break;
							}
						
							if ( $acf_field_type == 'date_picker' ) {
								echo '<div class="input-group">';
							}
							
							$value = '';
							
							if ( isset( $_GET[ $search_parameter ] ) ) {
								$value = $_GET[ $search_parameter ];
							}
							
							echo '<input type="' . $acf_field_type_output . '" name="' . $search_parameter . '" value="' . $value . '" placeholder="' . __( $search_labels[$i], 'tt' ) . '" class="form-control ' . $datepicker_class . '" />';
							
							if ( $acf_field_type == 'date_picker' ) {
								echo '<span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>';
								echo '</div>';
							}
						
						}
					
					//if ( in_array( $acf_field_type, $acf_supported_field_types ) ) {
						echo '</div>'; // .col-xx-x
					//}
					
					wp_reset_postdata();
					
				}
				
					}
				
				$i++;
			
			} // foreach()
		
		} // END if()
		
		else {
			echo '<div class="alert alert-info">' . __( 'Please setup your property search mini in your theme options panel.', 'tt' ) . '</div>';
		}

		?>	
		
		<div class="<?php echo $columns; ?> form-group">
				<input type="submit" value="<?php _e( 'Search', 'tt' ); ?>" class="btn btn-primary btn-block form-control" />
		</div>
	
	</div>
	
	<!-- Default Order: Newest Properties First -->
	<input type="hidden" name="orderby" value="date-new" />
	<input type="hidden" name="pageid" value="<?php echo $post->ID; ?>" />
	<input type="hidden" name="form" value="mini" />
	
</form>