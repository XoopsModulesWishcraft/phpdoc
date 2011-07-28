<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocPath extends XoopsObject
{

    function PhpdocPath($fid = null)
    {
        $this->initVar('pathid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('parentid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('projectid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('fileid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('folder', XOBJ_DTYPE_TXTBOX, '', false, 128);
        $this->initVar('path', XOBJ_DTYPE_TXTBOX, '', false, 500);
        $this->initVar('relative', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_PROJECTS_PATH', false, false, false, array('_MI_PHPDOC_XOOPS_ROOT_PATH','_MI_PHPDOC_XOOPS_UPLOAD_PATH','_MI_PHPDOC_XOOPS_VAR_PATH','_MI_PHPDOC_XOOPS_TRUST_PATH','_MI_PHPDOC_XOOPS_MODULE_PATH','_MI_PHPDOC_PROJECTS_PATH','_MI_PHPDOC_OPEN_PATH'));
        $this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('md5', XOBJ_DTYPE_TXTBOX, 0, false, 32);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('actioned', XOBJ_DTYPE_INT, 0, false);
	}
	
	function toArray()
	{
		$ret = parent::toArray();
		$ret['when'] = get_when_associative($this);
		$frm = $this->getForm($_SERVER['QUERY_STRING'], false, array());
		foreach($frm as $key => $value) { 
			if ($key!='required') {
				$ret['forms'][$key] = $frm[$key]->render();
			}
		}
		return $ret;
	}
	
	function getForm($querystring = '', $caption = true, $frm = false, $render=true) {
		if ($frm==false)
			$frm=array();

		$frm['required'][] = 'folder';
		$frm['required'][] = 'path';
		
		xoops_loadLanguage('forms', 'phpdoc');
		$item_digest_handler = xoops_getmodulehandler('item_digest', 'phpdoc');
		$id = $this->getVar('pathid');
		
		switch ($caption){
			case true:
				$frm['pathid'] = new XoopsFormHidden('id['.$id.']', 'path');
				$frm['projectid'] = new XoopsFormSelectProject(_FRM_PHPDOC_PROJECT, $id.'[projectid]', $this->getVar('projectid'), 1, false, true);
				$frm['fileid'] = new XoopsFormSelectFile(_FRM_PHPDOC_FILE, $id.'[fileid]', $this->getVar('fileid'), 1, false, true);
				$frm['folder'] = new XoopsFormText(_FRM_PHPDOC_FOLDER, $id.'[folder]', 35, 128, $this->getVar('folder'));
				$frm['path'] = new XoopsFormText(_FRM_PHPDOC_PATH, $id.'[path]', 35, 500, $this->getVar('path'));
				$frm['relative'] = new XoopsFormSelectRelative(_FRM_PHPDOC_RELATIVE, $id.'[relative]', $this->getVar('relative'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);			
				if ($render==false)
					return $frm;
				break;
			case false:
				$frm['pathid'] = new XoopsFormHidden('id['.$id.']', 'path');
				$frm['projectid'] = new XoopsFormSelectProject('', $id.'[projectid]', $this->getVar('projectid'), 1, false, true);
				$frm['fileid'] = new XoopsFormSelectFile('', $id.'[fileid]', $this->getVar('fileid'), 1, false, true);
				$frm['folder'] = new XoopsFormText('', $id.'[folder]', 35, 128, $this->getVar('folder'));
				$frm['path'] = new XoopsFormText('', $id.'[path]', 35, 500, $this->getVar('path'));
				$frm['relative'] = new XoopsFormSelectRelative('', $id.'[relative]', $this->getVar('relative'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);			
				return $frm;			
				break;				
		}

	    if ($this->isNew()) {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_NEW_PATH, 'path', $_SERVER['PHP_SELF'], 'post');
    	} else {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_EDIT_PATH, 'path', $_SERVER['PHP_SELF'], 'post');
    	}
    	
    	foreach($frm as $key => $value) {
    		if ($key!='required') {
	    		if (!in_array($field, $frm['required'])) {
	    			$form->addElement($frm[$key], false);
	    		} else {
	    			$form->addElement($frm[$key], true);
	    		}
    		}
    	}
		
    	return $form->render();
	}
	
	function getPluginName() {
		$ret = '';
		if (defined($this->getVar('relative').'_PLUGIN'))
			$ret .= constant($this->getVar('relative').'_PLUGIN');
		return $ret;				
	}
	
	function runPreInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('relative')) {
			default:
				$func = $this->getPluginName().'PreInsertPath';
				break;
		}
		
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}

	function runPostInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('relative')) {
			default:
				$func = $this->getPluginName().'PostInsertPath';
				break;
		}
				
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
	
	function runPostGetPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('relative')) {
			default:
				$func = $this->getPluginName().'PostGetPath';
				break;
		}
				
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
}


class PhpdocPathHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_paths", 'PhpdocPath', "pathid");
    }
    
    function insert($obj, $force=true) {
    	
    	foreach($obj->_vars as $key => $value)
	    	if (!in_array($key, array('created', 'updated', 'actioned', 'md5')))
	    		if (substr($key, strlen($key)-3, 3)!='ids')
	    			$md5 .= md5($md5.$value['value'].$value['name']);
    	
    	if ($obj->isNew()) {
    		$obj->setVar('created', time());	
	    	$criteria = new CriteriaCompo(new Criteria('md5', $md5));
	    	if (parent::getCount($criteria)>0) {
	    		$objs = parent::getObjects($criteria, false);
	    		if (is_object($objs[0])) {
	    			return $objs[0]->getVar($this->handler->keyName);
	    		}
	    	} else {
	    		$obj->setVar('md5', $md5);
	    	}
    	} else {
    		$obj->setVar('updated', time());
    		$obj->setVar('md5', $md5);
    	}
    	
    	$run_plugin = false;
    	if ($obj->vars['relative']['changed']==true) {	
			$obj->setVar('actioned', time());
			$run_plugin = true;
		}
    	if ($run_plugin==true) {
    		@$obj->runPreInsertPlugin($this);
    	}
		$id = parent::insert($obj, $force);
    	if ($run_plugin==true) {
    		$obj = parent::get($id);
    		@$obj->runPostInsertPlugin($this);
    	}
    	return $id;
    }
     
    function get($id) 
    {
    	$obj = parent::get($id);
    	if (is_object($obj)) {
    		return @$obj->runPostGetPlugin($this);
    	}
    }
    
	function getObjects($criteria, $id_as_key=false, $as_object=true) 
    {
    	$objs = parent::getObjects($criteria, $id_as_key, $as_object);
    	if ($as_object==true) {
    		foreach($objs as $key => $obj)
    			$objs[$key] = @$obj->runPostGetPlugin($this);
    	}
    	return $objs;
    } 
     
}
?>