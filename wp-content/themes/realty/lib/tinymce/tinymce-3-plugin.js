(function () {
	// create tt_shortcodes plugin
	tinymce.create("tinymce.plugins.tt_mce_button",
	{
		init: function ( ed, url )
		{

		},
		createControl: function ( btn, e )
		{
			if ( btn == "tt_mce_button" ) {	
			
				var a = this;

				var btn = e.createSplitButton('tt_mce_button', {
	      	//title: 'Real Estate Shortcodes',
					image: abspath.template_url + '/lib/tinymce/tinymce-icon.png',
					icons: false
        });

        btn.onRenderMenu.add(function (c, b) {	
				
					a.addImmediate( b, 'Section Title', '[section_title heading="h2"]My Section Title[/section_title]' );
					a.addImmediate( b, 'Testimonials', '[testimonials columns="2"]' );
					a.addImmediate( b, 'Agents', '[agents columns="2"]' );
					a.addImmediate( b, 'Single Property', '[single_property id="1"]' );
					a.addImmediate( b, 'Featured Properties', '[featured_properties columns="2"]' );
					a.addImmediate( b, 'Property Listing', '[property_listing columns="3" per_page="10" location="london" status="rent" type="apartment" features="balcony" max_price="3000" min_rooms="3" available_from="20131231"]' );
					a.addImmediate( b, 'Property Search Form', '[property_search_form]' );
					a.addImmediate( b, 'Map', '[map address="London, UK" zoomlevel="14" height="500px"]' );
					a.addImmediate( b, 'Latest Posts', '[latest_posts posts="3" columns="2"]' );
					
				});
                
        return btn;
        
			}
			
			return null;
		},
		addImmediate: function ( ed, title, sc) {
			ed.add({
				title: title,
				onclick: function () {
					tinyMCE.activeEditor.execCommand( "mceInsertContent", false, sc )
				}
			})
		},
		getInfo: function () {
			return {
				longname 	: "ThemeTrail",
        author 		: 'ThemeTrail',
        authorurl : 'http://themetrail.com',
        infourl 	: 'http://tinymce.com/wiki.php',
        version 	: "1.0"
			}
		}
	});
	
	// Register plugin
	tinymce.PluginManager.add("tt_mce_button", tinymce.plugins.tt_mce_button);
})();