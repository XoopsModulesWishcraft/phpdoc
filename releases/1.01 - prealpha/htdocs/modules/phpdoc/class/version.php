<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class PhpdocVersion extends XoopsObject
{

    function PhpdocVersion($fid = null)
    {
        $this->initVar('versionid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('projectid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('status', XOBJ_DTYPE_ENUM, '_MI_PHPDOC_STATUS_STABLE', false, false, false, array('_MI_PHPDOC_STATUS_ALPHA','_MI_PHPDOC_STATUS_BETA','_MI_PHPDOC_STATUS_RC','_MI_PHPDOC_STATUS_STABLE','_MI_PHPDOC_STATUS_MATURE','_MI_PHPDOC_STATUS_EXPERIMENTAL'));
        $this->initVar('itemid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('supporturl', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('downloadurl', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('repourl', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('major', XOBJ_DTYPE_INT, 0, false);
       	$this->initVar('minor', XOBJ_DTYPE_INT, 0, false);
       	$this->initVar('revision', XOBJ_DTYPE_INT, 0, false);
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

		$frm['required'][] = 'major';
		$frm['required'][] = 'minor';
		$frm['required'][] = 'revision';
				
		xoops_loadLanguage('forms', 'phpdoc');
		$item_digest_handler = xoops_getmodulehandler('item_digest', 'phpdoc');
		$id = $this->getVar('versionid');
		
		switch ($caption){
			case true:
				$frm['versionid'] = new XoopsFormHidden('id['.$id.']', 'version');
				$frm['cid'] = new XoopsFormSelectCategory(_FRM_PHPDOC_CATEGORY, $id.'[cid]', $this->getVar('cid'));
				$frm['projectid'] = new XoopsFormSelectProject(_FRM_PHPDOC_PROJECT, $id.'[projectid]', $this->getVar('projectid'));
				$frm['weight'] = new XoopsFormText(_FRM_PHPDOC_WEIGHT, $id.'[weight]', 5, 15, $this->getVar('weight'));
				$frm['status'] = new XoopsFormSelectStatus(_FRM_PHPDOC_STATUS, $id.'[status]', $this->getVar('status'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);			
				$frm['supporturl'] = new XoopsFormText(_FRM_PHPDOC_SUPPORTURL, $id.'[supporturl]', 35, 255, $this->getVar('supporturl'));
				$frm['downloadurl'] = new XoopsFormText(_FRM_PHPDOC_DOWNLOADURL, $id.'[downloadurl]', 35, 255, $this->getVar('downloadurl'));
				$frm['repourl'] = new XoopsFormText(_FRM_PHPDOC_REPOURL, $id.'[repourl]', 35, 255, $this->getVar('repourl'));
				$frm['major'] = new XoopsFormText(_FRM_PHPDOC_MAJOR, $id.'[major]', 5, 5, $this->getVar('major'));
				$frm['minor'] = new XoopsFormText(_FRM_PHPDOC_MINOR, $id.'[minor]', 5, 5, $this->getVar('minor'));
				$frm['revision'] = new XoopsFormText(_FRM_PHPDOC_REVISION, $id.'[revision]', 5, 5, $this->getVar('revision'));
				if ($render==false)
					return $frm;
				break;
			case false:
				$frm['versionid'] = new XoopsFormHidden('id['.$id.']', 'version');
				$frm['cid'] = new XoopsFormSelectCategory('', $id.'[cid]', $this->getVar('cid'));
				$frm['projectid'] = new XoopsFormSelectProject('', $id.'[projectid]', $this->getVar('projectid'));
				$frm['weight'] = new XoopsFormText('', $id.'[weight]', 5, 15, $this->getVar('weight'));
				$frm['status'] = new XoopsFormSelectStatus('', $id.'[status]', $this->getVar('status'));
				$frm['itemid'] = new XoopsFormHidden($id.'[itemid]', $this->getVar('itemid'));
				$frm = $item_digest_handler->getForm($this->getVar('itemid'), (isset($_GET['language'])?$_GET['language']:$GLOBALS['xoopsConfig']['language']), $querystring, $caption, $frm);			
				$frm['supporturl'] = new XoopsFormText('', $id.'[supporturl]', 35, 255, $this->getVar('supporturl'));
				$frm['downloadurl'] = new XoopsFormText('', $id.'[downloadurl]', 35, 255, $this->getVar('downloadurl'));
				$frm['repourl'] = new XoopsFormText('', $id.'[repourl]', 35, 255, $this->getVar('repourl'));
				$frm['major'] = new XoopsFormText('', $id.'[major]', 5, 5, $this->getVar('major'));
				$frm['minor'] = new XoopsFormText('', $id.'[minor]', 5, 5, $this->getVar('minor'));
				$frm['revision'] = new XoopsFormText('', $id.'[revision]', 5, 5, $this->getVar('revision'));
				return $frm;			
				break;				
		}

	    if ($this->isNew()) {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_NEW_VERSION, 'version', $_SERVER['PHP_SELF'], 'post');
    	} else {
    		$form = new XoopsThemeForm(_FRM_PHPDOC_EDIT_VERSION, 'version', $_SERVER['PHP_SELF'], 'post');
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
		return $ret;				
	}
	
	function runPreInsertPlugin($handler) {
		
		include_once($GLOBALS['xoops']->path('/modules/phpdoc/plugins/'.strtolower($this->getPluginName()).'.php'));
		
		switch ($this->getVar('status')) {
			default:
				$func = $this->getPluginName().'PreInsertVersion';
				break;
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
				$func = $this->getPluginName().'PostInsertVersion';
				break;
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
				$func = $this->getPluginName().'PostGetVersion';
				break;
		}
		
		if (function_exists($func))  {
			return @$func($this, $handler);
		}
		return $this;
	}
}


class PhpdocVersionHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "phpdoc_versions", 'PhpdocVersion', "pathid");
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