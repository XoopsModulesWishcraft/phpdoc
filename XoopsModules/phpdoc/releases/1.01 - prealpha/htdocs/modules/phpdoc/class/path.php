<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocPath extends XoopsObject
{

    function PhpdocPath($fid = null)
    {
        $this->initVar('pathid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('folder', XOBJ_DTYPE_TXTBOX, '', false, 128);
        $this->initVar('path', XOBJ_DTYPE_TXTBOX, '', false, 500);
        $this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('md5', XOBJ_DTYPE_TXTBOX, 0, false, 32);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
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
    	
    	return parent::insert($obj, $force);
    }
     
}
?>