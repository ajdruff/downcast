<?php 
	

	include("lib/downcast/Downcast.php");
        include("lib/downcast/DowncastPlugin.php");
        $io = new Downcast(false);

      
      $io->doActionHooks('_dc_before_template');
      



	$io->renderTemplate("templates/" . strtolower($io->CONFIG['SITE']['CONFIG']['TEMPLATE']) ."/index.php");
$io->doActionHooks('_dc_after_template');
$io->doActionHooks('_dc_controller_end');

	?>