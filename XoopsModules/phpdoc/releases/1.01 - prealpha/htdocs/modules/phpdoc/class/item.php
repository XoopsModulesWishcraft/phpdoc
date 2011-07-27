<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocItem extends XoopsObject
{

    function PhpdocItem($fid = null)
    {
        $this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('type', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_TYPE_OTHER', false, false, false, array('_MI_PHPDOC_TYPE_CATEGORY','_MI_PHPDOC_TYPE_VERSION','_MI_PHPDOC_TYPE_PROJECT','_MI_PHPDOC_TYPE_PATH','_MI_PHPDOC_TYPE_FILE','_MI_PHPDOC_TYPE_FUNCTION','_MI_PHPDOC_TYPE_CLASS','_MI_PHPDOC_TYPE_VARIABLE','_MI_PHPDOC_TYPE_OTHER'));
        $this->initVar('languages', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('default', XOBJ_DTYPE_TXTBOX, 0, false, 128);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('actioned', XOBJ_DTYPE_INT, 0, false);
	}

	function getPluginName() {
		$ret = '';
		if (defined($this->getVar('type').'_PLUGIN'))
			$ret .= constant($this->getVar('type').'_PLUGIN');
		return $ret;				
	}
	
	function runPreInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('type')) {
			default:
				$func = $this->getPluginName().'PreInsertItem';
				break;
		}
		
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}

	function runPostInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('type')) {
			default:
				$func = $this->getPluginName().'PostInsertItem';
				break;
		}
				
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
	
	function runPostGetPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('type')) {
			default:
				$func = $this->getPluginName().'PostGetItem';
				break;
		}
				
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
}


class PhpdocItemHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_items", 'PhpdocItem', "itemid");
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
    	if ($obj->vars['type']['changed']==true) {	
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