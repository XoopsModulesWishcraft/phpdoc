<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocIndex extends XoopsObject
{

    function PhpdocIndex($fid = null)
    {
        $this->initVar('id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('projectid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('versionid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('fileids', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('classid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('functionid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('status', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_STATUS_STABLE', false, false, false, array('_MI_PHPDOC_STATUS_ALPHA','_MI_PHPDOC_STATUS_BETA','_MI_PHPDOC_STATUS_RC','_MI_PHPDOC_STATUS_STABLE','_MI_PHPDOC_STATUS_MATURE','_MI_PHPDOC_STATUS_EXPERIMENTAL'));
		$this->initVar('mode', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_MODE_PUBLIC', false, false, false, array('_MI_PHPDOC_MODE_PUBLIC','_MI_PHPDOC_MODE_PRIVATE','_MI_PHPDOC_MODE_PROTECTED'));
		$this->initVar('tags', XOBJ_DTYPE_TXTBOX, 0, false, 255);
		$this->initVar('domains', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('visible', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('comments', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('md5', XOBJ_DTYPE_TXTBOX, md5(false), false, 32);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('actioned', XOBJ_DTYPE_INT, 0, false);
	}
	
	function getPluginName() {
		$ret = '';
		if (defined($this->getVar('status').'_PLUGIN'))
			$ret .= constant($this->getVar('status').'_PLUGIN');
		if (defined($this->getVar('mode').'_PLUGIN'))
			$ret .= constant($this->getVar('mode').'_PLUGIN');
		return $ret;				
	}
	
	function runPreInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('status')) {
			default:
			switch ($this->getVar('mode')) {
				default:
					$func = $this->getPluginName().'PreInsertIndex';
					break;
			}
		}
		
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}

	function runPostInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('status')) {
			default:
			switch ($this->getVar('mode')) {
				default:
					$func = $this->getPluginName().'PostInsertIndex';
					break;
			}
		}
		
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
	
	function runPostGetPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('status')) {
			default:
			switch ($this->getVar('mode')) {
				default:
					$func = $this->getPluginName().'PostGetIndex';
					break;
			}
		}
		
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
}


class PhpdocIndexHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_index", 'PhpdocIndex', "id");
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
    	if ($obj->vars['status']['changed']==true) {	
			$obj->setVar('actioned', time());
			$run_plugin = true;
		}
    	if ($obj->vars['mode']['changed']==true) {	
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