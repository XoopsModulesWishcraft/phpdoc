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
	
	function getForm($querystring = '', $caption = true, $frm = false) {
		if ($frm==false)
			$frm=array();
				
		xoops_loadLanguage('forms', 'phpdoc');
		$item_digest_handler = xoops_getmodulehandler('item_digest', 'phpdoc');
		$id = $this->getVar('fileid');
		
		switch ($caption){
			case true:
				$frm['fileid'] = new XoopsFormHidden('id['.$id.']', 'file');
				$frm['cid'] = new XoopsFormSelectCategory(_FRM_PHPDOC_CATEGORY, $id.'[cid]', $this->getVar('cid'));
				$frm['projectid'] = new XoopsFormSelectProject(_FRM_PHPDOC_PROJECT, $id.'[projectid]', $this->getVar('projectid'));
				$frm['versionid'] = new XoopsFormSelectVersion(_FRM_PHPDOC_VERSION, $id.'[versionid]', $this->getVar('versionid'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);
				$frm['weight'] = new XoopsFormText(_FRM_PHPDOC_WEIGHT, $id.'[weight]', 5, 10, $this->getVar('weight'));
				$frm['path'] = new XoopsFormText(_FRM_PHPDOC_PATH, $id.'[path]', 35, 500, $this->getVar('path'));
				$frm['filename'] = new XoopsFormText(_FRM_PHPDOC_FILENAME, $id.'[filename]', 35, 255, $this->getVar('filename'));
				$frm['filetype'] = new XoopsFormSelectFileType(_FRM_PHPDOC_FILETYPE, $id.'[filetype]', $this->getVar('filetype'));
				break;
			case false:
				$frm['fileid'] = new XoopsFormHidden('id['.$id.']', 'file');
				$frm['cid'] = new XoopsFormSelectCategory('', $id.'[cid]', $this->getVar('cid'));
				$frm['projectid'] = new XoopsFormSelectProject('', $id.'[projectid]', $this->getVar('projectid'));
				$frm['versionid'] = new XoopsFormSelectVersion('', $id.'[versionid]', $this->getVar('versionid'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);
				$frm['weight'] = new XoopsFormText('', $id.'[weight]', 5, 10, $this->getVar('weight'));
				$frm['path'] = new XoopsFormText('', $id.'[path]', 35, 500, $this->getVar('path'));
				$frm['filename'] = new XoopsFormText('', $id.'[filename]', 35, 255, $this->getVar('filename'));
				$frm['filetype'] = new XoopsFormSelectFileType('', $id.'[filetype]', $this->getVar('filetype'));
				return $frm;			
				break;				
		}

	    if ($this->isNew()) {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_NEW_FILE, 'file', $_SERVER['PHP_SELF'], 'post');
    	} else {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_EDIT_FILE, 'file', $_SERVER['PHP_SELF'], 'post');
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
				$func = $this->getPluginName().'PreInsertFile';
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
				$func = $this->getPluginName().'PostInsertFile';
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
				$func = $this->getPluginName().'PostGetFile';
				break;
		}
	
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
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