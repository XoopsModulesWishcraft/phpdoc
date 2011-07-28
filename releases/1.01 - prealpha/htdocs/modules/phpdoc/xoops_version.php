<?php

// $Author: wishcraft $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Simon Roberts (AKA wishcraft)                                     //
// URL: http://www.chronolabs.org.au                                         //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

$modversion['name'] = _MI_PHPDOC_NAME;
$modversion['version'] = 1.09;
$modversion['releasedate'] = "Thursday: July 14, 2011";
$modversion['description'] = _MI_PHPDOC_DESCRIPTION;
$modversion['author'] = "Wishcraft";
$modversion['credits'] = "Simon Roberts (simon@chronolabs.coop)";
$modversion['help'] = "spiders.html";
$modversion['license'] = "GPL";
$modversion['official'] = 0;
$modversion['status']  = "Stable";
$modversion['image'] = "images/phpdoc_slogo.png";
$modversion['dirname'] = _MI_PHPDOC_DIRNAME;

$modversion['author_realname'] = "Simon Roberts";
$modversion['author_website_url'] = "http://www.chronolabs.coop";
$modversion['author_website_name'] = "Chronolabs Cooperative";
$modversion['author_email'] = "simon@chronolabs.coop";
$modversion['demo_site_url'] = "http://xoops.demo.chronolabs.coop";
$modversion['demo_site_name'] = "Chronolabs Co-op XOOPS Demo";
$modversion['support_site_url'] = "";
$modversion['support_site_name'] = "Chronolabs";
$modversion['submit_bug'] = "";
$modversion['submit_feature'] = "";
$modversion['usenet_group'] = "sci.chronolabs";
$modversion['maillist_announcements'] = "";
$modversion['maillist_bugs'] = "";
$modversion['maillist_features'] = "";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

//$modversion['onUpdate'] = "include/update.php";
//$modversion['onInstall'] = "include/install.php";
//$modversion['onUninstall'] = "include/uninstall.php";

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// $modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";
// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "phpdoc_categories";
$modversion['tables'][1] = "phpdoc_classes";
$modversion['tables'][2] = "phpdoc_files";
$modversion['tables'][3] = "phpdoc_functions";
$modversion['tables'][4] = "phpdoc_index";
$modversion['tables'][5] = "phpdoc_items";
$modversion['tables'][6] = "phpdoc_items_digest";
$modversion['tables'][7] = "phpdoc_paths";
$modversion['tables'][8] = "phpdoc_projects";
$modversion['tables'][9] = "phpdoc_variables";
$modversion['tables'][10] = "phpdoc_versions";
$modversion['tables'][11] = "phpdoc_filestypes";

// Templates
$modversion['templates'][1]['file'] = 'phpdoc_cpanel_category_list.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'phpdoc_cpanel_class_list.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'phpdoc_cpanel_file_list.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'phpdoc_cpanel_function_list.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'phpdoc_cpanel_item_digest_list.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'phpdoc_cpanel_path_list.html';
$modversion['templates'][6]['description'] = '';
$modversion['templates'][7]['file'] = 'phpdoc_cpanel_project_list.html';
$modversion['templates'][7]['description'] = '';
$modversion['templates'][8]['file'] = 'phpdoc_cpanel_variable_list.html';
$modversion['templates'][8]['description'] = '';
$modversion['templates'][9]['file'] = 'phpdoc_cpanel_edit.html';
$modversion['templates'][9]['description'] = '';
$modversion['templates'][10]['file'] = 'phpdoc_cpanel_permissions.html';
$modversion['templates'][10]['description'] = '';
$modversion['templates'][11]['file'] = 'phpdoc_cpanel_filetype_list.html';
$modversion['templates'][11]['description'] = '';

// Menu
$i = 0;
$modversion['hasMain'] = 1;

xoops_load('XoopsEditorHandler');
$editor_handler = XoopsEditorHandler::getInstance();
foreach ($editor_handler->getList(false) as $id => $val)
	$options[$val] = $id;
	
$i++;
$modversion['config'][$i]['name'] = 'editor';
$modversion['config'][$i]['title'] = "_MI_PHPDOC_EDITOR";
$modversion['config'][$i]['description'] = "_MI_PHPDOC_EDITOR_DESC";
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'tinymce';
$modversion['config'][$i]['options'] = $options;

$i++;
$modversion['config'][$i]['name'] = 'projects_path';
$modversion['config'][$i]['title'] = "_MI_PHPDOC_PROJECTS_PATH";
$modversion['config'][$i]['description'] = "_MI_PHPDOC_PROJECTS_PATH_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = XOOPS_ROOT_PATH.'/modules/phpdoc/projects';

$i++;
$modversion['config'][$i]['name'] = 'htaccess';
$modversion['config'][$i]['title'] = "_MI_PHPDOC_HTACCESS";
$modversion['config'][$i]['description'] = "_MI_PHPDOC_HTACCESS_DESC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;

$i++;
$modversion['config'][$i]['name'] = 'baseurl';
$modversion['config'][$i]['title'] = "_MI_PHPDOC_BASEURL";
$modversion['config'][$i]['description'] = "_MI_PHPDOC_BASEURL_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'phpdoc';
$i++;

$modversion['config'][$i]['name'] = 'endofurl';
$modversion['config'][$i]['title'] = "_MI_PHPDOC_ENDOFURL";
$modversion['config'][$i]['description'] = "_MI_PHPDOC_ENDOFURL_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '.html';
$i++;

$modversion['config'][$i]['name'] = 'endofurl_rss';
$modversion['config'][$i]['title'] = "_MI_PHPDOC_ENDOFURLRSS";
$modversion['config'][$i]['description'] = "_MI_PHPDOC_ENDOFURLRSS_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '.rss';

$i++;

$modversion['config'][$i]['name'] = 'endofurl_pdf';
$modversion['config'][$i]['title'] = "_MI_PHPDOC_ENDOFURLPDF";
$modversion['config'][$i]['description'] = "_MI_PHPDOC_ENDOFURLPDF_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '.rss';

?>
