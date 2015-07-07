/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	var url='ckeditor/'; // path = duong dan toi folder chua thu muc ckeditor va ckfinder.
    config.filebrowserBrowseUrl         = url+ 'ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl  	= url+ 'ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl 	= url+ 'ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl 	    = url+ 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl 	= url+ 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl 	= url+ '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
	config.extraPlugins = 'youtube';
	// config.extraPlugins = 'lineheight';
	config.height = 500;
};
