<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocClass extends XoopsObject
{

    function PhpdocClass($fid = null)
    {
        $this->initVar('classid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('projectids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('versionids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('fileids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('weight', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, '', false, 128);
		$this->initVar('extends', XOBJ_DTYPE_TXTBOX, '', false, 128);
		$this->initVar('extendsclassid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('functions', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('variables', XOBJ_DTYPE_INT, 0, false);	
		$this->initVar('md5', XOBJ_DTYPE_TXTBOX, md5(false), false, 32);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
	}
	
	function toArray()
	{
		$ret = parent::toArray();
		$ret['when'] = get_when_associative($this);
		$frm = $this->getForm($_SERVER['QUERY_STRING'], false, array());
		foreach($frm as $key => $value) { 
			if ($key!='required') {
				$ret['forms'][$key] = $value->render();
			}
		}
		return $ret;
	}
	
	function getForm($querystring = '', $caption = true, $frm = false, $render = true) {
		if ($frm==false)
			$frm=array();
		
		$frm['required'][] = 'name';
		$frm['required'][] = 'weight';
		
		xoops_loadLanguage('forms', 'phpdoc');
		$item_digest_handler = xoops_getmodulehandler('item_digest', 'phpdoc');
		$id = $this->getVar('classid');
		
		switch ($caption){
			case true:
				$frm['classid'] = new XoopsFormHidden('id['.$id.']', 'class');
				$frm['cids'] = new XoopsFormSelectCategory(_FRM_PHPDOC_CATEGORIES, $id.'[cids]', $this->getVar('cids'), 7, true);
				$frm['projectids'] = new XoopsFormSelectProject(_FRM_PHPDOC_PROJECTS, $id.'[projectids]', $this->getVar('projectids'), 7, true);
				$frm['versionids'] = new XoopsFormSelectVersion(_FRM_PHPDOC_VERSIONS, $id.'[versionids]', $this->getVar('versionids'), 7, true);
				$frm['fileids'] = new XoopsFormSelectFile(_FRM_PHPDOC_FILES, $id.'[fileids]', $this->getVar('fileids'), 5, true);
				$frm['weight'] = new XoopsFormText(_FRM_PHPDOC_WEIGHT, $id.'[weight]', 5, 10, $this->getVar('weight'));
				$frm['name'] = new XoopsFormText(_FRM_PHPDOC_CLASSNAME, $id.'[name]', 35, 128, $this->getVar('name'));
				$frm['extends'] = new XoopsFormText(_FRM_PHPDOC_EXTENDSCLASS, $id.'[extends]', 35, 128, $this->getVar('extends'));
				if ($render==false)
					return $frm;
				break;
			case false:
				$frm['classid'] = new XoopsFormHidden('id['.$id.']', 'class');
				$frm['cids'] = new XoopsFormSelectCategory('', $id.'[cids]', $this->getVar('cids'), 5, true);
				$frm['projectids'] = new XoopsFormSelectProject('', $id.'[projectids]', $this->getVar('projectids'), 5, true);
				$frm['versionids'] = new XoopsFormSelectVersion('', $id.'[versionids]', $this->getVar('versionids'), 5, true);
				$frm['fileids'] = new XoopsFormSelectFile('', $id.'[fileids]', $this->getVar('fileids'), 5, true);
				$frm['weight'] = new XoopsFormText('', $id.'[weight]', 5, 10, $this->getVar('weight'));
				$frm['name'] = new XoopsFormText('', $id.'[name]', 35, 128, $this->getVar('name'));
				$frm['extends'] = new XoopsFormText('', $id.'[extends]', 35, 128, $this->getVar('extends'));
				return $frm;			
				break;				
		}

	    if ($this->isNew()) {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_NEW_CLASS, 'class', $_SERVER['PHP_SELF'], 'post');
    	} else {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_EDIT_CLASS, 'class', $_SERVER['PHP_SELF'], 'post');
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
}


class PhpdocClassHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_classes", 'PhpdocClass', "classid");
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