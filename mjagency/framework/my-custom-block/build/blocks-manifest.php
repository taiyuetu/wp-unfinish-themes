<?php
// This file is generated. Do not modify it manually.
return array(
	'capabilities-services' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/capabilities-services',
		'version' => '0.1.0',
		'title' => 'Capabilities Services',
		'category' => 'design',
		'icon' => 'rest-api',
		'description' => 'A dynamic services section with editable items.',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'align' => array(
				'wide',
				'full'
			)
		),
		'attributes' => array(
			'label' => array(
				'type' => 'string',
				'default' => 'Capabilities'
			),
			'titleTop' => array(
				'type' => 'string',
				'default' => 'What we'
			),
			'titleEm' => array(
				'type' => 'string',
				'default' => 'do best'
			),
			'intro' => array(
				'type' => 'string',
				'default' => 'From foundational brand thinking to immersive digital environments — we operate across the full spectrum of creative and technical disciplines.'
			),
			'services' => array(
				'type' => 'array',
				'items' => array(
					'type' => 'object',
					'properties' => array(
						'num' => array(
							'type' => 'string'
						),
						'name' => array(
							'type' => 'string'
						),
						'desc' => array(
							'type' => 'string'
						)
					)
				),
				'default' => array(
					array(
						'num' => '01',
						'name' => 'Brand<br>Strategy',
						'desc' => 'Positioning, identity architecture, verbal and visual systems that earn premium perception.'
					),
					array(
						'num' => '02',
						'name' => 'Web<br>Design',
						'desc' => 'Editorial-grade digital experiences. Considered, purposeful, and built to convert.'
					),
					array(
						'num' => '03',
						'name' => 'Digital<br>Experience',
						'desc' => 'Immersive interfaces, campaigns, and interactive storytelling at cultural scale.'
					),
					array(
						'num' => '04',
						'name' => 'Interactive<br>Development',
						'desc' => 'Performance-driven, technically precise front-end engineering that honours the design.'
					)
				)
			)
		),
		'textdomain' => 'capabilities-services',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'philosophy-section' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/philosophy-section',
		'version' => '0.1.0',
		'title' => 'Philosophy Section',
		'category' => 'design',
		'icon' => 'lightbulb',
		'description' => 'A dynamic philosophy section with stats and rich text editing.',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'anchor' => true
		),
		'attributes' => array(
			'sectionLabel' => array(
				'type' => 'string',
				'default' => 'Our Philosophy'
			),
			'philosophyStatement' => array(
				'type' => 'string',
				'default' => 'Design is not<br>decoration.<br>It is <em>strategy</em><br>made visible.'
			),
			'philosophyText1' => array(
				'type' => 'string',
				'default' => 'We believe the most powerful design work exists at the intersection of culture, technology, and human behaviour. Every pixel, every interaction, every word carries intent.'
			),
			'philosophyText2' => array(
				'type' => 'string',
				'default' => 'We work with a select number of clients each year — not because we can\'t scale, but because depth requires focus. The brands we shape with you should outlast trends and define categories.'
			),
			'stats' => array(
				'type' => 'array',
				'default' => array(
					array(
						'num' => '140+',
						'label' => 'Projects Delivered'
					),
					array(
						'num' => '9yr',
						'label' => 'In Practice'
					),
					array(
						'num' => '28',
						'label' => 'Industry Awards'
					),
					array(
						'num' => '100%',
						'label' => 'Client Retention'
					)
				)
			)
		),
		'textdomain' => 'philosophy-block',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	)
);
