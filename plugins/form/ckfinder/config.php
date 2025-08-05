<?php
function CheckAuthentication(){
	return isset($_SESSION['ss_user']) && isset($_SESSION['ss_id']);
}

$config['LicenseName'] = '';
$config['LicenseKey'] = '';

$SERVER_NAME=$_SERVER['SERVER_NAME'];
$folder=$_SERVER['DOCUMENT_ROOT'];

include"$folder/setting/kon.php";
include"$folder/setting/variable.php";
include"$folder/Adminweb/setting/adm_function.php";

$nwebsite=IdUnit($url_unit);
$idu=$nwebsite["id"];
$domain=$nwebsite["domain"];

$baseDir = "$folder/files/$idu/";
$baseUrl = "http://$domain/files/$idu/";

#$baseUrl = 'http://'.$domx.'.umm.ac.id/files/';
#$baseDir = '/data/web/'.$dom.'/files/';


$config['Thumbnails'] = Array(
		'url' => $baseUrl . '_thumbs',
		'directory' => $baseDir . '_thumbs',
		'enabled' => true,
		'directAccess' => false,
		'maxWidth' => 100,
		'maxHeight' => 100,
		'bmpSupported' => false,
		'quality' => 80);

$config['Images'] = Array(
		'maxWidth' => 1600,
		'maxHeight' => 1200,
		'quality' => 80);

$config['RoleSessionVar'] = 'admin';
session_start();


$config['AccessControl'][] = Array(
		'role' => '*',
		'resourceType' => '*',
		'folder' => '/',

		'folderView' => true,
		'folderCreate' => true,
		'folderRename' => true,
		'folderDelete' => true,

		'fileView' => true,
		'fileUpload' => true,
		'fileRename' => true,
		'fileDelete' => true);

$config['DefaultResourceTypes'] = '';

$config['ResourceType'][] = Array(
		'name' => 'Files',				// Single quotes not allowed
		'url' => $baseUrl . 'file',
		'directory' => $baseDir . 'file',
		'maxSize' => 0,
		'allowedExtensions' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
		'deniedExtensions' => '');

$config['ResourceType'][] = Array(
		'name' => 'Images',
		'url' => $baseUrl . 'image',
		'directory' => $baseDir . 'image',
		'maxSize' => "16M",
		'allowedExtensions' => 'bmp,gif,jpeg,jpg,png,avi,iso,mp3',
		'deniedExtensions' => '');

$config['ResourceType'][] = Array(
		'name' => 'Flash',
		'url' => $baseUrl . 'flash',
		'directory' => $baseDir . 'flash',
		'maxSize' => 0,
		'allowedExtensions' => 'swf,flv',
		'deniedExtensions' => '');

$config['CheckDoubleExtension'] = true;
/*
Examples:
	$config['FilesystemEncoding'] = 'CP1250';
	$config['FilesystemEncoding'] = 'ISO-8859-2';
*/
$config['FilesystemEncoding'] = 'UTF-8';

$config['SecureImageUploads'] = true;

$config['CheckSizeAfterScaling'] = true;

$config['HtmlExtensions'] = array('html', 'htm', 'xml', 'js');

$config['HideFolders'] = Array(".svn", "CVS");

$config['HideFiles'] = Array(".*");

$config['ChmodFiles'] = 0777 ;

$config['ChmodFolders'] = 0755 ;

$config['ForceAscii'] = false;


include_once "plugins/imageresize/plugin.php";
include_once "plugins/fileeditor/plugin.php";

$config['plugin_imageresize']['smallThumb'] = '90x90';
$config['plugin_imageresize']['mediumThumb'] = '120x120';
$config['plugin_imageresize']['largeThumb'] = '180x180';

?>
