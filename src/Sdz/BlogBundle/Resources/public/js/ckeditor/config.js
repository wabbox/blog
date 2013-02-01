CKEDITOR.editorConfig = function( config )
{
 // Define changes to default configuration here. For example:
 config.language = 'fr';
 config.uiColor = '#1980AF';
 config.resize_enabled = false;
 config.width = 650;
 config.height = 400;
 config.toolbarCanCollapse = false;
 config.enterMode = CKEDITOR.ENTER_BR;
 config.shiftEnterMode = CKEDITOR.ENTER_P;

 config.toolbar_CustomText2 =
 [
 ['Styles','Format','Font','FontSize'],
 '/',
 ['Bold', 'Italic', 'Underline', 'Strike', '-', 'NumberedList', 'BulletedList'],
 ['Underline', 'Strike', '-', 'Outdent','Indent','Blockquote'],
 ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
 '/',
 ['TextColor','BGColor','-', 'Preview']
 ];
};