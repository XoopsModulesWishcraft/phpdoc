<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocCategory extends XoopsObject
{

    function PhpdocCategory($fid = null)
    {
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('parentid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('weight', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
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
		
		xoops_loadLanguage('forms', 'phpdoc');
		$item_digest_handler = xoops_getmodulehandler('item_digest', 'phpdoc');
		$id = $this->getVar('cid');
		
		switch ($caption){
			case true:
				$frm['cid'] = new XoopsFormHidden('id['.$id.']', 'category');
				$frm['parentid'] = new XoopsFormSelectCategory(_FRM_PHPDOC_CATEGORY, $id.'[parentid]', $this->getVar('parentid'));
				$frm['weight'] = new XoopsFormText(_FRM_PHPDOC_WEIGHT, $id.'[weight]', 5, 10, $this->getVar('weight'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);
				if ($render==false)
					return $frm;
				break;
			case false:
				$frm['cid'] = new XoopsFormHidden('id['.$id.']', 'category');
				$frm['parentid'] = new XoopsFormSelectCategory('', $id.'[parentid]', $this->getVar('parentid'));
				$frm['weight'] = new XoopsFormText('', $id.'[weight]', 5, 10, $this->getVar('weight'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);
				return $frm;			
				break;				
		}

	    if ($this->isNew()) {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_NEW_CATEGORY, 'category', $_SERVER['PHP_SELF'], 'post');
    	} else {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_EDIT_CATEGORY, 'category', $_SERVER['PHP_SELF'], 'post');
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


class PhpdocCategoryHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_categories", 'PhpdocCategory', "cid");
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