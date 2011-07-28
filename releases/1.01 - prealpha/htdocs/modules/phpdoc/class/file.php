<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocFile extends XoopsObject
{

    function PhpdocFile($fid = null)
    {
        $this->initVar('fileid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('projectid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('versionid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('basepathid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('secondpathid', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('thirdpathid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('forthpathid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('weight', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('path', XOBJ_DTYPE_TXTBOX, '', false, 500);
		$this->initVar('filename', XOBJ_DTYPE_TXTBOX, '', false, 255);
		$this->initVar('classes', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('functions', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('imported', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('filetype', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_FILETYPE_OTHER', false, false, false, array('_MI_PHPDOC_FILETYPE_PHP', '_MI_PHPDOC_FILETYPE_CSS', '_MI_PHPDOC_FILETYPE_HTML', '_MI_PHPDOC_FILETYPE_JAVA', '_MI_PHPDOC_FILETYPE_TXT', '_MI_PHPDOC_FILETYPE_IMAGE', '_MI_PHPDOC_FILETYPE_ASSET', '_MI_PHPDOC_FILETYPE_OTHER'));
		$this->initVar('bytes', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('extension', XOBJ_DTYPE_TXTBOX, 'php', false, 20);
		$this->initVar('width', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('height', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('lines', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('filemd5', XOBJ_DTYPE_TXTBOX, md5(false), false, 32);
		$this->initVar('md5', XOBJ_DTYPE_TXTBOX, md5(false), false, 32);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('actioned', XOBJ_DTYPE_INT, 0, false);
	}
	
}


class PhpdocFileHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_files", 'PhpdocFile', "fileid");
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