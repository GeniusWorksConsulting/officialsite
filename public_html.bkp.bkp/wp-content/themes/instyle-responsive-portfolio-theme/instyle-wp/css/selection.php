<?php require_once( '../../../../wp-load.php' );
	Header("Content-type: text/css");
	
	$bodytextcolor =  get_option_tree ('bodytextcolor', '#8e9194');
	
	$generallink =  get_option_tree ('generallink', '#666');
	$generallinkhover =  get_option_tree ('generallinkhover', '#444');
	$sidebarlink =  get_option_tree ('sidebarlink', '#666');
	$sidebarlinkhover =  get_option_tree ('sidebarlinkhover', '#444');
	$menulink =  get_option_tree ('menulink', '#666');
	$menulinkhover =  get_option_tree ('menulinkhover', '#444');
	
	$menufont =  get_option_tree ('menufont', 'Helvetica Neue');
	$bodyfont =  get_option_tree ('bodyfont', 'Helvetica Neue');
	$titlefont =  get_option_tree ('titlefont', 'Helvetica Neue');
	
	function google_webfont($font) {
		
		$fontbase = 'http://fonts.googleapis.com/css?family=';
		$import='';	
		
		if($font=='Droid Sans'){ $import = "@import \"".$fontbase."Droid+Sans:regular,bold\";";}
		if($font=='Droid Serif'){ $import = "@import \"".$fontbase."Droid+Serif:regular,italic,bold,bolditalic\";"; }
		if($font=='Arvo'){ $import = "@import \"".$fontbase."Arvo:regular,italic,bold,bolditalic\";"; }
		if($font=='Lobster'){ $import = "@import \"".$fontbase."Lobster \";"; }
		if($font=='Lobster Two'){ $import = "@import \"".$fontbase."Lobster+Two:400,400italic,700,700italic&v2\";"; }
		if($font=='Vibur'){ $import = "@import \"".$fontbase."Vibur\";"; }
		if($font=='Six Caps'){ $import = "@import \"".$fontbase."Six+Caps\";"; }
		if($font=='Terminal Dosis Light'){ $import = "@import \"".$fontbase."Terminal+Dosis+Light\";"; }
		if($font=='Michroma'){ $import = "@import \"".$fontbase."Michroma\";"; }
		if($font=='Cabin Sketch'){ $import = "@import \"".$fontbase."Cabin+Sketch:bold\";"; }
		if($font=='Oswald'){ $import = "@import \"".$fontbase."Oswald\";"; }
		if($font=='Bevan'){ $import = "@import \"".$fontbase."Bevan\";"; }
		if($font=='Anonymous Pro'){ $import = "@import \"".$fontbase."Anonymous+Pro:regular,italic,bold,bolditalic\";"; }
		if($font=='Expletus Sans'){ $import = "@import \"".$fontbase."Expletus+Sans:regular,500,600,bold\";"; }
		if($font=='Amaranth'){ $import = "@import \"".$fontbase."Amaranth\";"; }
		if($font=='Philosopher'){ $import = "@import \"".$fontbase."Philosopher\";"; }
		if($font=='Quattrocento'){ $import = "@import \"".$fontbase."Quattrocento\";"; }
		if($font=='Radley'){ $import = "@import \"".$fontbase."Radley\";"; }
		if($font=='Merriweather'){ $import = "@import \"".$fontbase."Merriweather\";"; }
		if($font=='Cabin'){ $import = "@import \"".$fontbase."Cabin:regular,regularitalic,500,500italic,600,600italic,bold,bolditalic\";"; }
		if($font=='Cherry Cream Soda'){ $import = "@import \"".$fontbase."Cherry+Cream+Soda\";"; }
		if($font=='Play'){ $import = "@import \"".$fontbase."Play:regular,bold\";"; }
		if($font=='PT Sans'){ $import = "@import \"".$fontbase."PT+Sans:regular,italic,bold,bolditalic\";"; }
		if($font=='Crafty Girls"'){ $import = "@import \"".$fontbase."Crafty+Girls\";"; }
		if($font=='Pacifico'){ $import = "@import \"".$fontbase."Pacifico\";"; }
		if($font=='PT Serif'){ $import = "@import \"".$fontbase."PT+Serif:regular,italic,bold,bolditalic\";"; }
		if($font=='PT Serif Caption'){ $import = "@import \"".$fontbase."PT+Serif+Caption:regular,italic\";"; }	
		if($font=='Maven Pro'){ $import = "@import \"".$fontbase."Maven+Pro:regular,bold\";"; }	
		if($font=='Varela'){ $import = "@import \"".$fontbase."Varela&v2\";"; }	
		if($font=='Muli'){ $import = "@import \"".$fontbase."Muli:400,400italic&v2\";"; }
		if($font=='Tenor Sans'){ $import = "@import \"".$fontbase."Tenor+Sans&v2\";"; }
		if($font=='Open Sans'){ $import = "@import \"".$fontbase."Open+Sans:400,400italic,600\";"; }
		if($font=='Terminal Dosis'){ $import = "@import \"".$fontbase."Terminal+Dosis:400,700\";"; }
		if($font=='Istok Web'){ $import = "@import \"".$fontbase."Istok+Web:400,700\";"; }
		
		return $import;
	}
echo google_webfont($menufont) . "\n";
echo google_webfont($bodyfont) . "\n";
echo google_webfont($titlefont) . "\n";
?>

/* Options set in the admin page */
body { font-family: "<?php echo $bodyfont; ?>", Helvetica, Arial, sans-serif; color: <?php echo $bodytextcolor; ?>;}

header nav a { font-family: "<?php echo $menufont; ?>", Helvetica, Arial, sans-serif; }
h1, h2, h3, h4, h5, h6 { font-family: "<?php echo $titlefont; ?>", Helvetica, Arial, sans-serif; }

/* Colors set in the Admin Panel */
a { color: <?php echo $generallink; ?>}; }
a:hover { color: <?php echo $generallinkhover; ?>; }
.sidebar a, .postmeta a { color: <?php echo $sidebarlink; ?>; }
.sidebar a:hover, .postmeta a:hover { color: <?php echo $sidebarlinkhover; ?>; }
header nav a { color: <?php echo $menulink; ?>; }
header nav a:hover { color: <?php echo $menulinkhover; ?>; }