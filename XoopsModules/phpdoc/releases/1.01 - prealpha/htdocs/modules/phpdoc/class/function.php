<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocFunction extends XoopsObject
{

    function PhpdocFunction($fid = null)
    {
        $this->initVar('functionid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('projectids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('versionids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('classids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('fileids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, '', false, 128);
		$this->initVar('mode', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_MODE_PUBLIC', false, false, false, array('_MI_PHPDOC_MODE_PUBLIC','_MI_PHPDOC_MODE_PRIVATE','_MI_PHPDOC_MODE_PROTECTED'));
		$this->initVar('return', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_TYPE_MIXED', false, false, false, array('_MI_PHPDOC_TYPE_MIXED','_MI_PHPDOC_TYPE_INTEGER','_MI_PHPDOC_TYPE_LONG','_MI_PHPDOC_TYPE_DOUBLE','_MI_PHPDOC_TYPE_FLOAT','_MI_PHPDOC_TYPE_STRING','_MI_PHPDOC_TYPE_ARRAY','_MI_PHPDOC_TYPE_OBJECT','_MI_PHPDOC_TYPE_BOOLEAN'));
		$this->initVar('call', XOBJ_DTYPE_TXTBOX, 0, false, 1500);
		$this->initVar('variables', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('md5ofcode', XOBJ_DTYPE_TXTBOX, md5(false), false, 32);
		$this->initVar('md5', XOBJ_DTYPE_TXTBOX, md5(false), false, 32);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('actioned', XOBJ_DTYPE_INT, 0, false);
	}
	
	function getPluginName() {
		$ret = '';
		if (defined($this->getVar('mode').'_PLUGIN'))
			$ret .= constant($this->getVar('mode').'_PLUGIN');
		if (defined($this->getVar('return').'_PLUGIN'))
			$ret .= constant($this->getVar('return').'_PLUGIN');
		return $ret;				
	}
	
	function runPreInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('mode')) {
			default:
			switch ($this->getVar('return')) {
				default:
					$func = $this->getPluginName().'PreInsertFunction';
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
		
		switch ($this->getVar('mode')) {
			default:
			switch ($this->getVar('return')) {
				default:
					$func = $this->getPluginName().'PostInsertFunction';
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
		
		switch ($this->getVar('mode')) {
			default:
			switch ($this->getVar('return')) {
				default:
					$func = $this->getPluginName().'PostGetFunction';
					break;
			}
		}
		
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
}


class PhpdocFunctionHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_functions", 'PhpdocFunction', "functionid", 'name');
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
    	if ($obj->vars['return']['changed']==true) {	
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