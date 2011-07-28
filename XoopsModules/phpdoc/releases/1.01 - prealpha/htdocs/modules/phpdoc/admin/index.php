<?php
	
	include('header.php');
			
	xoops_cp_header();
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"wizard";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"project";
	
	switch($op) {
	case 'savelist':
		xoops_loadLanguage('admin', 'phpdoc');
		$object_handler =& xoops_getmodulehandler($fct, 'phpdoc');
		foreach($_POST['id'] as $id) {
			$object = $object_handler->get($id);
			if (is_object($object)) {
				$object->setVars($_POST[$id]);
				$object_handler->insert($object);
			}
		}	
		
		$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
		$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
		$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
		$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
		$url = $_SERVER["PHP_SELF"].'?op=list&fct='.$fct.'&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort;
		redirect_header($url, 10, _SHOP_AM_MSG_LISTWASSAVEDOKEY);
		exit(0);
		break;

	case 'save':
		xoops_loadLanguage('admin', 'phpdoc');		
		$object_handler =& xoops_getmodulehandler($fct, 'phpdoc');
		$object = $object_handler->get($_POST['id']);
		if (is_object($object)) {
			$object->setVars($_POST);
			$object_handler->insert($object);
		}
		$url = $_SERVER["PHP_SELF"].'?op=list&fct='.$fct;
		redirect_header($url, 10, _SHOP_AM_MSG_ITEMWASSAVEDOKEY);
		exit(0);
		break;

	case 'delete':
		xoops_loadLanguage('admin', 'phpdoc');		
		if (!isset($_POST['confirmed'])) {
			xoops_confirm(array('id'=>$_GET['id'], 'op'=>'delete', 'fct'=>$fct, 'confirm'=>true), $_SERVER['PHP_SELF'], _SHOP_AM_MSG_DELETEITEM, _SUBMIT);
		} else {
			$object_handler =& xoops_getmodulehandler($fct, 'phpdoc');
			$object = $object_handler->get($_POST['id']);
			if (is_object($object)) {
				$object_handler->delete($object);
			}
			$url = $_SERVER["PHP_SELF"].'?op=list&fct='.$fct;
			redirect_header($url, 10, _SHOP_AM_MSG_ITEMWASDELETED);
			exit(0);
		}
		break;

	case 'edit':
		xoops_loadLanguage('admin', 'phpdoc');

		include_once $GLOBALS['xoops']->path( "/class/template.php" );
		$GLOBALS['phpdocTpl'] = new XoopsTpl();

		$GLOBALS['phpdocTpl']->assign('php_self', $_SERVER['PHP_SELF']);
		
		$object_handler =& xoops_getmodulehandler($fct, 'phpdoc');
		$object = $object_handler->get($_GET['id']);
		if (is_object($object)) {
			$GLOBALS['phpdocTpl']->assign('form', $object->getForm($_SERVER['QUERY_STRING']));
		}

		$GLOBALS['phpdocTpl']->display('db:phpdoc_cpanel_edit.html');
		break;		
				
	default:
	case 'lists':
		switch ($fct) {
			default:
			case 'category':
				xoops_loadLanguage('admin', 'phpdoc');
				phpdoc_adminMenu(2);
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				include_once $GLOBALS['xoops']->path( "/class/template.php" );
				$GLOBALS['phpdocTpl'] = new XoopsTpl();
				
				$category_handler =& xoops_getmodulehandler('category', 'phpdoc');
					
				$ttl = $category_handler->getCount(NULL);
				$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
				$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
				$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op);
				$GLOBALS['phpdocTpl']->assign('pagenav', $pagenav->renderNav());

				$GLOBALS['phpdocTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['phpdocTpl']->assign('limit', $limit);
				$GLOBALS['phpdocTpl']->assign('start', $start);
				$GLOBALS['phpdocTpl']->assign('order', $order);
				$GLOBALS['phpdocTpl']->assign('sort', $sort);
				$GLOBALS['phpdocTpl']->assign('op', $op);
				$GLOBALS['phpdocTpl']->assign('fct', $fct);
				
				foreach (array(	'cid','parentid','weight','itemid','md5', 'created', 'updated') as $id => $key) {
					$GLOBALS['phpdocTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'">'.(defined('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
				}
					
				$criteria = new Criteria('1','1');
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
					
				$categories = $category_handler->getObjects($criteria, true);
				foreach($categories as $id => $category) {
					$GLOBALS['phpdocTpl']->append('categories', $category->toArray());
				}
						
				$GLOBALS['phpdocTpl']->display('db:phpdoc_cpanel_category_list.html');
				break;		
			
			case 'project':

				xoops_loadLanguage('admin', 'phpdoc');
				phpdoc_adminMenu(3);
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				include_once $GLOBALS['xoops']->path( "/class/template.php" );
				$GLOBALS['phpdocTpl'] = new XoopsTpl();
				
				$project_handler =& xoops_getmodulehandler('project', 'phpdoc');
					
				$ttl = $project_handler->getCount(NULL);
				$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
				$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
				$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op);
				$GLOBALS['phpdocTpl']->assign('pagenav', $pagenav->renderNav());

				$GLOBALS['phpdocTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['phpdocTpl']->assign('limit', $limit);
				$GLOBALS['phpdocTpl']->assign('start', $start);
				$GLOBALS['phpdocTpl']->assign('order', $order);
				$GLOBALS['phpdocTpl']->assign('sort', $sort);
				$GLOBALS['phpdocTpl']->assign('op', $op);
				$GLOBALS['phpdocTpl']->assign('fct', $fct);
				
				foreach (array(	'projectid','parentid','itemid','weight','status','url','md5', 'folder', 'created', 'updated', 'actioned') as $id => $key) {
					$GLOBALS['phpdocTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'">'.(defined('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
				}
					
				$criteria = new Criteria('1','1');
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
					
				$projects = $project_handler->getObjects($criteria, true);
				foreach($projects as $id => $project) {
					$GLOBALS['phpdocTpl']->append('projects', $project->toArray());
				}
						
				$GLOBALS['phpdocTpl']->display('db:phpdoc_cpanel_project_list.html');
				break;		

			case 'file':

				xoops_loadLanguage('admin', 'phpdoc');
				phpdoc_adminMenu(4);
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				include_once $GLOBALS['xoops']->path( "/class/template.php" );
				$GLOBALS['phpdocTpl'] = new XoopsTpl();
				
				$file_handler =& xoops_getmodulehandler('file', 'phpdoc');
					
				$ttl = $file_handler->getCount(NULL);
				$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
				$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
				$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op);
				$GLOBALS['phpdocTpl']->assign('pagenav', $pagenav->renderNav());

				$GLOBALS['phpdocTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['phpdocTpl']->assign('limit', $limit);
				$GLOBALS['phpdocTpl']->assign('start', $start);
				$GLOBALS['phpdocTpl']->assign('order', $order);
				$GLOBALS['phpdocTpl']->assign('sort', $sort);
				$GLOBALS['phpdocTpl']->assign('op', $op);
				$GLOBALS['phpdocTpl']->assign('fct', $fct);
				
				foreach (array(	'fileid','cid','projectid','versionid','basepathid','secondpathid','thirdpathid', 'forthpathid', 
								'itemid', 'weight', 'path', 'filename', 'classes', 'functions', 'filemd5', 'md5', 'created', 'updated',
								'imported', 'filetype', 'bytes', 'extension', 'width', 'height', 'actioned') as $id => $key) {
					$GLOBALS['phpdocTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'">'.(defined('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
				}
					
				$criteria = new Criteria('1','1');
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
					
				$files = $file_handler->getObjects($criteria, true);
				foreach($files as $id => $file) {
					$GLOBALS['phpdocTpl']->append('files', $file->toArray());
				}
						
				$GLOBALS['phpdocTpl']->display('db:phpdoc_cpanel_file_list.html');
				break;

			case 'class':

				xoops_loadLanguage('admin', 'phpdoc');
				phpdoc_adminMenu(5);
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				include_once $GLOBALS['xoops']->path( "/class/template.php" );
				$GLOBALS['phpdocTpl'] = new XoopsTpl();
				
				$class_handler =& xoops_getmodulehandler('class', 'phpdoc');
					
				$ttl = $class_handler->getCount(NULL);
				$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
				$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
				$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';

				$GLOBALS['phpdocTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['phpdocTpl']->assign('limit', $limit);
				$GLOBALS['phpdocTpl']->assign('start', $start);
				$GLOBALS['phpdocTpl']->assign('order', $order);
				$GLOBALS['phpdocTpl']->assign('sort', $sort);
				$GLOBALS['phpdocTpl']->assign('op', $op);
				$GLOBALS['phpdocTpl']->assign('fct', $fct);
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op);
				$GLOBALS['phpdocTpl']->assign('pagenav', $pagenav->renderNav());
		
				foreach (array(	'classid','cids','projectids','versionids','fileids','weight','name', 'extends', 'extendsclassid',
								'functions', 'variables', 'md5', 'created', 'updated') as $id => $key) {
					$GLOBALS['phpdocTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'">'.(defined('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
				}
					
				$criteria = new Criteria('1','1');
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
					
				$classs = $class_handler->getObjects($criteria, true);
				foreach($classs as $id => $class) {
					$GLOBALS['phpdocTpl']->append('classes', $class->toArray());
				}
						
				$GLOBALS['phpdocTpl']->display('db:phpdoc_cpanel_class_list.html');
				break;				

			case 'function':

				xoops_loadLanguage('admin', 'phpdoc');
				phpdoc_adminMenu(6);
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				include_once $GLOBALS['xoops']->path( "/class/template.php" );
				$GLOBALS['phpdocTpl'] = new XoopsTpl();
				
				$function_handler =& xoops_getmodulehandler('function', 'phpdoc');
					
				$ttl = $function_handler->getCount(NULL);
				$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
				$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
				$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op);
				$GLOBALS['phpdocTpl']->assign('pagenav', $pagenav->renderNav());

				$GLOBALS['phpdocTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['phpdocTpl']->assign('limit', $limit);
				$GLOBALS['phpdocTpl']->assign('start', $start);
				$GLOBALS['phpdocTpl']->assign('order', $order);
				$GLOBALS['phpdocTpl']->assign('sort', $sort);
				$GLOBALS['phpdocTpl']->assign('op', $op);
				$GLOBALS['phpdocTpl']->assign('fct', $fct);
				
				foreach (array(	'functionid','cids','projectids','versionids','classids','fileids','itemid',
								'weight', 'name', 'mode','return', 'call', 'variables', 'md5', 
								'created', 'updated', 'actioned') as $id => $key) {
					$GLOBALS['phpdocTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'">'.(defined('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
				}
					
				$criteria = new Criteria('1','1');
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
					
				$functions = $function_handler->getObjects($criteria, true);
				foreach($functions as $id => $function) {
					$GLOBALS['phpdocTpl']->append('functions', $function->toArray());
				}
						
				$GLOBALS['phpdocTpl']->display('db:phpdoc_cpanel_function_list.html');
				break;				

			case 'variable':

				xoops_loadLanguage('admin', 'phpdoc');
				phpdoc_adminMenu(7);
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				include_once $GLOBALS['xoops']->path( "/class/template.php" );
				$GLOBALS['phpdocTpl'] = new XoopsTpl();
				
				$variable_handler =& xoops_getmodulehandler('variable', 'phpdoc');
					
				$ttl = $variable_handler->getCount(NULL);
				$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
				$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
				$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op);
				$GLOBALS['phpdocTpl']->assign('pagenav', $pagenav->renderNav());

				$GLOBALS['phpdocTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['phpdocTpl']->assign('limit', $limit);
				$GLOBALS['phpdocTpl']->assign('start', $start);
				$GLOBALS['phpdocTpl']->assign('order', $order);
				$GLOBALS['phpdocTpl']->assign('sort', $sort);
				$GLOBALS['phpdocTpl']->assign('op', $op);
				$GLOBALS['phpdocTpl']->assign('fct', $fct);
				
				foreach (array(	'variableid','cids','projectids','versionids','classids','functionids','fileids', 
								'itemid', 'weight', 'name', 'default', 'type', 'md5', 'created', 'updated', 'actioned') as $id => $key) {
					$GLOBALS['phpdocTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'">'.(defined('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
				}
					
				$criteria = new Criteria('1','1');
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
					
				$variables = $variable_handler->getObjects($criteria, true);
				foreach($variables as $id => $variable) {
					$GLOBALS['phpdocTpl']->append('variables', $variable->toArray());
				}
						
				$GLOBALS['phpdocTpl']->display('db:phpdoc_cpanel_variable_list.html');
				break;
				
			case 'path':

				xoops_loadLanguage('admin', 'phpdoc');
				phpdoc_adminMenu(8);
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				include_once $GLOBALS['xoops']->path( "/class/template.php" );
				$GLOBALS['phpdocTpl'] = new XoopsTpl();
				
				$path_handler =& xoops_getmodulehandler('path', 'phpdoc');
					
				$ttl = $path_handler->getCount(NULL);
				$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
				$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
				$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op);
				$GLOBALS['phpdocTpl']->assign('pagenav', $pagenav->renderNav());

				$GLOBALS['phpdocTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['phpdocTpl']->assign('limit', $limit);
				$GLOBALS['phpdocTpl']->assign('start', $start);
				$GLOBALS['phpdocTpl']->assign('order', $order);
				$GLOBALS['phpdocTpl']->assign('sort', $sort);
				$GLOBALS['phpdocTpl']->assign('op', $op);
				$GLOBALS['phpdocTpl']->assign('fct', $fct);
				
				foreach (array(	'pathid','folder','path','relative','itemid','md5','created','updated','actioned') as $id => $key) {
					$GLOBALS['phpdocTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'">'.(defined('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
				}
					
				$criteria = new Criteria('1','1');
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
					
				$paths = $path_handler->getObjects($criteria, true);
				foreach($paths as $id => $path) {
					$GLOBALS['phpdocTpl']->append('paths', $path->toArray());
				}
						
				$GLOBALS['phpdocTpl']->display('db:phpdoc_cpanel_path_list.html');
				break;			
			case 'item_digest':

				xoops_loadLanguage('admin', 'phpdoc');
				phpdoc_adminMenu(9);
				
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				include_once $GLOBALS['xoops']->path( "/class/template.php" );
				$GLOBALS['phpdocTpl'] = new XoopsTpl();
				
				$item_digest_handler =& xoops_getmodulehandler('item_digest', 'phpdoc');
					
				$ttl = $item_digest_handler->getCount(NULL);
				$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
				$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
				$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
				$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
				
				$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op);
				$GLOBALS['phpdocTpl']->assign('pagenav', $pagenav->renderNav());

				$GLOBALS['phpdocTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['phpdocTpl']->assign('limit', $limit);
				$GLOBALS['phpdocTpl']->assign('start', $start);
				$GLOBALS['phpdocTpl']->assign('order', $order);
				$GLOBALS['phpdocTpl']->assign('sort', $sort);
				$GLOBALS['phpdocTpl']->assign('op', $op);
				$GLOBALS['phpdocTpl']->assign('fct', $fct);
				
				foreach (array(	'itemdigestid','itemid','cid','projectid','versionid','fileid', 
								'menu', 'title', 'description', 'functionid', 'pathid', 'language',
								'md5', 'created', 'updated') as $id => $key) {
					$GLOBALS['phpdocTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'">'.(defined('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_PHPDOC_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
				}
					
				$criteria = new Criteria('1','1');
				$criteria->setStart($start);
				$criteria->setLimit($limit);
				$criteria->setSort('`'.$sort.'`');
				$criteria->setOrder($order);
					
				$item_digests = $item_digest_handler->getObjects($criteria, true);
				foreach($item_digests as $id => $item_digest) {
					$GLOBALS['phpdocTpl']->append('item_digests', $item_digest->toArray());
				}
						
				$GLOBALS['phpdocTpl']->display('db:phpdoc_cpanel_item_digest_list.html');
				break;	

			case 'permissions':

				xoops_loadLanguage('admin', 'phpdoc');
				phpdoc_adminMenu(10);
				
				break;
		}																				
		break;
		
	}
	
	phpdoc_footer_adminMenu();
	xoops_cp_footer();
?>