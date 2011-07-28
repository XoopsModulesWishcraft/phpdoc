<?php

	include('../../../mainfile.php');
	include('../../../include/cp_functions.php');
	include('../include/functions.php');	
	include('../include/forms.phpdoc.php');

	$moduleHandler =& xoops_gethandler('module');
	$configHandler =& xoops_gethandler('config');
	$xophpdoc = $moduleHandler->getByDirname('phpdoc');
	if (is_object($xophpdoc))
		$GLOBALS['xoopsModuleConfig'] = $configHandler->getConfigList($xophpdoc->getVar('mid'));
		
?>