( function( blocks, element ) {
	var el = element.createElement;
	var registerBlockType = wp.blocks.registerBlockType;

	var RichText = wp.editor.RichText;
	var BlockControls = wp.editor.BlockControls;
	var AlignmentToolbar = wp.editor.AlignmentToolbar;
	var MediaUpload = wp.editor.MediaUpload;
	var InspectorControls = wp.editor.InspectorControls;
	var AlignmentToolbar = wp.editor.AlignmentToolbar;

	var components = wp.components;

	var TextControl = wp.components.TextControl;
	var ColorPalette = wp.components.ColorPalette;
	var SelectControl = wp.components.SelectControl;
	var ToggleControl = wp.components.ToggleControl;
	var RangeControl = wp.components.RangeControl;
	var FontSizePicker = wp.components.FontSizePicker;



	var blockStyle = {
		backgroundColor: '#900',
		color: '#fff',
		padding: '20px',
	};

	blocks.registerBlockType( 'wpblockhub/paragraph', {
		title: 'WPBlockHub Paragraph',
		icon: 'editor-paragraph',
		category: 'wpblockhub',
		attributes: {
			paragraphText:{
				type: 'string',
				default: 'Write your story...',
			},

			bgColor: {
				type: 'string',
				default: '#ffffff',
			},
			textColor: {
				type: 'string',
				default: '#222222',
			},

			fontsize: {
				type: 'number',
				default: '16',
			},

			paraPadding: {
				type: 'number',
				default: '8',
			},

			dropCap:{
				type: 'boolean',
				default:false,
			},


			alignment: {
				type: 'string',
				default: 'left',
			},

		},


		edit: function(props) {

			var attributes = props.attributes;
			var alignment = props.attributes.alignment;

			function onChangeAlignment( newAlignment ) {
				props.setAttributes( { alignment: newAlignment } );
			}

			return [

				el( BlockControls, { key: 'controls' }, // Display controls when the block is clicked on.
					el( AlignmentToolbar, {
						value: alignment,
						onChange: onChangeAlignment,
					} )
				),

				el( InspectorControls, { key: 'inspector' }, // Display the block options in the inspector panel.
					el( components.PanelBody, {
							title: 'Style',
							initialOpen: false,
							className:'',
						},


						el( 'p', {}, 'Background color.' ),
						el( ColorPalette, {
							value: props.attributes.bgColor,
							colors: [
								{color: '#ffffff', name: 'white'},
								{color: '#00d1b2', name: 'teal'},
								{ color: '#3373dc', name: 'royal blue' },
								{ color: '#209cef', name: 'sky blue' },
								{ color: '#22d25f', name: 'green' },
								{ color: '#ffdd57', name: 'yellow' },
								{ color: '#ff3860', name: 'pink' },
								{ color: '#7941b6', name: 'purple' },
								{ color: '#392F43', name: 'black' }
							],
							allowCustom: false,
							onChange: function(bgColor){
								props.setAttributes( { bgColor: bgColor } );

								//console.log(bgColor);
							}
						}),

						el( 'p', {}, 'Text color.' ),
						el( ColorPalette, {
							value: props.attributes.textColor,
							colors: [
								{color: '#ffffff', name: 'white'},
								{color: '#00d1b2', name: 'teal'},
								{ color: '#3373dc', name: 'royal blue' },
								{ color: '#209cef', name: 'sky blue' },
								{ color: '#22d25f', name: 'green' },
								{ color: '#ffdd57', name: 'yellow' },
								{ color: '#ff3860', name: 'pink' },
								{ color: '#7941b6', name: 'purple' },
								{ color: '#392F43', name: 'black' }
							],
							allowCustom: false,
							onChange: function(textColor){
								props.setAttributes( { textColor: textColor } );

								//console.log(bgColor);
							}
						}),

						el( 'p', {}, 'Padding.' ),
						el( RangeControl, {
							value: props.attributes.paraPadding,
							//initialPosition: props.attributes.padding,
							//allowReset: true,
							onChange: function(getPadding){
								props.setAttributes( { paraPadding: getPadding } );


								console.log(getPadding);
								console.log('props:'+props.attributes.paraPadding);
							}
						}),

						//el( 'p', {}, 'Font size.' ),
						el( FontSizePicker, {
							value: props.attributes.fontsize,
							//fontSize: 16,
							fontSizes:  [
								{name: 'Small',slug: 'small',size: 12,},
								{name: 'Normal',slug: 'normal',size: 16,},
								{name: 'Medium',slug: 'medium',size: 20,},
								{name: 'Large',slug: 'large',size: 36,},
								{name: 'Huge',slug: 'huge',size: 48,},
							],
							onChange: function(getFontSize){
								props.setAttributes( { fontsize: getFontSize } );
							}
						}),

						el( 'p', {}, 'Drop cap.' ),
						el(ToggleControl,{
							label: 'Enable drop cap',
							checked: props.attributes.dropCap,
							onChange: function( value ) {
								props.setAttributes( { dropCap: value } );
							},
						}),

					),


				),


				el( RichText, {
					key: 'editable',
					tagName: 'p',
					className: [attributes.dropCap ? 'dropcap' :'','wpblockhub-paragraph'],
					placeholder: 'Keep writing...',
					keepPlaceholderOnFocus: true,
					value: attributes.paragraphText,
					style:{background: attributes.bgColor, color: attributes.textColor, textAlign: attributes.alignment,fontSize: attributes.fontsize+'px', padding: attributes.paraPadding+'px'},
					onChange: function( content ) {
						props.setAttributes( { paragraphText: content } );
					},
				}),


			];
		},
		save: function(props){

			var attributes = props.attributes;
			alignment = attributes.alignment;
			bgColor = attributes.bgColor;
			textColor = attributes.textColor;
			fontsize = attributes.fontsize;
			paraPadding = attributes.paraPadding;
			dropCap = attributes.dropCap;


			console.log(dropCap);

			return el( RichText.Content, {
				className: [dropCap ? 'dropcap' : '', 'wpblockhub-paragraph'],
				tagName: 'p',
				value: attributes.paragraphText,
				style:{background: bgColor, color: textColor, fontSize: fontsize+'px', padding: paraPadding+'px',textAlign: alignment},
			})



		},
	} );
}(
	window.wp.blocks,
	window.wp.element
) );