<?php
	$alamat="http://localhost/mcq/form/fckeditor/editor/";
	include "ckeditor.php";
	 
 	$CKEditor = new CKEditor();
	$CKEditor->basePath = './';
	$config['skin'] = 'office2003';
	$config['width'] = '1000';
	$config['height'] = '300';
	$config['language'] = 'en';
	
 	$config['filebrowserBrowseUrl'] = 'filemanager/browser.html';
 	$config['filebrowserImageBrowseUrl'] = 'filemanager/browser.html?type=Images';
	$config['filebrowserFlashBrowseUrl'] = 'filemanager/browser.html?type=Flash';
 	$config['filebrowserUploadUrl'] = 'filemanager/connectors/php/connector.php?command=QuickUpload&type=Files';
 	$config['filebrowserImageUploadUrl'] = 'filemanager/connectors/php/connector.php?command=QuickUpload&type=Images';
 	$config['filebrowserFlashUploadUrl'] = 'filemanager/connectors/php/connector.php?command=QuickUpload&type=Flash';
 	#$editor('CKEditor1');
	
	
	/* $config['toolbar'] = array(
		array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
		array( 'Image', 'Link', 'Unlink', 'Anchor' )
	); */
	
	echo $CKEditor->editor("editor1", $initialValue, $config);
?>