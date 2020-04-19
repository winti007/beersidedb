/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{

        // Define changes to default configuration here. For example:
         config.language = 'hu';
//        config.forceEnterMode = CKEDITOR.ENTER_BR;
        config.enterMode = CKEDITOR.ENTER_BR;
        config.filebrowserBrowseUrl = '/ckeditor/filemanager/index.html';
         config.filebrowserUploadUrl = '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
         config.filebrowserImageBrowseUrl = '/ckeditor/filemanager/index.html';


        config.allowedContent = true;
         config.extraAllowedContent = '*(*)';



        // config.uiColor = '#AADC6E';
};
