(function() {
	tinymce.PluginManager.requireLangPack('shortcodes');
	tinymce.create('tinymce.plugins.shortcodes', {
		init : function(ed, url) {

			ed.addCommand('mce_shortcodes', function() {
				ed.windowManager.open({
					file : url + '/interface.php',
					width : 400 + ed.getLang('shortcodes.delta_width', 0),
					height : 200 + ed.getLang('shortcodes.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			
			ed.addButton('shortcodes', {
				title : 'Insert Shortcodes',
				cmd : 'mce_shortcodes',
				image : url + '/btn.png'
			});

			
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('shortcodes', n.nodeName == 'IMG');
			});
		},
		
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
					longname  : 'shortcodes',
					author 	  : 'turkhitbox',
					authorurl : 'http://www.turkhitbox.com',
					infourl   : 'http://www.turkhitbox.com',
					version   : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('shortcodes', tinymce.plugins.shortcodes);
})();


