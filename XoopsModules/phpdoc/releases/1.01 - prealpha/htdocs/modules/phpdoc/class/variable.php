<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocVariable extends XoopsObject
{

    function PhpdocVariable($fid = null)
    {
        $this->initVar('variableid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cids', XOBJ_DTYPE_ARRAY, array(), false);
        $this->initVar('projectids', XOBJ_DTYPE_ARRAY, array(), false);
        $this->initVar('versionids', XOBJ_DTYPE_ARRAY, array(), false);
        $this->initVar('classids', XOBJ_DTYPE_ARRAY, array(), false);
        $this->initVar('functionids', XOBJ_DTYPE_ARRAY, array(), false);
        $this->initVar('fileids', XOBJ_DTYPE_ARRAY, array(), false);
        $this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, '', false, 128);
        $this->initVar('default', XOBJ_DTYPE_TXTBOX, '', false, 128);
        $this->initVar('type', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_TYPE_MIXED', false, false, false, array('_MI_PHPDOC_TYPE_MIXED','_MI_PHPDOC_TYPE_INTEGER','_MI_PHPDOC_TYPE_LONG','_MI_PHPDOC_TYPE_DOUBLE','_MI_PHPDOC_TYPE_FLOAT','_MI_PHPDOC_TYPE_STRING','_MI_PHPDOC_TYPE_ARRAY','_MI_PHPDOC_TYPE_OBJECT','_MI_PHPDOC_TYPE_BOOLEAN'));
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

		$frm['required'][] = 'folder';
				
		xoops_loadLanguage('forms', 'phpdoc');
		$item_digest_handler = xoops_getmodulehandler('item_digest', 'phpdoc');
		$id = $this->getVar('variableid');
		
		switch ($caption){
			case true:
				$frm['variableid'] = new XoopsFormHidden('id['.$id.']', 'variable');
				$frm['cids'] = new XoopsFormSelectCategory(_FRM_PHPDOC_CATEGORIES, $id.'[cids]', $this->getVar('cids'), 5, true);
				$frm['projectids'] = new XoopsFormSelectProject(_FRM_PHPDOC_PROJECTS, $id.'[projectids]', $this->getVar('projectids'), 5, true);
				$frm['versionids'] = new XoopsFormSelectVersion(_FRM_PHPDOC_VERSIONS, $id.'[versionids]', $this->getVar('versionids'), 5, true);
				$frm['classids'] = new XoopsFormSelectClass(_FRM_PHPDOC_CLASSES, $id.'[classids]', $this->getVar('classids'), 5, true);
				$frm['functionids'] = new XoopsFormSelectFunction(_FRM_PHPDOC_FUNCTIONS, $id.'[functionids]', $this->getVar('functionids'), 5, true);
				$frm['fileids'] = new XoopsFormSelectFile(_FRM_PHPDOC_FILES, $id.'[fileids]', $this->getVar('fileids'), 5, true);
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);			
				$frm['weight'] = new XoopsFormText(_FRM_PHPDOC_WEIGHT, $id.'[weight]', 5, 15, $this->getVar('weight'));
				$frm['name'] = new XoopsFormText(_FRM_PHPDOC_NAME, $id.'[name]', 35, 128, $this->getVar('name'));
				$frm['default'] = new XoopsFormText(_FRM_PHPDOC_DEFAULT, $id.'[default]', 35, 128, $this->getVar('default'));
				$frm['type'] = new XoopsFormSelectType(_FRM_PHPDOC_TYPE, $id.'[type]', $this->getVar('type'));			
				if ($render==false)
					return $frm;
				break;
			case false:
				$frm['variableid'] = new XoopsFormHidden('id['.$id.']', 'variable');
				$frm['cids'] = new XoopsFormSelectCategory('', $id.'[cids]', $this->getVar('cids'), 5, true);
				$frm['projectids'] = new XoopsFormSelectProject('', $id.'[projectids]', $this->getVar('projectids'), 5, true);
				$frm['versionids'] = new XoopsFormSelectVersion('', $id.'[versionids]', $this->getVar('versionids'), 5, true);
				$frm['classids'] = new XoopsFormSelectClass('', $id.'[classids]', $this->getVar('classids'), 5, true);
				$frm['functionids'] = new XoopsFormSelectFunction('', $id.'[functionids]', $this->getVar('functionids'), 5, true);
				$frm['fileids'] = new XoopsFormSelectFile('', $id.'[fileids]', $this->getVar('fileids'), 5, true);
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);			
				$frm['weight'] = new XoopsFormText('', $id.'[weight]', 5, 15, $this->getVar('weight'));
				$frm['name'] = new XoopsFormText('', $id.'[name]', 35, 128, $this->getVar('name'));
				$frm['default'] = new XoopsFormText('', $id.'[default]', 35, 128, $this->getVar('default'));
				$frm['type'] = new XoopsFormSelectType('', $id.'[type]', $this->getVar('type'));
				return $frm;			
				break;				
		}

	    if ($this->isNew()) {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_NEW_VARIABLE, 'variable', $_SERVER['PHP_SELF'], 'post');
    	} else {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_EDIT_VARIABLE, 'variable', $_SERVER['PHP_SELF'], 'post');
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
		if (defined($this->getVar('type').'_PLUGIN'))
			$ret .= constant($this->getVar('type').'_PLUGIN');
		return $ret;				
	}
	
	function runPreInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('type')) {
			default:
				$func = $this->getPluginName().'PreInsertVariable';
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
				$func = $this->getPluginName().'PostInsertVariable';
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
				$func = $this->getPluginName().'PostGetVariable';
				break;
		}
				
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}	
}


class PhpdocVariableHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_variables", 'PhpdocVariable', "pathid");
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