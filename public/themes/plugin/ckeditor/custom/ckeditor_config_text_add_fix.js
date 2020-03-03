CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'Subscript,Superscript,Cut,Copy,Paste,PasteText,PasteFromWord,Scayt,Link,Unlink,Anchor,Image,Table,HorizontalRule,SpecialChar,Source,Strike,RemoveFormat,Outdent,Indent,Blockquote,Styles,Format,About';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.removePlugins = 'contextmenu,liststyle,tabletools,tableselection';
  	config.disableNativeSpellChecker = false;
};