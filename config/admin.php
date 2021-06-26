<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Settings for extensions.
	|--------------------------------------------------------------------------
	|
	| You can find all available extensions here
	| https://github.com/laravel-admin-extensions.
	|
	*/
	'extensions' => [
		'chartjs' => [
			// Set to `false` if you want to disable this extension
			'enable' => true,
		],

		'ckeditor' => [

			//Set to false if you want to disable this extension
			'enable' => true,

			// Editor configuration
			'config' => [
				"simpleUpload" => [
					"uploadUrl" => '/api/svc/media/upload/editor'
				],
				"fontFamily" => [
					"options" => [
						'default',
						'微软雅黑, 华文细黑, sans-serif',
						'宋体, sans-serif',
						'黑体, sans-serif',
						'Arial, Helvetica, sans-serif',
						'Courier New, Courier, monospace',
						'Georgia, serif',
						'Lucida Sans Unicode, Lucida Grande, sans-serif',
						'Tahoma, Geneva, sans-serif',
						'Times New Roman, Times, serif',
						'Trebuchet MS, Helvetica, sans-serif',
						'Verdana, Geneva, sans-serif'
					]
				],
				"fontSize" => [
					"options" => [
						9,
						11,
						13,
						'default',
						17,
						19,
						21,
						23
					]
				],
				"toolbar" => [
					"items" => [
//                        'heading',
						'fontFamily',
						'fontSize',
						'fontColor',
						'fontBackgroundColor',
						'bold',
						'italic',
						'link',
						'bulletedList',
						'numberedList',
						'|',
						'alignment:left',
						'alignment:center',
						'alignment:right',
//                        'indent',
//                        'outdent',
						'|',
						'imageUpload',
//                        'blockQuote',
//                        'insertTable',
//                        'mediaEmbed',
						'removeformat',
						'undo',
						'redo'
					]
				],
				'image' => [
					'toolbar' => [
						//'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight'
					],
					'resizeUnit' => 'px',
					'styles' => [
						// This option is equal to a situation where no style is applied.
						'full',

						'side',
						// This represents an image aligned to the left.
						'alignLeft',
						'alignCenter',
						// This represents an image aligned to the right.
						'alignRight'
					]
				],
				"table" => [
					'contentToolbar' => [
						'tableColumn',
						'tableRow',
						'mergeTableCells'
					]
				],
				'language' => 'zh-cn',

			]
		]
	],
];
