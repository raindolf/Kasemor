//http://www.wpexplorer.com/wordpress-tinymce-tweaks/
(function() {
	tinymce.PluginManager.add('tt_mce_button', function( editor, url ) {
		editor.addButton( 'tt_mce_button', {
			//text: 'Real Estate Shortcodes',
			type: 'menubutton',
			icon: 'themetrail',
			menu: [
				
				{
					text: 'Section Title',
					onclick: function() {
								editor.insertContent('[section_title heading="h2"]My Section Title[/section_title]');
							}
				},
				
				{
					text: 'Testimonials',
					onclick: function() {
								editor.insertContent('[testimonials columns="2"]');
							}
				},
				
				{
					text: 'Agents',
					onclick: function() {
								editor.insertContent('[agents columns="2"]');
							}
				},
				
				{
					text: 'Single Property',
					onclick: function() {
								editor.insertContent('[single_property id="1"]');
							}
				},
				
				{
					text: 'Featured Properties',
					onclick: function() {
								editor.insertContent('[featured_properties columns="2"]');
							}
				},
				
				{
					text: 'Property Listing',
					onclick: function() {
								editor.insertContent('[property_listing columns="3" per_page="10" location="london" status="rent" type="apartment" features="balcony" max_price="3000" min_rooms="3" available_from="20131231"]');
							}
				},
				
				{
					text: 'Property Search Form',
					onclick: function() {
								editor.insertContent('[property_search_form]');
							}
				},
				
				{
					text: 'Map',
					onclick: function() {
								editor.insertContent('[map address="London, UK" zoomlevel="14" height="500px"]');
							}
				},
				
				{
					text: 'Latest Posts',
					onclick: function() {
								editor.insertContent('[latest_posts posts="3" columns="2"]');
							}
				},
				
				/* Menu Item + Sub Menu
				{
					text: 'Button',
					menu: [
						{
							text: 'Button Default',
							onclick: function() {
								editor.insertContent('[button url="#" type="default"]Default Button Text[/button]');
							}
						},
						{
							text: 'Button Primary',
							onclick: function() {
								editor.insertContent('[button url="#" type="primary"]Primary Button Text[/button]');
							}
						},
					]
				},
				*/
				
			]
		});
	});
})();