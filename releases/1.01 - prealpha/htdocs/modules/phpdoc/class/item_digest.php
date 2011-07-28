<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocItem_digest extends XoopsObject
{

    function PhpdocItem_digest($fid = null)
    {
        $this->initVar('itemdigestid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('projectid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('versionid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('fileid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('classid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('functionid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('variableid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('pathid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('language', XOBJ_DTYPE_TXTBOX, '', false, 128);
        $this->initVar('menu', XOBJ_DTYPE_TXTBOX, '', false, 128);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('description', XOBJ_DTYPE_OTHER, '', false);
		$this->initVar('md5', XOBJ_DTYPE_TXTBOX, 0, false, 32);
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
				$ret['forms'][$key] = $frm[$key]->render();
			}
		}
		return $ret;
	}
	
	function getForm($querystring = '', $caption = true, $frm = false, $render=true) {
		if ($frm==false)
			$frm=array();

		$frm['required'][] = 'menu';
		
		xoops_loadLanguage('forms', 'phpdoc');
		$item_digest_handler = xoops_getmodulehandler('item_digest', 'phpdoc');
		$id = $this->getVar('itemdigestid');
		
		switch ($caption){
			case true:
				$frm['itemdigestid'] = new XoopsFormHidden('digestid['.$id.']', 'item_digest');
				$frm['language'] = new XoopsFormSelectLanguage(_FRM_PHPDOC_LANGUAGE, $id.'[language]', $this->getVar('language'));
		    	$frm['language']->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.filter_querystring($querystring, 'language').'&language=\'+this.options[this.selectedIndex].value,\'_self\')"');
				$frm['menu'] = new XoopsFormText(_FRM_PHPDOC_MENUTEXT, $id.'[menu]', 35, 128, $this->getVar('menu'));
				$frm['title'] = new XoopsFormText(_FRM_PHPDOC_TITLE, $id.'[title]', 35, 255, $this->getVar('title'));
				$description_configs = array();
				$description_configs['name'] = $id.'[description]';
				$description_configs['value'] = $this->getVar('description');
				$description_configs['rows'] = 35;
				$description_configs['cols'] = 60;
				$description_configs['width'] = "100%";
				$description_configs['height'] = "400px";
				$description_configs['editor'] = $GLOBALS['xoopsModuleConfig']['editor'];
				$frm['description'] = new XoopsFormEditor(_FRM_PHPDOC_DESCRIPTION, $description_configs['name'], $description_configs);
				if ($render==false)
					return $frm;
				break;
			case false:
				$frm['itemdigestid'] = new XoopsFormHidden('digestid['.$id.']', 'item_digest');
				$frm['language'] = new XoopsFormSelectLanguage('', $id.'[language]', $this->getVar('language'));
		    	$frm['language']->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.filter_querystring($querystring, 'language').'&language=\'+this.options[this.selectedIndex].value,\'_self\')"');
				$frm['menu'] = new XoopsFormText('', $id.'[menu]', 35, 128, $this->getVar('menu'));
				$frm['title'] = new XoopsFormText('', $id.'[title]', 35, 255, $this->getVar('title'));
				$description_configs = array();
				$description_configs['name'] = $id.'[description]';
				$description_configs['value'] = $this->getVar('description');
				$description_configs['rows'] = 35;
				$description_configs['cols'] = 60;
				$description_configs['width'] = "100%";
				$description_configs['height'] = "400px";
				$description_configs['editor'] = $GLOBALS['xoopsModuleConfig']['editor'];
				$frm['description'] = new XoopsFormEditor('', $description_configs['name'], $description_configs);
				return $frm;			
				break;				
		}

	    if ($this->isNew()) {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_NEW_ITEM, 'item_digest', $_SERVER['PHP_SELF'], 'post');
    	} else {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_EDIT_ITEM, 'item_digest', $_SERVER['PHP_SELF'], 'post');
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


class PhpdocItem_digestHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_items_digest", 'PhpdocItem_digest', "itemdigestid");
    }
    
    function getForm($itemid, $language, $querystring = '', $caption = true, $frm = false, $render=true) {
		if ($frm==false)
			$frm=array();
    	
		$criteria = new CriteriaCompo(new Criteria('itemid', $itemid));
    	$criteria->add(new Criteria('language', $language));
    	
    	if ($this->getCount($criteria)>0){
    		$obj = $this->getObjects($criteria, false);
    		if (is_object($obj[0])) {
	    		return $obj[0]->getForm($querystring, $caption, $frm, $render);
    		} else {
	    		$obj = $this->create();
	    		$obj->setVar('itemid', $itemid);
	    		$obj->setVar('language', $language);
	    		return $obj->getForm($querystring, $caption, $frm, $render);
    		}
    	} else {
    		$obj = $this->create();
    		$obj->setVar('itemid', $itemid);
    		$obj->setVar('language', $language);
    		return $obj->getForm($querystring, $caption, $frm, $render);
    	}
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