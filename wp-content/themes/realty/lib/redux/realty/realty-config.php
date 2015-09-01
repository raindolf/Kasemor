<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux_Framework_sample_config' ) ) {

        class Redux_Framework_sample_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
                $this->setHelpTabs();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                // If Redux is running as a plugin, this will remove the demo notice and links
                //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

                // Function to test the compiler hook and demo CSS output.
                // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
                //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);

                // Change the arguments after they've been declared, but before the panel is created
                //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

                // Change the default value of a field after it's been set, but before it's been useds
                //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

                // Dynamically add a section. Can be also used to modify sections/fields
                //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            /**
             * This is a test function that will let you see when the compiler hook occurs.
             * It only runs if a field    set with compiler=>true is changed.
             * */
            function compiler_action( $options, $css, $changed_values ) {
                echo '<h1>The compiler hook has run!</h1>';
                echo "<pre>";
                print_r( $changed_values ); // Values that have changed since the last save
                echo "</pre>";
                //print_r($options); //Option values
                //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

                /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
            }

            /**
             * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
             * Simply include this function in the child themes functions.php file.
             * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
             * so you must use get_template_directory_uri() if you want to use any of the built in icons
             * */
            function dynamic_section( $sections ) {
                //$sections = array();
                $sections[] = array(
                    'title'  => __( 'Section via hook', 'tt' ),
                    'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'tt' ),
                    'icon'   => 'el-icon-paper-clip',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );

                return $sections;
            }

            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             * */
            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }

            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             * */
            function change_defaults( $defaults ) {
                $defaults['str_replace'] = 'Testing filter hook!';

                return $defaults;
            }

            // Remove the demo link and the notice of integrated demo from the redux-framework plugin
            function remove_demo() {

                // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    remove_filter( 'plugin_row_meta', array(
                        ReduxFrameworkPlugin::instance(),
                        'plugin_metalinks'
                    ), null, 2 );

                    // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
                }
            }

            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'tt' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'tt' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'tt' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo $this->theme->display( 'Name' ); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'tt' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'tt' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'tt' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'tt' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'tt' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
                if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
                    Redux_Functions::initWpFilesystem();

                    global $wp_filesystem;

                    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
                }

                /* General
								============================== */
		            $this->sections[] = array(
			            'title'     => __('General', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-cog',
			            'fields'    => array(
			            	array(
		                  'id'        							=> 'color-body-background',
		                  'type'      							=> 'background',
		                  'output'    							=> array('body, .section-title span'),
		                  'title'     							=> __('Body Background Color', 'tt'),
		                  'subtitle'  							=> __('Site Background Color (default: #f8f8f8)', 'tt'),
		                  'default'   							=> array( 'background-color' => '#f8f8f8' ),
		                  'background-repeat'  			=> false,
		                  'background-attachment'  	=> false,
		                  'background-position'			=> false,
		                  'background-image'  			=> false,
		                  'transparent'			  			=> false,
		                  'background-size'	  			=> false,
		                ),
		                array(
			                'id'       								=> 'color-accent',
									    'type'     								=> 'color',
									    'title'    								=> __('Accent Color', 'tt'), 
									    'subtitle' 								=> __('Site Background Color (default: #70b9a0)', 'tt'),
									    'default'  								=> '#70b9a0',
									    'validate' 								=> 'color',
									    'transparent'		  				=> false,
										),
			            	array(
		                  'id'        => 'favicon',
		                  'type'      => 'media',
		                  'title'     => __('Favicon', 'tt'),
		                  'compiler'  => 'true',
		                  'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
		                  'desc'      => __('Upload Square Graphic (Recommendation: 64x64 PNG file)', 'tt' ),
		                  'subtitle'  => __('', 'tt'),
			              ),
			              array(
		                  'id'        => 'logo-menu',
		                  'type'      => 'media',
		                  'title'     => __('Logo', 'tt'),
		                  'compiler'  => 'true',
		                  'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
		                  'desc'      => __('Image will be displayed at 50% width & height for Retina-Ready purpose. For example: 300x60 image shows at 150x30. Upload your logo accordingly.', 'tt'),
		                  'subtitle'  => __('', 'tt'),
			              ),
			              array(
		                  'id'        => 'logo-url',
		                  'type'      => 'text',
		                  'title'     => __('Logo URL', 'tt'),
		                  'desc'      => __('Helpful when using intro page as your frontpage. If empty, URL will be your site address.', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  //'validate' 	=> 'url'
			              ),
			              array(
		                  'id'        => 'logo-login',
		                  'type'      => 'media',
		                  'title'     => __('WordPress Login Page - Logo', 'tt'),
		                  'compiler'  => 'true',
		                  'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
		                  'desc'      => __('Max. dimension: 320x80', 'tt'),
		                  'subtitle'  => __('', 'tt'),
			              ),
			              array(
			                'id'       								=> 'background-login',
									    'type'     								=> 'color',
									    'title'    								=> __('WordPress Login Page - Background Color', 'tt'), 
									    'subtitle' 								=> __('Default: #f8f8f8', 'tt'),
									    'default'  								=> '#f8f8f8',
									    'validate' 								=> 'color',
									    'transparent'		  				=> false,
										),
			              array(
		                  'id'        => '404-page',
		                  'type'      => 'select',
		                  'data'      => 'pages',
		                  'title'     => __('Custom 404 Error Page', 'tt'),
		                  'subtitle'  => __('Content of selected page will be shown to visitors who request a non-existing, so called "404 Error Page".', 'tt'),
		                  'desc'      => __('If nothing selected, default 404 Content will be displayed.', 'tt'),
		                ),
		                array(
		                  'id'        => 'custom-styles',
		                  'type'      => 'ace_editor',
		                  'mode' 			=> 	'css',
											'theme' 		=> 	'chrome',
		                  'title'     => __('Custom Styles (CSS)', 'tt'),
		                  'subtitle'  => __('Inline CSS right before closing <strong>&lt;/head&gt;</strong>', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'custom-scripts',
		                  'type'      => 'ace_editor',
		                  'mode' 			=> 	'javascript',
											'theme' 		=> 	'chrome',
		                  'title'     => __('Custom Scripts (Google Analytics etc.)', 'tt'),
		                  'subtitle'  => __('Inline scripts right before closing <strong>&lt;/body&gt;</strong>', 'tt'),
		                  'desc'      => __('Use "jQuery" selector, instead of "$" shorthand. Do not add any &lt;script&gt; tags, they are already applied to this code.', 'tt'),
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'enable-rtl-support',
		                  'type'      => 'checkbox',
		                  'title'     => __('Enable Right-to-Left Language Support', 'tt'),
		                  'subtitle'  => __('Required for carousel etc.', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'user-registration-terms-page',
		                  'type'      => 'select',
		                  'data'      => 'pages',
		                  'title'     => __('User Registration: Select "Terms & Conditions" Page', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Select the page that contains your "Terms & Conditions". When a page has been selected, users are required to accept your terms and conditions in order to register.', 'tt')
		                ),
		              )
		            );
		            
		            
		            /* HEADER
								============================== */
		            $this->sections[] = array(
			            'title'     => __('Header', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-bars',
			            'fields'    => array(
			            	array(
			                'id'        		=> 'header-layout',
			                'type'      		=> 'radio',
			                'title'     		=> __('Header Layout', 'tt'),
			                'subtitle'  		=> __('', 'tt'),
			                'desc'      		=> __('', 'tt'),	                
			                'options'   		=> array(
			                    'default' 	=> __('Logo left, contact right, navigation bottom', 'tt'),
			                    'nav-right' 	=> __('Logo left, contact top , navigation right', 'tt'),
			                ),
			                'default'   		=> 'default'
		                ),
			            	array(
		                  'id'        							=> 'color-header-background',
		                  'type'      							=> 'background',
		                  'output'    							=> array('header.navbar, header.navbar .navbar-collapse, header.navbar .navbar-nav > ul > li ul.sub-menu, header.navbar nav > div > ul > li ul.sub-menu'),
		                  'title'     							=> __('Site Header Background Color', 'tt'),
		                  'subtitle'  							=> __('Site Background Color (default: #333333)', 'tt'),
		                  'default'   							=> array( 'background-color' => '#333333' ),
		                  'background-repeat'  			=> false,
		                  'background-attachment'  	=> false,
		                  'background-position'			=> false,
		                  'background-image'  			=> false,
		                  'transparent'			  			=> false,
		                  'background-size'	  			=> false,
		                ),
		                array(
			                'id'       								=> 'color-header',
									    'type'     								=> 'color',
									    'title'    								=> __('Site Header Color', 'tt'), 
									    'subtitle' 								=> __('Site Header Color (default: #ffffff)', 'tt'),
									    'default'  								=> '#ffffff',
									    'validate' 								=> 'color',
									    'transparent'		  				=> false,
										),
										array(
		                  'id'        => 'site-header-phone',
		                  'type'      => 'text',
		                  'title'     => __('Phone Number', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => '+1 555 22 66 8890',
		                ),
		                array(
		                  'id'        => 'site-header-email',
		                  'type'      => 'text',
		                  'title'     => __('Email Address', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'validate'  => 'email',
		                  'default'   => 'info@yourcompany.com',
		                ),
						array(
		                  'id'        => 'show-sub-menu-by-default-on-mobile',
		                  'type'      => 'checkbox',
		                  'title'     => __('Show Sub Menu By Default On Mobile', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'disable-header-login-register-bar',
		                  'type'      => 'checkbox',
		                  'title'     => __('Disable Login/Register Bar', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'site-header-hide-property-submit-link',
		                  'type'      => 'checkbox',
		                  'title'     => __('Hide Property Submit Link', 'tt'),
		                  'subtitle'  => __('For Non-Logged-In Visitors', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'site-header-position-fixed',
		                  'type'      => 'checkbox',
		                  'title'     => __('Enable Fixed/Scrolling Header', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'header-tagline',
		                  'type'      => 'checkbox',
		                  'title'     => __('Show Site Tagline', 'tt'),
		                  'subtitle'  => __('Display "Settings  > General > Tagline" underneath logo', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		              )
		            );
		
		            
		            /* Home Slideshow
								============================== */
		            $this->sections[] = array(
			            'title'     => __('Home Slideshow', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-image',
			            'fields'    => array(
						 array(
		                  'id'        => 'slideshow-width-type',
		                  'type'      => 'radio',
		                  'title'     => __('Slideshow Width', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Applied to page template "Home - Slideshow" and "Property Slideshow". Select smaller dimension for faster page load time. Setting doesn\'t effect slideshow container width, image still stretches full width.', 'tt'),                  
									    'options'  	=> array(
								        'full' 								=> __( 'Original Image Ratio', 'tt' ),
								        'thumbnail-1600' 			=> __( '1600px', 'tt' ) . ' (new thumbnail size in v1.6.2, if selected make sure to <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">regenarate thumbnails</a>)',
								        'thumbnail-1200' 			=> __( '1200px (Default)', 'tt' )
									    ),
									    'default'  => 'thumbnail-1200',
		                ),
				            array(
		                  'id'        => 'home-slideshow-height-type',
		                  'type'      => 'radio',
		                  'title'     => __('Slideshow Height', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Applied to page template "Home - Slideshow" and "Property Slideshow". When selecting "Original Image Ratio" make sure your images have a sufficient height on mobile devices. If not, please resize them accordingly.', 'tt'),                  
									    'options'  	=> array(
								        'original' 		=> __( 'Original Image Ratio', 'tt' ),
								        'fullscreen' 	=> __( 'Fullscreen (min. 400px for proper display on mobile)', 'tt' ),
								        'custom' 			=> __( 'Custom Height (min. 400px for proper display on mobile)', 'tt' )
									    ),
									    'default'  => 'fullscreen',
		                ),
				            array(
		                  'id'        => 'home-slideshow-custom-height',
		                  'type'      => 'text',
		                  'title'     => __('Custom Slideshow Height', 'tt'),
		                  'subtitle'  => __('Default: 400', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => '400',
											'required' 	=> 	array('home-slideshow-height-type','=','custom'),
		                ),
		                array(
		                  'id'        => 'home-slideshow-speed',
		                  'type'      => 'text',
		                  'title'     => __('Slideshow Speed (in ms)', 'tt'),
		                  'subtitle'  => __('Default: 5000', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => '5000',                ),
										array(
			                'id'        		=> 'home-slideshow-type',
			                'type'      		=> 'radio',
			                'title'     		=> __('Home Slideshow Type', 'tt'),
			                'subtitle'  		=> __('', 'tt'),
			                'desc'      		=> __('', 'tt'),	                
			                'options'   		=> array(
			                    'slideshow-properties' 	=> __('Property Slideshow', 'tt'),
			                    'slideshow-custom' 			=> __('Custom Content Slideshow', 'tt'),
			                ),
			                'default'   		=> 'slideshow-properties'
		                ),
		                array(
			                'id'        		=> 'home-slideshow-properties-mode',
			                'type'      		=> 'radio',
			                'title'     		=> __('Property Slideshow Type', 'tt'),
			                'subtitle'  		=> __('', 'tt'),
			                'desc'      		=> __('', 'tt'),	                
			                'options'   		=> array(
				                	'slideshow-properties-show-featured' 		=> __('Show Featured Properties', 'tt'),
			                    'slideshow-properties-show-latest' 		=> __('Show Latest 3 Properties', 'tt'),
			                    'slideshow-properties-show-selected'	=> __('Show Selected Properties', 'tt'),
			                ),
			                'default'   		=> 'slideshow-properties-show-latest',
			                'required' 	=> 	array('home-slideshow-type','=','slideshow-properties'),
		                ),
										array(
		                  'id'        => 'home-property-slides',
		                  'type'      => 'select',
		                  'data'      => 'posts',
		                  'args' 			=> array('post_type' => 'property', 'posts_per_page' => -1),
		                  'multi'			=> true,
		                  'sortable'	=> true,
		                  'title'     => __('Property Slides', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Select Slideshow Properties. Order Via Drag & Drop.', 'tt'),
		                  'required' 	=> 	array('home-slideshow-properties-mode','=','slideshow-properties-show-selected'),
		                ),
										array(
		                  'id'        => 'home-slides',
		                  'type'      => 'slides',
		                  'title'     => __('Custom Content Slides', 'tt'),
		                  'subtitle'  => __('Create Your Custom Home Slideshow.', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'placeholder'   => array(
		                      'title'         => __('This is a title', 'tt'),
		                      'description'   => __('Description Here', 'tt'),
		                      'url'           => __('Give us a link!', 'tt'),
		                  ),
		                  'required' 	=> 	array('home-slideshow-type','=','slideshow-custom'),	
		                ),
										array(
			                'id'        		=> 'home-slideshow-search',
			                'type'      		=> 'radio',
			                'title'     		=> __('Show Property Search Form', 'tt'),
			                'subtitle'  		=> __('', 'tt'),
			                'desc'      		=> __('', 'tt'),	                
			                'options'   		=> array(
				                	'none' 		=> __('None', 'tt'),
			                    'mini' 		=> __('Mini Search', 'tt'),
			                    'custom'	=> __('Custom Search (configure under "Property Search")', 'tt'),
			                ),
			                'default'   		=> 'none',
		                ),
		              )
		            );
		            
		            
		            /* Map
								============================== */
		            $this->sections[] = array(
			            'title'     => __('Map', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-map-marker',
			            'fields'    => array(
				            array(
		                  'id'        => 'map-height-type-home-search',
		                  'type'      => 'radio',
		                  'title'     => __('Map Height', 'tt'),
		                  'subtitle'  => __('Min. 400px for proper display on mobile', 'tt'),
		                  'desc'      => __('Will be applied to home map and search results map.', 'tt'),                  
									    'options'  	=> array(
								        'fullscreen' 	=> __( 'Fullscreen', 'tt' ),
								        'custom' 			=> __( 'Custom Height', 'tt' )
									    ),
									    'default'  => 'custom',
		                ),
				            array(
		                  'id'        => 'map-height-custom-home-search',
		                  'type'      => 'text',
		                  'title'     => __('Custom Map Height', 'tt'),
		                  'subtitle'  => __('Default: 400', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => '400',
											'required' 	=> 	array('map-height-type-home-search','=','custom'),
		                ),
			            	array(
			                'id'        => 'map-marker-property-default',
			                'type'      => 'image_select',
			                'title'     => __('Default Map Marker: Property', 'tt'),
			                'subtitle'  => __('Default: Green (Dimension: 100x138)', 'tt'),
			                'desc'      => __('', 'tt'),
			                
			                //Must provide key => value(array:title|img) pairs for radio options
			                'options'   => array(
			                  get_template_directory_uri().'/lib/images/map-marker/map-marker-red-fat.png' 	=> array('title' => 'Map Marker: Red', 'img' => get_template_directory_uri().'/lib/images/map-marker/map-marker-red-fat.png'),
			                  get_template_directory_uri().'/lib/images/map-marker/map-marker-blue-fat.png' 	=> array('title' => 'Map Marker: Blue', 'img' => get_template_directory_uri().'/lib/images/map-marker/map-marker-blue-fat.png'),
			                  get_template_directory_uri().'/lib/images/map-marker/map-marker-green-fat.png' 	=> array('title' => 'Map Marker: Green', 'img' => get_template_directory_uri().'/lib/images/map-marker/map-marker-green-fat.png'),
			                ), 
			                'default'   => get_template_directory_uri().'/lib/images/map-marker/map-marker-green-fat.png'
		                ),
			            	array(
		                  'id'        => 'map-marker-property',
		                  'type'      => 'media',
		                  'title'     => __('Custom Map Marker: Property', 'tt'),
		                  'compiler'  => 'true',
		                  'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
		                  'desc'      => __('', 'tt' ),
		                  'subtitle'  => __('Transparent PNG file (Recommended Dimension: 100x138)', 'tt'),
			              ),
			              array(
			                'id'        => 'map-marker-cluster-default',
			                'type'      => 'image_select',
			                'title'     => __('Default Map Marker: Cluster', 'tt'),
			                'subtitle'  => __('Default: Red (Dimension: 100x100)', 'tt'),
			                'desc'      => __('', 'tt'),
			                
			                //Must provide key => value(array:title|img) pairs for radio options
			                'options'   => array(
			                  get_template_directory_uri().'/lib/images/map-marker/map-marker-red-round.png' 	=> array('title' => 'Map Marker: Red', 'img' => get_template_directory_uri().'/lib/images/map-marker/map-marker-red-round.png'),
			                  get_template_directory_uri().'/lib/images/map-marker/map-marker-blue-round.png' 	=> array('title' => 'Map Marker: Blue', 'img' => get_template_directory_uri().'/lib/images/map-marker/map-marker-blue-round.png'),
			                  get_template_directory_uri().'/lib/images/map-marker/map-marker-green-round.png' 	=> array('title' => 'Map Marker: Green', 'img' => get_template_directory_uri().'/lib/images/map-marker/map-marker-green-round.png'),
			                ), 
			                'default'   => get_template_directory_uri().'/lib/images/map-marker/map-marker-red-round.png'
		                ),
			              array(
		                  'id'        => 'map-marker-cluster',
		                  'type'      => 'media',
		                  'title'     => __('Custom Map Marker: Cluster', 'tt'),
		                  'compiler'  => 'true',
		                  'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
		                  'desc'      => __('For a non-square graphic, you have to add some Custom CSS to position the cluster number: #map .cluster > div { line-height: 1 !important; padding-top: ??px !important; }', 'tt' ),
		                  'subtitle'  => __('Transparent Square PNG file (Recommended Dimension: 100x100)', 'tt'),
			              ),
										array(
			                'id'            => 'map-default-zoom-level',
			                'type'          => 'spinner',
			                'title'         => __('Default Zoom Level', 'tt'),
			                'subtitle'      => __('Default: 14', 'tt'),
			                'desc'          => __('Applies to: Property Single Page.', 'tt'),
			                'default'       => 14,
			                'min'           => 1,
			                'step'          => 1,
			                'max'           => 20,
			                'display_value' => 'label'
										),
										array(
			                'id'            => 'map-properties-quantity',
			                'type'          => 'spinner',
			                'title'         => __('Number of Properties on Map', 'tt'),
			                'subtitle'      => __('', 'tt'),
			                'desc'          => __('Select "-1" to display all properties on the map.', 'tt'),
			                'default'       => -1,
			                'min'           => -1,
			                'step'          => 1,
			                'max'           => 100,
			                'display_value' => 'label'
										),
										array(
		                  'id'        => 'disable-google-maps-api',
		                  'type'      => 'checkbox',
		                  'title'     => __('Don\'t load Google Maps API', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Check this box, if map is not showing. Another plugin might already load the API, and a duplicate API request produces an error.', 'tt'),
		                  'default'   => 0
		                ),
									)
								);
		            
		                        
		            /* Property
								============================== */
								
								// Default Fields
								$default_fields_search = array(
									__( 'Default Fields', 'tt' ) => array(
						        'estate_search_by_keyword'   					=> __( 'Search by Keyword', 'tt' ), 
						        'estate_property_id'    							=> __( 'Property ID', 'tt' ), 
			              'estate_property_location'        		=> __( 'Location', 'tt' ), 
			              'estate_property_type'    						=> __( 'Type', 'tt' ), 
			              'estate_property_status'      				=> __( 'Status', 'tt' ), 
			              'estate_property_price'     					=> __( 'Price', 'tt' ), 
			              'estate_property_pricerange'     			=> __( 'Price Range (set options below)', 'tt' ), 
			              'estate_property_size'      					=> __( 'Size', 'tt' ), 
			              'estate_property_rooms'      					=> __( 'Rooms', 'tt' ), 
			              'estate_property_bedrooms'      			=> __( 'Bedrooms', 'tt' ), 
			              'estate_property_bathrooms'      			=> __( 'Bathrooms', 'tt' ), 
			              'estate_property_garages'      				=> __( 'Garages', 'tt' ), 
			              'estate_property_available_from'			=> __( 'Availability / Date', 'tt' )
		              )
		            );
		            
		            // Default Property Listing Fields
								$default_fields_listing = array(
									__( 'Default Listing Fields', 'tt' ) => array(
						        'estate_property_id'    							=> __( 'Property ID', 'tt' ), 
			              'estate_property_size'      					=> __( 'Size', 'tt' ), 
			              'estate_property_rooms'      					=> __( 'Rooms', 'tt' ), 
			              'estate_property_bedrooms'      			=> __( 'Bedrooms', 'tt' ), 
			              'estate_property_bathrooms'      			=> __( 'Bathrooms', 'tt' ), 
			              'estate_property_garages'      				=> __( 'Garages', 'tt' ), 
			              'estate_property_available_from'			=> __( 'Availability / Date', 'tt' )
		              )
		            );
			            
		            require_once ( TT_LIB . '/advanced-custom-fields.php' );
								
								// Check if ACF is activated & ACF for post type "property" field groups
								if ( tt_acf_active() && tt_acf_group_id_property() ) {						
									$acf_fields = array_combine( tt_acf_fields_name( tt_acf_group_id_property() ), tt_acf_fields_label( tt_acf_group_id_property() ) );
									$acf_fields = array( __( 'Advanced Custom Fields', 'tt' ) => $acf_fields );
									
									$merged_fields_search = array_merge( $default_fields_search, $acf_fields );
									$merged_fields_listing = array_merge( $default_fields_listing, $acf_fields );
								}
								else {
									$merged_fields_search = $default_fields_search;
									$merged_fields_listing = $default_fields_listing;
								}
										
		            $this->sections[] = array(
			            'title'     => __('Property', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-home',
			            'fields'    => array(

			            	array(
		                    'id'        => 'section-property-listing',
		                    'type'      => 'section',
		                    'title'     => __('Property Listing', 'tt'),
		                    'subtitle'  => __('', 'tt'),
		                ),
		                
		                array(
		                  'id'        => 'property-listing-type',
		                  'type'      => 'radio',
		                  'title'     => __('Property Listing Fields', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),                  
									    'options'  	=> array(
								        'default' 	=> __( 'Default', 'tt' ),
								        'custom' 		=> __( 'Custom', 'tt' )
									    ),
									    'default'  => 'default',
		                ),
		                
		                array(
											'id'       		=> 'property-custom-listing',
											'type'     		=> 'repeater',
											'title'    		=> __( 'Custom Listing Fields', 'tt' ),
											'subtitle' 		=> __( 'FontAwesome icon classes, e.g. "fa-expand", can be found here: <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">http://fortawesome.github.io/Font-Awesome/icons/</a>. Do not use Advanced Custom Fields with a type of "checkbox" etc., as only single values are accepted.', 'tt' ),
											'bind_title' => 'property-custom-listing-label',
											//'bind_title' 	=> false,
											//'group_values' => false, // Group all fields below within the repeater ID
											//'static'		 	=> 8,
											//'limit' 		=> 8,
											'sortable' 	=> true,
											'required' 	=> 	array('property-listing-type','=','custom'),
											'fields' => array(
											  array(
											    'id'          => 'property-custom-listing-field',
											    'title'    		=> __( '', 'tt' ),
													'subtitle' 		=> __( '', 'tt' ),
													'desc' 		=> __( '', 'tt' ),
											    'type'        => 'select',
											    'options'     => $merged_fields_listing,
											    'placeholder' => __( 'Field Type', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-custom-listing-icon-class',
											    'type'        => 'text',
											    'placeholder' => __( 'FontAwesome Icon Class', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-custom-listing-label',
											    'type'        => 'text',
											    'placeholder' => __( 'Label', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-custom-listing-label-plural',
											    'type'        => 'text',
											    'placeholder' => __( 'Label Plural', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-custom-listing-tooltip',
											    'type'        => 'checkbox',
											    'subtitle'		=> __( 'Show Tooltip Only', 'tt' ),
											    'default'   	=> 0
											  )
											)
										),
		                
		                array(
		                  'id'        => 'property-listing-default-view',
		                  'type'      => 'radio',
		                  'title'     => __('Property Listing Default View', 'tt'),
		                  'subtitle'  => __('Default: Grid View', 'tt'),
		                  'desc'      => __('', 'tt'),                  
									    'options'  	=> array(
								        'grid-view' 		=> __( 'Grid View', 'tt' ),
								        'list-view' 		=> __( 'List View', 'tt' )
									    ),
									    'default'  => 'grid-view',
		                ),
		
			            	array(
		                  'id'        => 'property-listing-columns',
		                  'type'      => 'radio',
		                  'title'     => __('Number Of Columns', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),                  
									    'options'  	=> array(
								        'col-md-6' => __( '2 Columns', 'tt' ),
								        'col-lg-4 col-md-6' => __( '3 Columns', 'tt' ),
								        'col-lg-3 col-md-6' => __( '4 Columns', 'tt' ),
									    ),
									    'default'  => 'col-lg-4 col-md-6',
		                ),
		                array(
			                'id'            => 'search-results-per-page',
			                'type'          => 'spinner',
			                'title'         => __('Number of Properties Per Page', 'tt'),
			                'subtitle'      => __('', 'tt'),
			                'desc'          => __('Used For Property Search, Taxonomies etc.', 'tt'),
			                'default'       => 10,
			                'min'           => 2,
			                'step'          => 1,
			                'max'           => 50,
			                'display_value' => 'label'
										),
										array(
			                'id'            => 'property-new-badge',
			                'type'          => 'spinner',
			                'title'         => __('"New" Property Badge', 'tt'),
			                'subtitle'      => __('', 'tt'),
			                'desc'          => __('Add <i class="fa fa-fire"></i> icon to property, if published within the last .. days. Set to "0" to disable this feature.', 'tt'),
			                'default'       => 7,
			                'min'           => 0,
			                'step'          => 1,
			                'max'           => 360,
			                'display_value' => 'label'
										),
										array(
		                  'id'        => 'property-comparison-disabled',
		                  'type'      => 'checkbox',
		                  'title'     => __('Disable Property Comparison', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
										array(
		                  'id'        => 'property-favorites-disabled',
		                  'type'      => 'checkbox',
		                  'title'     => __('Disable Favorites Feature', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
										array(
		                  'id'        => 'property-favorites-temporary',
		                  'type'      => 'checkbox',
		                  'title'     => __('Allow Non-Logged-In Visitors To Save Favorites Temporary', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
										array(
		                    'id'        => 'section-property-detail-page',
		                    'type'      => 'section',
		                    'title'     => __('Property Detail Page', 'tt'),
		                    'subtitle'  => __('', 'tt'),
		                ),
		                
		                array(
		                  'id'        => 'property-meta-data-type',
		                  'type'      => 'radio',
		                  'title'     => __('Property Meta Data', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),                  
									    'options'  	=> array(
								        'default' 	=> __( 'Default', 'tt' ),
								        'custom' 		=> __( 'Custom', 'tt' )
									    ),
									    'default'  => 'default',
		                ),
		                
		                array(
											'id'       		=> 'property-custom-meta-data',
											'type'     		=> 'repeater',
											'title'    		=> __( 'Custom Meta Data', 'tt' ),
											'subtitle' 		=> __( 'FontAwesome icon classes, e.g. "fa-expand", can be found here: <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">http://fortawesome.github.io/Font-Awesome/icons/</a>. Do not use Advanced Custom Fields with a type of "checkbox" etc., as only single values are accepted.', 'tt' ),
											'bind_title' => 'property-custom-meta-data-label',
											//'bind_title' 	=> false,
											//'group_values' => false, // Group all fields below within the repeater ID
											//'static'		 	=> 8,
											//'limit' 		=> 8,
											'sortable' 	=> true,
											'required' 	=> 	array('property-meta-data-type','=','custom'),
											'fields' => array(
											  array(
											    'id'          => 'property-custom-meta-data-field',
											    'title'    		=> __( '', 'tt' ),
													'subtitle' 		=> __( '', 'tt' ),
													'desc' 		=> __( '', 'tt' ),
											    'type'        => 'select',
											    'options'     => $merged_fields_listing,
											    'placeholder' => __( 'Field Type', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-custom-meta-data-icon-class',
											    'type'        => 'text',
											    'placeholder' => __( 'FontAwesome Icon Class', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-custom-meta-data-label',
											    'type'        => 'text',
											    'placeholder' => __( 'Label', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-custom-meta-data-label-plural',
											    'type'        => 'text',
											    'placeholder' => __( 'Label Plural', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-custom-meta-data-tooltip',
											    'type'        => 'checkbox',
											    'subtitle'		=> __( 'Show Tooltip Only', 'tt' ),
											    'default'   	=> 0
											  )
											)
										),
										
										array(
		                  'id'        => 'property-meta-data-hide-print',
		                  'type'      => 'checkbox',
		                  'title'     => __('Property Meta Data: Hide Print Icon', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                
		                array(
			                'id'        		=> 'property-id-type',
			                'type'      		=> 'radio',
			                'title'     		=> __('Property ID Type', 'tt'),
			                'subtitle'  		=> __('', 'tt'),
			                'desc'      		=> __('', 'tt'),	                
			                'options'   		=> array(
			                    'post_id' 			=> __('Post ID', 'tt'),
			                    'custom_id' 		=> __('Custom Property ID', 'tt'),
			                ),
			                'default'   		=> 'post_id'
		                ),
		                
										array(
			                'id'        		=> 'property-layout',
			                'type'      		=> 'radio',
			                'title'     		=> __('Default Single Property Layout', 'tt'),
			                'subtitle'  		=> __('', 'tt'),
			                'desc'      		=> __('', 'tt'),	                
			                'options'   		=> array(
			                    'layout-full-width' 	=> __('Full Width Property Image / Slideshow', 'tt'), 
			                    'layout-boxed' 				=> __('Boxed Property Image / Slideshow', 'tt'), 
			                ),
			                'default'   		=> 'layout-full-width'
		                ),
		                array(
			                'id'        		=> 'property-lightbox',
			                'type'      		=> 'radio',
			                'title'     		=> __('Property Lightbox', 'tt'),
			                'subtitle'  		=> __('', 'tt'),
			                'desc'      		=> __('', 'tt'),	                
			                'options'   		=> array(
			                    'magnific-popup' 	=> __('Magnific Popup', 'tt'), 
			                    'intense-images' 	=> __('Intense Images', 'tt'), 
			                    'none' 						=> __('None', 'tt'), 
			                ),
			                'default'   		=> 'magnific-popup'
		                ),
										array(
		                  'id'        => 'property-image-width',
		                  'type'      => 'radio',
		                  'title'     => __('Property Image Width', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Select smaller dimension for faster page load time. Setting doesn\'t effect slideshow container width, image still stretches full width.', 'tt'),                  
									    'options'  	=> array(
								        'full' 								=> __( 'Original Image Ratio', 'tt' ),
								        'thumbnail-1600' 			=> __( '1600px', 'tt' ) . ' (new thumbnail size in v1.6.2, if selected make sure to <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">regenarate thumbnails</a>)',
								        'thumbnail-1200' 			=> __( '1200px (Default)', 'tt' )
									    ),
									    'default'  => 'thumbnail-1200',
		                ),
		                array(
		                  'id'        => 'property-image-height',
		                  'type'      => 'radio',
		                  'title'     => __('Property Image Height', 'tt'),
		                  'subtitle'  => __('Min. 400px for proper display on mobile', 'tt'),
		                  'desc'      => __('', 'tt'),                  
									    'options'  	=> array(
								        'original' 		=> __( 'Original Image Ratio', 'tt' ),
								        'fullscreen' 	=> __( 'Fullscreen (min. 400px for proper display on mobile)', 'tt' ),
								        'custom' 			=> __( 'Custom Height (min. 400px for proper display on mobile)', 'tt' )
									    ),
									    'default'  => 'original',
		                ),
				            array(
		                  'id'        => 'property-image-custom-height',
		                  'type'      => 'text',
		                  'title'     => __('Custom Property Image Height', 'tt'),
		                  'subtitle'  => __('Default: 400', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => '400',
											'required' 	=> 	array('property-image-height','=','custom'),
		                ),
		                array(
		                  'id'        => 'property-image-height-fit-or-cut',
		                  'type'      => 'radio',
		                  'title'     => __('Property Image Overflow Handling', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Choose to fit or cut off your property images.', 'tt'),                  
									    'options'  	=> array(
								        'fit' => __( 'Fit Image', 'tt' ),
								        'cut' => __( 'Cut Off Image', 'tt' ),
									    ),
									    'default'  => 'cut',
									    'required' 	=> 	array('property-image-height','!=','original'),
		                ),
		                array(
		                  'id'        => 'property-additional-details-hide-empty',
		                  'type'      => 'checkbox',
		                  'title'     => __('Hide Empty Additional Details', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'property-features-hide-non-applicable',
		                  'type'      => 'checkbox',
		                  'title'     => __('Hide Non Applicable Property Features', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
			            	array(
		                  'id'        => 'property-title-details',
		                  'type'      => 'text',
		                  'title'     => __('Property Title: Details', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => __( '', 'tt' ),
		                ),
		                array(
		                  'id'        => 'property-title-additional-details',
		                  'type'      => 'text',
		                  'title'     => __('Property Title: Custom Fields', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Won\'t appear, if no advanced custom fields are created.', 'tt'),
		                  'default'   => __( 'Additional details', 'tt' ),
		                ),
		                array(
		                  'id'        => 'property-title-features',
		                  'type'      => 'text',
		                  'title'     => __('Property Title: Features', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => __( 'Features', 'tt' ),
		                ),
		                array(
		                  'id'        => 'property-title-map',
		                  'type'      => 'text',
		                  'title'     => __('Property Title: Map', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => __( 'Location', 'tt' ),
		                ),
		                array(
		                  'id'        => 'property-title-attachments',
		                  'type'      => 'text',
		                  'title'     => __('Property Title: Attachments', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => __( 'Attachments', 'tt' ),
		                ),
		                array(
		                  'id'        => 'property-title-floor-plan',
		                  'type'      => 'text',
		                  'title'     => __('Property Title: Floor Plan', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => __( 'Floor Plan', 'tt' ),
		                ),
		                array(
		                  'id'        => 'property-title-agent',
		                  'type'      => 'text',
		                  'title'     => __('Property Title: Agent', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => __( 'Agent', 'tt' ),
		                ),
		                array(
		                  'id'        => 'property-floor-plan-disable',
		                  'type'      => 'checkbox',
		                  'title'     => __('Disable Floor Plans', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
										array(
		                  'id'        => 'property-social-sharing',
		                  'type'      => 'checkbox',
		                  'title'     => __('Display Social Sharing Buttons', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 1
		                ),
		                array(
		                  'id'        => 'property-agent-information',
		                  'type'      => 'checkbox',
		                  'title'     => __('Display Agent Information', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 1
		                ),
		                array(
		                  'id'        => 'property-contact-form',
		                  'type'      => 'checkbox',
		                  'title'     => __('Display Contact Form', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 1
		                ),
		                array(
		                  'id'        => 'property-contact-form-cf7-shortcode',
		                  'type'      => 'text',
		                  'title'     => __('Use Contact Form 7 Instead Of Default Contact Form', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Enter your Contact Form 7 shortcode ID. Make sure CF7 plugin is activated and that ID entered matches your CF7 form ID. Leave empty to use the default contact form.', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => '',
											'required' 	=> 	array('property-contact-form','=','1'),
		                ),
		                array(
		                  'id'        => 'property-contact-form-default-email',
		                  'type'      => 'text',
		                  'title'     => __('Default Contact Email Adress', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Used, if agent has no email address, and on his/her profile page.', 'tt'),
		                  'validate' 	=> 'email',
		                  'default'   => '',          
		                ),
										array(
		                  'id'        => 'property-similar-properties-criteria',
		                  'type'      => 'checkbox',
		                  'title'     => 'Similar Properties',
		                  'subtitle'  => 'Check criteria that a property has to meet in order to be listed on a property detail page under "Similar Properties".',
		                  'compiler'  => 'true',
		                  'options'   => array(
			                  'location' 						=> __( 'Same location', 'tt' ), 
			                  'status' 							=> __( 'Same status', 'tt' ), 
			                  'type' 								=> __( 'Same type', 'tt' ), 
			                  'min_rooms'						=> __( 'Min. Rooms', 'tt' ), 
			                  'max_price' 					=> __( 'Max. Price', 'tt' ), 
			                  'available_from' 			=> __( 'Available From', 'tt' ), 
		                  ),
		                  'default'   => array(
		                    'location' 						=> '1', 
		                    'status' 							=> '0', 
		                    'type' 								=> '0',
		                    'min_rooms' 					=> '0',
		                    'max_price' 					=> '0',
		                    'available_from' 			=> '0',
		                  )
		                ),
		                array(
			                'id'            => 'property-similar-properties-columns',
			                'type'          => 'spinner',
			                'title'         => __('Similar Properties Columns', 'tt'),
			                'subtitle'      => __('', 'tt'),
			                'desc'          => __('', 'tt'),
			                'default'       => 2,
			                'min'           => 1,
			                'step'          => 1,
			                'max'           => 4,
			                'display_value' => 'label'
										),
										array(
		                  'id'        => 'property-comments',
		                  'type'      => 'checkbox',
		                  'title'     => __('Show Property Comments', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		              )
		            );
		
		            
		            /* Property Search
								============================== */
														
		            $this->sections[] = array(
			            'title'     => __('Property Search', 'tt'),
			            'desc'      => __('', 'tt'),
			            'class'			=> 'property-search-section',
			            'icon'      => 'el-icon-search',
			            'fields'    => array(  
				                      
			            	array(
		                    'id'        => 'section-property-search-fields',
		                    'type'      => 'section',
		                    'title'     => __('Fields: Property Search', 'tt'),
		                    'subtitle'  => '<strong>' . __( 'Required', 'tt' ) . ':</strong> ' . __( 'All search field attributes for every search field. "Unique Search Parameter" has to be all lowercase letters, no spaces. Underscores and dashes allowed.', 'tt' ) . ' (<a href="//themetrail.com/docs/realty/#property-search" target=_blank">' . __('learn more about the property search', 'tt') .'</a>).',
		                ),
		                
			            	array(
											'id'       		=> 'property-search-fields',
											'type'     		=> 'repeater',
											'title'    		=> __( '', 'tt' ),
											'subtitle' 		=> __( '', 'tt' ),
											'bind_title' => 'property-search-label',
											//'bind_title' 	=> false,
											//'group_values' => false, // Group all fields below within the repeater ID
											//'static'		 	=> 8,
											//'limit' 		=> 8,
											'sortable' 	=> true,
											'fields' => array(
											  array(
											    'id'          => 'property-search-field',
											    //'title'    		=> __( '', 'tt' ),
													//'subtitle' 		=> __( '', 'tt' ),
													//'desc' 		=> __( '', 'tt' ),
											    'type'        => 'select',
											    'options'     => $merged_fields_search,
											    'placeholder' => __( 'Search Field Type', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-search-compare',
											    'type'        => 'select',
											    'options' 		=> array(
											      'equal'    						=> __( 'Equal', 'tt' ), 
														'greater_than'       	=> __( 'Greater than', 'tt' ), 
														'less_than'    				=> __( 'Less than', 'tt' ),
														'like'    						=> __( 'Like', 'tt' )
													),
											    'placeholder' => __( 'Compare As', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-search-label',
											    'type'        => 'text',
											    'placeholder' => __( 'Search Label', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-search-parameter',
											    'type'        => 'text',
											    'placeholder' => __( 'Unique Search Parameter', 'tt' ) . ' (' . __( 'required', 'tt' ) . ')',
											  )
											)
										),
										
										array(
		                    'id'        => 'section-property-search-mini-fields',
		                    'type'      => 'section',
		                    'title'     => __('Fields: Property Search Mini', 'tt'),
		                    'subtitle'  => __( 'The following search setup is used in page template &quot;Property Slideshow&quot;. Search fields that are also being used in the property search above, should have identical parameters. Mini search doesn\'t show &quot;More&quot; link.', 'tt'),
		                ),
										
										// Mini Search
										array(
											'id'       		=> 'property-search-mini-fields',
											'type'     		=> 'repeater',
											'title'    		=> __( '', 'tt' ),
											'subtitle' 		=> __( '', 'tt' ),
											'bind_title' => 'property-search-mini-label',
											//'bind_title' 	=> false,
											//'group_values' => false, // Group all fields below within the repeater ID
											//'static'		 	=> 8,
											//'limit' 		=> 8,
											'sortable' 	=> true,
											'fields' => array(
											  array(
											    'id'          => 'property-search-mini-field',
											    //'title'    		=> __( '', 'tt' ),
													//'subtitle' 		=> __( '', 'tt' ),
													//'desc' 		=> __( '', 'tt' ),
											    'type'        => 'select',
											    'options'     => $merged_fields_search,
											    'placeholder' => __( 'Search Field Type', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-search-mini-compare',
											    'type'        => 'select',
											    'options' 		=> array(
											      'equal'    						=> __( 'Equal', 'tt' ), 
														'greater_than'       	=> __( 'Greater than', 'tt' ), 
														'less_than'    				=> __( 'Less than', 'tt' ),
														'like'    						=> __( 'Like', 'tt' )
													),
											    'placeholder' => __( 'Compare As', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-search-mini-label',
											    'type'        => 'text',
											    'placeholder' => __( 'Search Label', 'tt' ),
											  ),
											  array(
											    'id'          => 'property-search-mini-parameter',
											    'type'        => 'text',
											    'placeholder' => __( 'Unique Search Parameter', 'tt' ) . ' (' . __( 'required', 'tt' ) . ')',
											  )
											)
										),
										
		                array(
			                'id'        => 'section-property-other',
			                'type'      => 'section',
			                'title'     => __('Other', 'tt'),
			                'subtitle'  => __('Even if you are not currently using the price range slider, it is recommended to set default values, instead of leaving them blank.', 'tt'),
		                ),
		                array(
		                  'id'        => 'property-search-results-disable-map',
		                  'type'      => 'checkbox',
		                  'title'     => __('Search Results Page: Disable Map', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'property-search-price-range-min',
		                  'type'      => 'text',
		                  'title'    	=> __('Price range: Min. price', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => 0,
										),
										array(
		                  'id'        => 'property-search-price-range-max',
		                  'type'      => 'text',
		                  'title'    	=> __('Price range: Max. price', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => 100000,
										),
										array(
		                  'id'        => 'property-search-price-range-step',
		                  'type'      => 'text',
		                  'title'    	=> __('Price range: Step', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => 10000,
										),
										
		                array(
		                  'id'        => 'datepicker-language',
		                  'type'      => 'select',
		                  'data'      => 'pages',
		                  'title'     => __('Datepicker Language', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Select the language of the property search datepicker.', 'tt'),                  
									    'options'  	=> array(
								        'en' => 'English (Default)',
								        'ar' => 'Arabic',
								        'az' => 'Azerbaijani',
								        'bg' => 'Bulgarian',
								        'ca' => 'Catalan',
								        'cs' => 'Czech',
								        'cy' => 'Welch',
								        'da' => 'Danish',
								        'de' => 'German',
								        'el' => 'Greek',
								        'es' => 'Spanish',
								        'et' => 'Estonian',
								        'fa' => 'Persian',
								        'fi' => 'Finnish',
								        'fr' => 'French',
								        'he' => 'Hebrew',
								        'hr' => 'Croatian',
								        'hu' => 'Hungarian',
								        'id' => 'Bahasa Indonesia',
								        'is' => 'Icelandic',
								        'it' => 'Italian',
								        'ja' => 'Japanese',
								        'ka' => 'Georgian',
								        'kk' => 'Kazakh',
								        'kr' => 'Korean',
								        'lt' => 'Lithuanian',
								        'lv' => 'Latvian',
								        'mk' => 'Macedonian',
								        'ms' => 'Malay',
								        'nb' => 'Norwegian (bokmal)',
								        'nl-BE' => 'Belgium-Dutch',
								        'nl' => 'Dutch',
								        'no' => 'Norwegian',
								        'pl' => 'Polish',
								        'pt-BR' => 'Brazilian',
								        'pt' => 'Portuguese',
								        'ro' => 'Romanian',
								        'rs-latin' => 'Serbian-latin',
								        'rs' => 'Serbian-cyrillic',
								        'ru' => 'Russian',
								        'sk' => 'Slovak',
								        'sl' => 'Slovene',
								        'sq' => 'Albanian',
								        'sv' => 'Swedish',
								        'sw' => 'Swahili',
								        'th' => 'Thai',
								        'tr' => 'Turkish',
								        'ua' => 'Ukrainian',
								        'vi' => 'Vietnamese',
								        'zh-CN' => 'Simplified Chinese',
								        'zh-TW' => 'Traditional Chinese'
									    ),
									    'default'  => 'en',
		                ),
		                array(
		                  'id'        => 'property-search-features',
		                  'type'      => 'select',
		                  'data'      => 'terms',
		                  'args' 			=> array(
		                  	'taxonomies' 			=> 'property-features'
		                  ),
		                  'multi'			=> true,
		                  'sortable'	=> true,
		                  'title'     => __('Property Features', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Select all property features you want to add to property search form. Order via drag & drop.', 'tt')
		                ),
		
		              )
		            );
		            
		            
								/* Property Submit
								============================== */
		            $this->sections[] = array(
									'icon' 		=> 'fa fa-send',
									'title' 	=> __('Property Submit', 'tt'),
									'desc' 	=> __('', 'tt'),
									'fields' 	=> array(
										array(
		                  'id'        => 'property-submit-default-address-latitude',
		                  'type'      => 'text',
		                  'title'    	=> __('Default Address: Latitude', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('e.g.: https://www.google.com/maps/place/London,+UK/@<strong><u>51.5286416</u></strong>,-0.1015987,11z/', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => 51.5286416,
										),
										array(
		                  'id'        => 'property-submit-default-address-longitude',
		                  'type'      => 'text',
		                  'title'    	=> __('Default Address: Longitude', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('e.g.: https://www.google.com/maps/place/London,+UK/@51.5286416,<strong><u>-0.1015987</u></strong>,11z/', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => -0.1015987,
										),
		                array(
		                  'id'        => 'property-submit-notification-email-recipient',
		                  'type'      => 'text',
		                  'title'     => __('Send Email Notification To', 'tt'),
		                  'subtitle'  => __('Get notified about property submit via email.', 'tt'),
		                  'desc'      => __('If notification ends up in spam folder, set up <a href="https://wordpress.org/plugins/wp-mail-smtp/" target="_blank">WP Mail SMTP</a> for the email address entered above.', 'tt'),
		                  'validate'  => 'email',
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'property-submit-price-suffix',
		                  'type'      => 'multi_text',
		                  'title'     => __('Submit Form: Price Suffix', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Add Price Suffix That Users Can Choose From When Submitting A Property. This Helps To Prevent User Typos And Keeps All Submitted Property Data Consistent.', 'tt'),
		                  'add_text'	=> __('Add Price Suffix', 'tt'),
		                  'default'		=> array(
		                  	'/day'				=> __( '/day', 'tt' ),
		                  	'/week'				=> __('/week', 'tt' ),
		                  	'/month'			=> __('/month', 'tt' ),
		                  )
		                ),
		                array(
		                  'id'        => 'property-submit-size-unit',
		                  'type'      => 'multi_text',
		                  'title'     => __('Submit Form: Size Unit Suffix', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Add Site Unit That Users Can Choose From When Submitting A Property. This Helps To Prevent User Typos And Keeps All Submitted Property Data Consistent.', 'tt'),
		                  'add_text'	=> __('Add Site Unit', 'tt'),
		                  'default'		=> array(
		                  	'sq ft'				=> __('sq ft', 'tt' ),
		                  	'm2'					=> __('m2', 'tt' ),
		                  )
		                ),
		                array(
		                  'id'        => 'property-submit-hide-link',
		                  'type'      => 'checkbox',
		                  'title'     => __('Hide "Submit Property" Link', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'property-submit-disabled-for-subscriber',
		                  'type'      => 'checkbox',
		                  'title'     => __('Disable Property Submit For User Role Subscriber', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('If checked, only agents and admins are able to submit properties.', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                    'id'        => 'section-property-payment',
		                    'type'      => 'section',
		                    'title'     => __('Property Payments', 'tt'),
		                    'subtitle'  => __('Users with a role of "subscriber" can be charged in order to publish properties. Admins and agents publish properties right away.', 'tt'),
		                ),
		                array(
		                  'id'        => 'paypal-enable',
		                  'type'      => 'checkbox',
		                  'title'     => __('Enable property payments via PayPal', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'paypal-alerts-hide',
		                  'type'      => 'checkbox',
		                  'title'     => __('Hide PayPal payment notifications on submit page', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'paypal-enable-subscription',
		                  'type'      => 'checkbox',
		                  'title'     => __('Enable property subscription via PayPal', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('If checked, recurring payments (subscriptions) will be charged instead of one-time payments.', 'tt'),
		                  'default'   => 0
		                ),
		                array(
		                  'id'        => 'paypal-subscription-recurrence',
		                  'type'      => 'text',
		                  'title'     => __('Subscription Recurrences', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('Charge subscription fee every .. (period)', 'tt'),
		                  'validate'  => 'numeric',
		                  'default'   => 12,
		                ),
		                array(
			                'id'        		=> 'paypal-subscription-period',
			                'type'      		=> 'select',
			                'title'     		=> __('Subscription Period', 'tt'),
			                'subtitle'  		=> __('', 'tt'),
			                'desc'      		=> __('', 'tt'),	                
			                'options'   		=> array(
			                	'D' 		=> __('Days', 'tt'),
			                  'W' 		=> __('Weeks', 'tt'),
			                  'M'			=> __('Months', 'tt'),
			                  'Y'			=> __('Years', 'tt'),
			                ),
			                'default'   		=> 'M',
			                //'required' 	=> 	array('paypal-enable-subscription','=','1'),
		                ),
		                array(
		                  'id'        => 'paypal-merchant-id',
		                  'type'      => 'text',
		                  'title'     => __('PayPal merchant ID or email address', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'email',
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'paypal-ipn-email-address',
		                  'type'      => 'text',
		                  'title'     => __('IPN (Instant Payment Notification) email address', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'validate'  => 'email',
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'paypal-amount',
		                  'type'      => 'text',
		                  'title'     => __('Amount to pay per property', 'tt'),
		                  'subtitle'  => __('Format: 25.00', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'email',
		                  'default'   => '25.00',
		                ),
		                array(
		                  'id'        => 'paypal-featured-amount',
		                  'type'      => 'text',
		                  'title'     => __('Charge additional .. to set property "Featured"', 'tt'),
		                  'subtitle'  => __('Format: 10.00', 'tt'),
		                  'desc'      => __('To disable "Featured" property option, set the amount to "0"', 'tt'),
		                  //'validate'  => 'email',
		                  'default'   => '10.00',
		                ),
		                array(
		                  'id'        => 'paypal-currency-code',
		                  'type'      => 'text',
		                  'title'     => __('PayPal currency code', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'text',
		                  'default'   => 'USD',
		                ),
		                array(
		                  'id'        => 'paypal-sandbox',
		                  'type'      => 'checkbox',
		                  'title'     => __('Enable PayPal sandbox for testing', 'tt'),
		                  'subtitle'  => __('Disable to process live transactions.', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 0,
		                ),
		                array(
		                  'id'        => 'paypal-ssl',
		                  'type'      => 'checkbox',
		                  'title'     => __('Post payment over SSL connection', 'tt'),
		                  'subtitle'  => __('Recommendation: Enable SSL', 'tt'),
		                  'desc'      => __('If disabled, HTTP connection will be used.', 'tt'),
		                  'default'   => 1,
		                ),
		                array(
		                  'id'        => 'paypal-auto-publish',
		                  'type'      => 'checkbox',
		                  'title'     => __('Auto publish properties on payment completion', 'tt'),
		                  'subtitle'  => __('If disabled you have to publish properties manually.', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 1,
		                ),
									)
								);
								
								
								/* TYPOGRAPHY
								============================== */
		            $this->sections[] = array(
			            'title'     => __('Typography', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-paragraph',
			            'fields'    => array(
				            array(
			                'id'            => 'typography-header',
			                'type'          => 'typography',
			                'title'         => __('Typography Header', 'tt'),
			                'google'        => true,
			                'font-backup'   => false,
			                'font-style'    => false,
			                'font-weight'   => false,
			                'text-align'		=> false,
			                //'subsets'       => false, // Only appears if google is true and subsets not set to false
			                'font-size'     => true,
			                'line-height'   => false,
			                //'word-spacing'  => true,  // Defaults to false
			                //'letter-spacing'=> true,  // Defaults to false
			                'color'         => false,
			                //'preview'       => false, // Disable the previewer
			                'all_styles'    => false,    // Enable all Google Font style/weight variations to be added to the page
			                'output'        => array('header.navbar'), // An array of CSS selectors to apply this font style to dynamically
			                'units'         => 'em', // Defaults to px
			                'subtitle'      => __('', 'tt'),
			                'default'       => array(
			                    'font-family'   => 'Lato',
			                    'google'        => true,
			                    ),
		                ),
		                array(
			                'id'            => 'typography-headings',
			                'type'          => 'typography',
			                'title'         => __('Typography Headings', 'tt'),
			                'google'        => true,
			                'font-backup'   => false,
			                'font-style'    => false,
			                'font-weight'   => true,
			                'text-align'		=> false,
			                //'subsets'       => false, // Only appears if google is true and subsets not set to false
			                'font-size'     => true,
			                'line-height'   => false,
			                //'word-spacing'  => true,  // Defaults to false
			                //'letter-spacing'=> true,  // Defaults to false
			                'color'         => true,
			                //'preview'       => false, // Disable the previewer
			                'all_styles'    => false,    // Enable all Google Font style/weight variations to be added to the page
			                'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
			                'units'         => 'em', // Defaults to px
			                'subtitle'      => __('', 'tt'),
			                'default'       => array(
			                    'font-family'   => 'Lato',
			                    'font-style'    => '400',
			                    'google'        => true,
			                    'color' 				=> '#666'
			                    ),
		                ),
		                array(
			                'id'            => 'typography-body',
			                'type'          => 'typography',
			                'title'         => __('Typography Body', 'tt'),
			                'google'        => true,
			                'font-backup'   => false,
			                'font-style'    => false,
			                'font-weight'   => true,
			                'text-align'		=> false,
			                //'subsets'       => false, // Only appears if google is true and subsets not set to false
			                'font-size'     => true,
			                'line-height'   => false,
			                //'word-spacing'  => true,  // Defaults to false
			                //'letter-spacing'=> true,  // Defaults to false
			                'color'         => true,
			                //'preview'       => false, // Disable the previewer
			                'all_styles'    => false,    // Enable all Google Font style/weight variations to be added to the page
			                'output'        => array('body'), // An array of CSS selectors to apply this font style to dynamically
			                'units'         => 'em', // Defaults to px
			                'subtitle'      => __('', 'tt'),
			                'default'       => array(
			                    'font-family'   => 'Open Sans',
			                    'font-style'    => '400',
			                    'google'        => true,
			                    'color' 				=> '#666'
			                    ),
		                ),
		              )
		            );    
		            
		            
		            /* Colors
								==============================
		            $this->sections[] = array(
			            'title'     => __('Colors', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-tint',
			            'fields'    => array(
			              
										
		              )
		            );
		            */
		            
		            /* Format Pricing
								============================== */
		            $this->sections[] = array(
			            'title'     => __('Format Pricing', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-money',
			            'fields'    => array(
			              array(
		                  'id'        => 'price-prefix',
		                  'type'      => 'text',
		                  'title'     => __('Price Prefix', 'tt'),
		                  'subtitle'  => __('e.g. "from "', 'tt'),
		                  'desc'      => __('If filled out, prefix appears on every property price. Can be overwritten for each property.', 'tt'),
		                  //'validate'  => 'numeric',
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'price-suffix',
		                  'type'      => 'text',
		                  'title'     => __('Price Suffix', 'tt'),
		                  'subtitle'  => __('e.g. "/month"', 'tt'),
		                  'desc'      => __('If filled out, suffix appears on every property price. Can be overwritten for each property.', 'tt'),
		                  //'validate'  => 'numeric',
		                  'default'   => '',
		                ),
			              array(
		                  'id'        => 'currency-sign',
		                  'type'      => 'text',
		                  'title'     => __('Currency Sign', 'tt'),
		                  'subtitle'  => __('Default: $', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'numeric',
		                  'default'   => '$',
		                ),
		                array(
			                'id'        => 'currency-sign-position',
			                'type'      => 'radio',
			                'title'     => __('Currency Sign Position', 'tt'),
			                'subtitle'  => __('Default: left', 'tt'),
			                'desc'      => __('', 'tt'),
			                 //Must provide key => value pairs for radio options
			                'options'   => array(
			                	'left' 			=> 'Left', 
			                  'right' 		=> 'Right', 
			                ),
			                'default'   => 'left'
										),
										array(
		                  'id'        => 'price-thousands-separator',
		                  'type'      => 'text',
		                  'title'     => __('Thousands Separator', 'tt'),
		                  'subtitle'  => __('Default: ,', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'numeric',
		                  'default'   => ',',
		                ),
		                array(
			                'id'            => 'price-decimals',
			                'type'          => 'spinner',
			                'title'         => __('Price Decimals', 'tt'),
			                'subtitle'      => __('Default: 0', 'tt'),
			                'desc'          => __('', 'tt'),
			                'default'       => 0,
			                'min'           => 0,
			                'step'          => 1,
			                'max'           => 2,
			                'display_value' => 'label'
										),
			            )
			          );
			          
			          
			          
			          /* Footer
								============================== */
		            $this->sections[] = array(
			            'title'     => __('Footer', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-anchor',
			            'fields'    => array(
			            	array(
		                  'id'        							=> 'color-footer-background',
		                  'type'      							=> 'background',
		                  'output'    							=> array('#footer'),
		                  'title'     							=> __('Footer Top Background Color', 'tt'),
		                  'subtitle'  							=> __('', 'tt'),
		                  'default'   							=> array( 'background-color' => '' ),
		                  'background-repeat'  			=> false,
		                  'background-attachment'  	=> false,
		                  'background-position'			=> false,
		                  'background-image'  			=> false,
		                  'transparent'			  			=> false,
		                  'background-size'	  			=> false,
		                ),
		                array(
		                  'id'        							=> 'color-footer-bottom-background',
		                  'type'      							=> 'background',
		                  'output'    							=> array('#footer #footer-bottom'),
		                  'title'     							=> __('Footer Bottom Background Color', 'tt'),
		                  'subtitle'  							=> __('', 'tt'),
		                  'default'   							=> array( 'background-color' => '' ),
		                  'background-repeat'  			=> false,
		                  'background-attachment'  	=> false,
		                  'background-position'			=> false,
		                  'background-image'  			=> false,
		                  'transparent'			  			=> false,
		                  'background-size'	  			=> false,
		                ),
		                array(
			                'id'       								=> 'color-footer',
									    'type'     								=> 'color',
									    'title'    								=> __('Footer Color', 'tt'), 
									    'subtitle' 								=> __('Footer Color (default: #999999)', 'tt'),
									    'default'  								=> '#999999',
									    'output'    							=> array('#footer #footer-bottom, #footer #footer-bottom a, #footer .widget-title'),
									    'validate' 								=> 'color',
									    'transparent'		  				=> false,
										),
			              array(
		                  'id'        => 'copyright',
		                  'type'      => 'text',
		                  'title'     => __('Copyright Text', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'numeric',
		                  'default'   => __( '&copy; 2014 - <a href="http://themetrail.com">ThemeTrail</a>', 'tt' ),
		                ),
		                array(
		                  'id'        => 'footer-show-up-button',
		                  'type'      => 'checkbox',
		                  'title'     => __('Display "To The Top" Button', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 1
		                ),
		                array(
		                  'id'        => 'footer-property-search-button',
		                  'type'      => 'checkbox',
		                  'title'     => __('Display "Property Search" Button', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  'default'   => 1
		                )                
			            )
			          );
			          
			          
			          
			          /* Social
								============================== */
		            $this->sections[] = array(
			            'title'     => __('Social', 'tt'),
			            'desc'      => __('', 'tt'),
			            'icon'      => 'fa fa-facebook-square',
			            'fields'    => array(
		                array(
		                  'id'        => 'social-facebook',
		                  'type'      => 'text',
		                  'title'     => __('Faceook URL', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'url',
		                  'default'   => '#',
		                ),
		                array(
		                  'id'        => 'social-twitter',
		                  'type'      => 'text',
		                  'title'     => __('Twitter URL', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'url',
		                  'default'   => '#',
		                ),
		                array(
		                  'id'        => 'social-google',
		                  'type'      => 'text',
		                  'title'     => __('Google+ URL', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'url',
		                  'default'   => '#',
		                ),
		                array(
		                  'id'        => 'social-linkedin',
		                  'type'      => 'text',
		                  'title'     => __('LinkedIn URL', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'url',
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'social-pinterest',
		                  'type'      => 'text',
		                  'title'     => __('Pinterest URL', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'url',
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'social-instagram',
		                  'type'      => 'text',
		                  'title'     => __('Instagram URL', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'url',
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'social-youtube',
		                  'type'      => 'text',
		                  'title'     => __('YouTube URL', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'url',
		                  'default'   => '',
		                ),
		                array(
		                  'id'        => 'social-skype',
		                  'type'      => 'text',
		                  'title'     => __('Skype URL', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'desc'      => __('', 'tt'),
		                  //'validate'  => 'url',
		                  'default'   => '',
		                )
			            )
			          );
			          
			          
			          
			          /* Contact
								============================== */
		            $this->sections[] = array(
									'icon' 		=> 'fa fa-envelope',
									'title' 	=> __('Contact', 'tt'),
									'desc' 	=> __('Contact Details for Contact Page Template.', 'tt'),
									'fields' 	=> array(
										array(
											'id'					=> 	'contact-google-map',
											'type' 				=> 	'switch',
											'title' 			=> 	__('Show Google Maps', 'tt'), 
											'subtitle' 		=> 	__('', 'tt'),
											'desc'				=> 	__('Show Google Map on Contact Page Template.', 'tt'),
											'default' 		=> 	1,
											'on'					=> 	__('Yes', 'tt'), 
											'off'					=> 	__('No', 'tt'), 
										),
										array(
		                  'id'        => 'contact-logo',
		                  'type'      => 'media',
		                  'title'     => __('Logo', 'tt'),
		                  'compiler'  => 'true',
		                  'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
		                  'desc'      => __('', 'tt'),
		                  'subtitle'  => __('', 'tt'),
		                  'required' 	=> 	array('contact-google-map','=','1'),	
			              ),
			              array(
											'id'					=>	'contact-address',
											'type' 				=> 	'text',
											'title' 			=> 	__('Address', 'tt'),
											'subtitle' 		=> 	__('', 'tt'),
											'desc' 				=> 	__('', 'tt'),
											//'validate' 		=> 	'no_special_chars',
											'msg' 				=> 	'',
											'default' 		=> 	__( 'Main St, New York, USA', 'tt' ),
											'required' 		=> 	array('contact-google-map','=','1'),	
										),	
										array(
											'id'					=>	'contact-phone',
											'type' 				=> 	'text',
											'title' 			=> 	__('Phone', 'tt'),
											'subtitle' 		=> 	__('', 'tt'),
											'desc' 				=> 	__('', 'tt'),
											//'validate' 		=> 	'no_special_chars',
											'msg' 				=> 	'',
											'default' 		=> 	'+1 555 22 66 8890',
											'required' 		=> 	array('contact-google-map','=','1'),	
										),
										array(
											'id'					=>	'contact-mobile',
											'type' 				=> 	'text',
											'title' 			=> 	__('Mobile', 'tt'),
											'subtitle' 		=> 	__('', 'tt'),
											'desc' 				=> 	__('', 'tt'),
											//'validate' 		=> 	'no_special_chars',
											'msg' 				=> 	'',
											'default' 		=> 	'+1 555 22 66 8891',
											'required' 		=> 	array('contact-google-map','=','1'),	
										),	
										array(
											'id'					=>	'contact-email',
											'type' 				=> 	'text',
											'title' 			=> 	__('Email', 'tt'),
											'subtitle' 		=> 	__('', 'tt'),
											'desc' 				=> 	__('', 'tt'),
											//'validate' 		=> 	'no_special_chars',
											'msg' 				=> 	'',
											'default' 		=> 	'info@yourcompany.com',
											'required' 		=> 	array('contact-google-map','=','1'),	
										),
									)
								);

                if ( file_exists( trailingslashit( dirname( __FILE__ ) ) . 'README.html' ) ) {
                    $tabs['docs'] = array(
                        'icon'    => 'el-icon-book',
                        'title'   => __( 'Documentation', 'tt' ),
                        'content' => nl2br( file_get_contents( trailingslashit( dirname( __FILE__ ) ) . 'README.html' ) )
                    );
                }
            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => __( 'Theme Information 1', 'tt' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'tt' )
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => __( 'Theme Information 2', 'tt' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'tt' )
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'tt' );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'realty_theme_option',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'submenu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'Theme Options', 'tt' ),
                    'page_title'           => __( 'Theme Options', 'tt' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => 'AIzaSyB66Y-sRZ5P60QYBoGHn3PhplGX2i7o87k',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => false,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => false,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => '_options',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    'footer_credit'     => '&nbsp;',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );

                // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-docs',
                    'href'   => 'http://docs.reduxframework.com/',
                    'title' => __( 'Documentation', 'tt' ),
                );

                $this->args['admin_bar_links'][] = array(
                    //'id'    => 'redux-support',
                    'href'   => 'https://github.com/ReduxFramework/redux-framework/issues',
                    'title' => __( 'Support', 'tt' ),
                );

                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-extensions',
                    'href'   => 'reduxframework.com/extensions',
                    'title' => __( 'Extensions', 'tt' ),
                );

                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                /*
                $this->args['share_icons'][] = array(
                    'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                    'title' => 'Visit us on GitHub',
                    'icon'  => 'el-icon-github'
                    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                    'title' => 'Like us on Facebook',
                    'icon'  => 'el-icon-facebook'
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'http://twitter.com/reduxframework',
                    'title' => 'Follow us on Twitter',
                    'icon'  => 'el-icon-twitter'
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'http://www.linkedin.com/company/redux-framework',
                    'title' => 'Find us on LinkedIn',
                    'icon'  => 'el-icon-linkedin'
                );
								*/
								
                // Panel Intro text -> before the form
                if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                    if ( ! empty( $this->args['global_variable'] ) ) {
                        $v = $this->args['global_variable'];
                    } else {
                        $v = str_replace( '-', '_', $this->args['opt_name'] );
                    }
                    $this->args['intro_text'] = sprintf( __( '', 'tt' ), $v );
                } else {
                    $this->args['intro_text'] = __( '', 'tt' );
                }

                // Add content after the form.
                $this->args['footer_text'] = __( '', 'tt' );
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                /*
              do your validation

              if(something) {
                $value = $value;
              } elseif(something else) {
                $error = true;
                $value = $existing_value;
                
              }
             */

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_sample_config();
    } else {
        echo "The class named Redux_Framework_sample_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            
          }
         */

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;
