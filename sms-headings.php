<?php
/*
Plugin Name: SMS Headings
Plugin URI: http://www.roadsidemultimedia.com
Description: Add headings to the site using global styles to decide how they appear
Author: Roadside Multimedia
PageLines: true
Version: 1.0
Section: true
Class Name: SMS_Heading
Filter: component
Loading: active
*/

/**
 * IMPORTANT
 * This tells wordpress to not load the class as DMS will do it later when the main sections API is available.
 * If you want to include PHP earlier like a normal plugin just add it above here.
 */

if( ! class_exists( 'PageLinesSectionFactory' ) )
	return;

class SMS_Heading extends PageLinesSection {

	function section_head() {
		add_action( 'pl_scripts_on_ready', array( $this, 'script' ) );
	}

	function script() {

		/*
		// Remove padding that Pagelines adds to all sections
		ob_start();
		?>jQuery( '.section-sms-headings div' ).removeClass('pl-section-pad fix');
		<?php
		return ob_get_contents();
		*/
		
	}

	function section_opts(){

			// $size_name_choices = $sms_options['fonts']['size-name-list'];

			$selector_choices = array(
				'h1' => array( 'name' => 'H1 (use only one per page)' ),
				'h2' => array( 'name' => 'H2' ),
				'h3' => array( 'name' => 'H3' ),
				'h4' => array( 'name' => 'H4' ),
				'h5' => array( 'name' => 'H5' ),
				'h6' => array( 'name' => 'H6' ),
			);

			$text_align_choices = array(
				'align-left'      => array( 'name' => 'Left' ),
				'align-center'    => array( 'name' => 'Center' ),
				'align-right'     => array( 'name' => 'Right' ),
				'align-justify'   => array( 'name' => 'Justify' ),
				'align-initial'   => array( 'name' => 'Initial' ),
				'align-inherit'   => array( 'name' => 'Inherit' ),
			);

			$value_group_array = array(
				'title' => 'Heading',
			);

			$field_array = array(
				array(
					'type'    => 'check',
					'key'     => 'enable',
					'title'   => 'Enable subheading?',
					'default' => false
				),
				array(
					'type'          => 'select',
					'title'         => 'Type',
					'key'           => 'type',
					'opts'=> array(
						'primary'   => array( 'name' => 'Heading' ),
						'secondary' => array( 'name' => 'Subheading' ),
						'tertiary'  => array( 'name' => 'Accent Heading' ),
					),
				),
				array(
					'type'          => 'select',
					'title'         => 'Selector',
					'key'           => 'selector',
					'opts'          => $selector_choices,
				),
				array(
					'type'          => 'select',
					'title'         => 'Alignment Override',
					'key'           => 'align',
					'opts'          => $text_align_choices,
				),
				array(
					'type'          => 'text',
					'title'         => 'Text',
					'key'           => 'text',
				),
			);

			$options = array();
			$variations = 2;
			for ($i=1; $i <= $variations; $i++) { 

				$temp = array();
				$j = 0;
				foreach ($field_array as $field) {

					// Only add enable option for subheading on second iteration
					if( $i == 1 ){
						if( $field['key'] == 'enable' )
							continue;
					}

					$temp[$j] = array(
						'title' => $field['title'],
						'key'   => "heading{$i}_{$field['key']}",
						'type'  => $field['type'],
						'opts'  => $field['opts'],
					);
					$j++;

				}

				$options[] = array(
					'title' => 'Heading #'.$i,
					'type'  => 'multi',
					'col'   => $i,
					'opts'  => $temp
				);

			}
			// echo "<pre>\$options: " . print_r($options, true) . "</pre>";

			return $options;
		}
		function section_template(){

			$heading1_type       = ($this->opt('heading1_type')) ? $this->opt('heading1_type') : 'primary';
			$heading1_selector   = ($this->opt('heading1_selector')) ? $this->opt('heading1_selector') : 'h2';
			$heading1_text       = ($this->opt('heading1_text')) ? $this->opt('heading1_text') : 'Default heading text';
			$heading1_align       = ($this->opt('heading1_align')) ? ' '.$this->opt('heading1_align') : '';

			$heading2_type       = ($this->opt('heading2_type')) ? $this->opt('heading2_type') : 'secondary';
			$heading2_selector   = ($this->opt('heading2_selector')) ? $this->opt('heading2_selector') : 'h3';
			$heading2_text       = ($this->opt('heading2_text')) ? $this->opt('heading2_text') : 'Default subheading text';
			$heading2_align       = ($this->opt('heading2_align')) ? ' '.$this->opt('heading2_align') : '';

			$output_heading1 = sprintf('<%2$s class="sms-heading sms-heading--%1$s%4$s">%3$s</%2$s>', $heading1_type, $heading1_selector, $heading1_text, $heading1_align);
			$output_heading2 = sprintf('<%2$s class="sms-heading sms-heading--%1$s%4$s">%3$s</%2$s>', $heading2_type, $heading2_selector, $heading2_text, $heading2_align);

			if( $this->opt('heading2_enable') ){
				$output = '<hgroup>'.$output_heading1.$output_heading2.'</hgroup>';
			} else {
				$output = $output_heading1;
			}

			echo $output;
		}
}
