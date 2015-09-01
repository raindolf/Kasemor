<?php
if ( !function_exists('tt_icon_attachment') ) {
	function tt_icon_attachment( $type ) {
		
	switch( $type ) {
		
		// PDF
		case 'pdf':
		return '<i class="fa fa-file-pdf-o"></i>';
		
		// Image
		case 'jpg':
		case 'jpeg':	
		case 'png':
		case 'gif':
		case 'bmp':
		case 'tif':
		case 'tiff':
		return '<i class="fa fa-file-image-o"></i>';
		
		// Audio
		case 'mp3':
		case 'wav':
		case 'm4a':
		case 'aif':
		case 'wma':
		case 'ra':
		case 'mpa':
		case 'iff':
		case 'm3u':
		return '<i class="fa fa-file-audio-o"></i>';
		
		// Video
		case 'avi':
		case 'flv':
		case 'm4v':
		case 'mov':
		case 'mp4':
		case 'mpg':
		case 'rm':
		case 'swf':
		case 'wmv':
		return '<i class="fa fa-file-video-o"></i>';
		
		// Text
		case 'txt':
		case 'log':
		case 'tex':
		return '<i class="fa fa-file-text-o"></i>';
		
		// Doc
		case 'doc':
		case 'docx':
		case 'odt':
		case 'msg':
		case 'rtf':
		case 'wps':
		case 'wpd':
		case 'pages':
		return '<i class="fa fa-file-word-o"></i>';
		
		// Spreadsheet
		case 'csv':
		case 'xls':
		case 'xlsx':
		case 'xml':
		case 'xlr':
		return '<i class="fa fa-file-excel-o"></i>';
		
		// ZIP
		case 'zip':
		case 'rar':
		case '7z':
		case 'zipx':
		case 'tar.gz':
		case 'gz':
		case 'pkg':
		return '<i class="fa fa-file-zip-o"></i>';
		
		// Other
		default:
		return '<i class="fa fa-file-o"></i>';
		
		}
		
	}
}