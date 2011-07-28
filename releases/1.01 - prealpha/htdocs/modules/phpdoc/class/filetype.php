<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocFiletype extends XoopsObject
{

    function PhpdocFiletype($fid = null)
    {
        $this->initVar('filetypeid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('filetype', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_FILETYPE_OTHER', false, false, false, array('_MI_PHPDOC_FILETYPE_PHP', '_MI_PHPDOC_FILETYPE_CSS', '_MI_PHPDOC_FILETYPE_HTML', '_MI_PHPDOC_FILETYPE_JAVA', '_MI_PHPDOC_FILETYPE_TXT', '_MI_PHPDOC_FILETYPE_IMAGE', '_MI_PHPDOC_FILETYPE_ASSET', '_MI_PHPDOC_FILETYPE_OTHER'));
		$this->initVar('extension', XOBJ_DTYPE_TXTBOX, 'php', false, 20);
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
				$ret['forms'][$key] = $value->render();
			}
		}
		return $ret;
	}
	
	function getForm($querystring = '', $caption = true, $frm = false, $render=true) {
		if ($frm==false)
			$frm=array();
				
		xoops_loadLanguage('forms', 'phpdoc');
		$item_digest_handler = xoops_getmodulehandler('item_digest', 'phpdoc');
		$id = $this->getVar('filetypeid');
		
		switch ($caption){
			case true:
				$frm['filetypeid'] = new XoopsFormHidden('id['.$id.']', 'file');
				$frm['extension'] = new XoopsFormText(_FRM_PHPDOC_EXTENSION, $id.'[extension]', 5, 10, $this->getVar('extension'));
				$frm['filetype'] = new XoopsFormSelectFileType(_FRM_PHPDOC_FILETYPE, $id.'[filetype]', $this->getVar('filetype'));
				if ($render==false)
					return $frm;
				break;
			case false:
				$frm['filetypeid'] = new XoopsFormHidden('id['.$id.']', 'file');
				$frm['extension'] = new XoopsFormText('', $id.'[extension]', 5, 10, $this->getVar('extension'));
				$frm['filetype'] = new XoopsFormSelectFileType('', $id.'[filetype]', $this->getVar('filetype'));
				return $frm;			
				break;				
		}

	    if ($this->isNew()) {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_NEW_FILETYPE, 'filetype', $_SERVER['PHP_SELF'], 'post');
    	} else {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_EDIT_FILETYPE, 'filetype', $_SERVER['PHP_SELF'], 'post');
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
		if (defined($this->getVar('filetype').'_PLUGIN'))
			$ret .= constant($this->getVar('mode').'_PLUGIN');
		return $ret;				
	}
	
	function runPreInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('filetype')) {
			default:
				$func = $this->getPluginName().'PreInsertFiletype';
				break;
		}
		
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}

	function runPostInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('filetype')) {
			default:
				$func = $this->getPluginName().'PostInsertFiletype';
				break;
		}
		
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
	
	function runPostGetPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('filetype')) {
			default:
				$func = $this->getPluginName().'PostGetFiletype';
				break;
		}
	
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
}


class PhpdocFiletypeHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_filestypes", 'PhpdocFiletype', "filetypeid");
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
    	if ($obj->vars['filetype']['changed']==true) {	
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