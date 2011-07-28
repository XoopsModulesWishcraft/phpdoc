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
		$this->initVar('fileid', XOBJ_DTYPE_INT, 0, false);
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
				
		xoops_loadLanguage('forms', 'phpdoc');
		$item_digest_handler = xoops_getmodulehandler('item_digest', 'phpdoc');
		$id = $this->getVar('id');
		
		switch ($caption){
			case true:
				$frm['id'] = new XoopsFormHidden('id['.$id.']', 'file');
				$frm['cid'] = new XoopsFormSelectCategory(_FRM_PHPDOC_CATEGORY, $id.'[cid]', $this->getVar('cid'));
				$frm['projectid'] = new XoopsFormSelectProject(_FRM_PHPDOC_PROJECT, $id.'[projectid]', $this->getVar('projectid'));
				$frm['versionid'] = new XoopsFormSelectVersion(_FRM_PHPDOC_VERSION, $id.'[versionid]', $this->getVar('versionid'));
				$frm['fileid'] = new XoopsFormSelectFile(_FRM_PHPDOC_FILE, $id.'[fileid]', $this->getVar('fileid'));
				$frm['classid'] = new XoopsFormSelectClass(_FRM_PHPDOC_CLASS, $id.'[classid]', $this->getVar('classid'));
				$frm['functionid'] = new XoopsFormSelectFile(_FRM_PHPDOC_FUNCTION, $id.'[functionid]', $this->getVar('functionid'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);
				$frm['weight'] = new XoopsFormText(_FRM_PHPDOC_WEIGHT, $id.'[weight]', 5, 10, $this->getVar('weight'));
				$frm['status'] = new XoopsFormSelectStatus(_FRM_PHPDOC_STATUS, $id.'[status]', $this->getVar('status'));
				$frm['mode'] = new XoopsFormSelectMode(_FRM_PHPDOC_MODE, $id.'[mode]', $this->getVar('mode'));
				$frm['visible'] = new XoopsFormRadioYN(_FRM_PHPDOC_VISIBLE, $id.'[visible]', $this->getVar('visible'));
				$frm['comments'] = new XoopsFormRadioYN(_FRM_PHPDOC_COMMENTS, $id.'[comments]', $this->getVar('comments'));
				if ($render==false)
					return $frm;
				break;
			case false:
				$frm['id'] = new XoopsFormHidden('id['.$id.']', 'file');
				$frm['cid'] = new XoopsFormSelectCategory('', $id.'[cid]', $this->getVar('cid'));
				$frm['projectid'] = new XoopsFormSelectProject('', $id.'[projectid]', $this->getVar('projectid'));
				$frm['versionid'] = new XoopsFormSelectVersion('', $id.'[versionid]', $this->getVar('versionid'));
				$frm['fileid'] = new XoopsFormSelectFile('', $id.'[fileid]', $this->getVar('fileid'));
				$frm['classid'] = new XoopsFormSelectClass('', $id.'[classid]', $this->getVar('classid'));
				$frm['functionid'] = new XoopsFormSelectFile('', $id.'[functionid]', $this->getVar('functionid'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);
				$frm['weight'] = new XoopsFormText('', $id.'[weight]', 5, 10, $this->getVar('weight'));
				$frm['status'] = new XoopsFormSelectStatus('', $id.'[status]', $this->getVar('status'));
				$frm['mode'] = new XoopsFormSelectMode('', $id.'[mode]', $this->getVar('mode'));
				$frm['visible'] = new XoopsFormRadioYN('', $id.'[visible]', $this->getVar('visible'));
				$frm['comments'] = new XoopsFormRadioYN('', $id.'[comments]', $this->getVar('comments'));
				return $frm;			
				break;				
		}

	    if ($this->isNew()) {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_NEW_INDEX, 'index', $_SERVER['PHP_SELF'], 'post');
    	} else {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_EDIT_INDEX, 'index', $_SERVER['PHP_SELF'], 'post');
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