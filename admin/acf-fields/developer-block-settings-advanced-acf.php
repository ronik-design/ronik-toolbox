<?php

if (function_exists('acf_add_local_field_group')) :

    function spacing_mtop($screenType){
        $conditional_logic = array(
            array(
                array(
                    'field' => 'field_624ddc1a83353_dynamic',
                    'operator' => '==',
                    'value' => '0',
                ),
            ),
        );
        if($screenType == 'desktop'){
            $screen_type = '';
            $screen_name = '';
            $conditional_logic = '';
        } elseif($screenType == 'tablet') {
            $screen_type = '_tablet';
            $screen_name = '(Tablet)';
        } else{
            $screen_type = '_mobile';
            $screen_name = '(Mobile)';
        }

        return array(
            'key' => 'field_624ddc1a83353'.$screen_type,
            'label' => 'Margin Top '.$screen_name ,
            'name' => 'margin-top'.$screen_type,
            'type' => 'number',
            'instructions' => 'Spacing is applied to outside of the container. Negatives values can be applied.',
            'required' => 0,
            'conditional_logic' => $conditional_logic,
            'wrapper' => array(
                'width' => '25',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => 'px',
            'min' => '',
            'max' => '',
            'step' => '',
        ); 
    }
    function spacing_mbtm($screenType){
        $conditional_logic = array(
            array(
                array(
                    'field' => 'field_624ddc1a83353_dynamic',
                    'operator' => '==',
                    'value' => '0',
                ),
            ),
        );
        if($screenType == 'desktop'){
            $screen_type = '';
            $screen_name = '';
            $conditional_logic = '';
        } elseif($screenType == 'tablet') {
            $screen_type = '_tablet';
            $screen_name = '(Tablet)';
        } else{
            $screen_type = '_mobile';
            $screen_name = '(Mobile)';
        }

        return array(
            'key' => 'field_624de0a9971ae'.$screen_type,
            'label' => 'Margin Bottom '. $screen_name,
            'name' => 'margin-bottom'.$screen_type,
            'type' => 'number',
            'instructions' => 'Spacing is applied to outside of the container. Negatives values can be applied.',
            'required' => 0,
            'conditional_logic' => $conditional_logic,
            'wrapper' => array(
                'width' => '25',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => 'px',
            'min' => '',
            'max' => '',
            'step' => '',
        ); 
    }
    function spacing_ptop($screenType){
        $conditional_logic = array(
            array(
                array(
                    'field' => 'field_624ddc1a83353_dynamic',
                    'operator' => '==',
                    'value' => '0',
                ),
            ),
        );
        if($screenType == 'desktop'){
            $screen_type = '';
            $screen_name = '';
            $conditional_logic = '';
        } elseif($screenType == 'tablet') {
            $screen_type = '_tablet';
            $screen_name = '(Tablet)';
        } else{
            $screen_type = '_mobile';
            $screen_name = '(Mobile)';
        }

        return array(
            'key' => 'field_624de0b6971af'.$screen_type,
            'label' => 'Padding Top '.$screen_name,
            'name' => 'padding-top'.$screen_type,
            'type' => 'number',
            'instructions' => 'Spacing is applied to within the container.',
            'required' => 0,
            'conditional_logic' => $conditional_logic,
            'wrapper' => array(
                'width' => '25',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => 'px',
            'min' => 0,
            'max' => '',
            'step' => '',
        );
    }
    function spacing_pbtm($screenType){
        $conditional_logic = array(
            array(
                array(
                    'field' => 'field_624ddc1a83353_dynamic',
                    'operator' => '==',
                    'value' => '0',
                ),
            ),
        );
        if($screenType == 'desktop'){
            $screen_type = '';
            $screen_name = '';
            $conditional_logic = '';
        } elseif($screenType == 'tablet') {
            $screen_type = '_tablet';
            $screen_name = '(Tablet)';
        } else{
            $screen_type = '_mobile';
            $screen_name = '(Mobile)';
        }

        return array(
            'key' => 'field_624de0dfffd3e'.$screen_type,
            'label' => 'Padding Bottom'.$screen_name,
            'name' => 'padding-bottom'.$screen_type,
            'type' => 'number',
            'instructions' => 'Spacing is applied to within the container.',
            'required' => 0,
            'conditional_logic' => $conditional_logic,
            'wrapper' => array(
                'width' => '25',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => 'px',
            'min' => 0,
            'max' => '',
            'step' => '',
        );
    }
    


    acf_add_local_field_group(array(
        'key' => 'group_624ddd9f382ef_ronikdesign',
        'title' => 'CLONE: Advanced Settings',
        'fields' => array(
            array(
                'key' => 'field_6398e499ddb72',
                'label' => 'Outer Background Image',
                'name' => 'outer-img',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
            array(
                'key' => 'field_6398e5889e997',
                'label' => 'Outer Background Image (Position)',
                'name' => 'outer-img_pos',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'top' => 'Top',
                    'center' => 'Center',
                    'bottom' => 'Bottom',
                ),
                'default_value' => false,
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            array(
                'key' => 'field_6398e60c9e998',
                'label' => 'Outer Background Image (Size)',
                'name' => 'outer-img_size',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'contain' => 'Contain',
                    'cover' => 'Cover',
                ),
                'default_value' => false,
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            array(
                'key' => 'field_62c47efec3bf8',
                'label' => 'Outer Background Color',
                'name' => 'outer-bg',
                'type' => 'color_picker',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
            ),
            array(
                'key' => 'field_6398ebc147ced',
                'label' => 'Gradient Overlay',
                'name' => 'gradient_overlay',
                'type' => 'group',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '75',
                    'class' => '',
                    'id' => '',
                ),
                'layout' => 'table',
                'sub_fields' => array(
                    array(
                        'key' => 'field_6398f69acca49',
                        'label' => 'Enable Gradient',
                        'name' => 'enable_gradient',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                    array(
                        'key' => 'field_6398ebd147cee',
                        'label' => 'Color One',
                        'name' => 'color_one',
                        'type' => 'color_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                    ),
                    array(
                        'key' => 'field_6398ebdc47cef',
                        'label' => 'Color Two',
                        'name' => 'color_two',
                        'type' => 'color_picker',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                    ),
                    array(
                        'key' => 'field_6398ef5676ef3',
                        'label' => 'Overall Opacity',
                        'name' => 'overall_opacity',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => 0,
                        'max' => 1,
                        'step' => '.1',
                    ),
                ),
            ),
            array(
                'key' => 'field_6398e6dde16f9',
                'label' => 'Content Color Override',
                'name' => 'content_color_override',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'light' => 'Light',
                    'dark' => 'Dark',
                ),
                'default_value' => false,
                'allow_null' => 1,
                'multiple' => 0,
                'ui' => 1,
                'ajax' => 0,
                'return_format' => 'value',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_624ddade43043',
                'label' => 'Z-Index',
                'name' => 'z-index',
                'type' => 'number',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'min' => '',
                'max' => '',
                'step' => '',
            ),
            array(
                'key' => 'field_6398e96e377f0',
                'label' => 'Content Alignment',
                'name' => 'content-alignment',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ),
                'default_value' => false,
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            array(
                'key' => 'field_628d2a8ee82f1',
                'label' => 'Outer Class Assigning',
                'name' => 'class_assigning_outer',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_628d2af1e82f2',
                'label' => 'Outer Id Assigning',
                'name' => 'id_assigning_outer',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_628d2b5c1adad',
                'label' => 'Inner Class Assigning',
                'name' => 'class_assigning_inner',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_628d2b4f1adac',
                'label' => 'Inner Id Assigning',
                'name' => 'id_assigning_inner',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),

            array(
                'key' => 'field_624ddc1a83353_dynamic',
                'label' => 'Enable Dynamic Spacing Resizing',
                'name' => 'enable_dyn_space_resizing',
                'type' => 'true_false',
                'instructions' => 'Enabling will auto override the tablet & mobile spacing and will resize desktop spacing based on percentage reduction.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
            

            array(
                'key' => 'field_624ddc1a83353_message',
                'label' => 'Spacing template (Desktop)',
                'name' => '',
                'aria-label' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'The Following section will only affect desktop screensizes'
            ),
            
            spacing_mtop('desktop'),
            spacing_mbtm('desktop'),
            spacing_ptop('desktop'),
            spacing_pbtm('desktop'),
            array(
                'key' => 'field_624ddc1a83353b_message',
                'label' => 'Spacing template (Tablet)',
                'name' => '',
                'aria-label' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_624ddc1a83353_dynamic',
                            'operator' => '==',
                            'value' => '0',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'The Following section will only affect tablet screensizes'
            ),
            spacing_mtop('tablet'),
            spacing_mbtm('tablet'),
            spacing_ptop('tablet'),
            spacing_pbtm('tablet'),
            array(
                'key' => 'field_624ddc1a83353c_message',
                'label' => 'Spacing template (Mobile)',
                'name' => '',
                'aria-label' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_624ddc1a83353_dynamic',
                            'operator' => '==',
                            'value' => '0',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'The Following section will only affect mobile screensizes'
            ),
            spacing_mtop('mobile'),
            spacing_mbtm('mobile'),
            spacing_ptop('mobile'),
            spacing_pbtm('mobile'),

            array(
                'key' => 'field_624ddc1a83353sss_message',
                'label' => 'Width & Height template',
                'name' => '',
                'aria-label' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => 'The Following section will affect all screensizes'
            ),
            array(
                'key' => 'field_62c72711f9e78',
                'label' => 'Min Height',
                'name' => 'min-height',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'xsm' => 'X-Small (575px)',
                    'sm' => 'Small (745px)',
                    'md' => 'Medium (775px)',
                    'lg' => 'Large (825px)',
                    'xlg' => 'X-Large (945px)',
                    'custom' => 'Custom',
                ),
                'default_value' => 'custom',
                'allow_null' => 1,
                'multiple' => 0,
                'ui' => 1,
                'ajax' => 0,
                'return_format' => 'value',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_62c728aef9e79',
                'label' => 'Custom Min Height',
                'name' => 'min-height_custom',
                'type' => 'number',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_62c72711f9e78',
                            'operator' => '==',
                            'value' => 'custom',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'min' => '',
                'max' => '',
                'step' => '',
            ),
            array(
                'key' => 'field_62606a1ce28fb',
                'label' => 'Max Width',
                'name' => 'max-width',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'sm' => 'Small',
                    'md' => 'Medium',
                    'lg' => 'Large',
                    'custom' => 'Custom',
                ),
                'default_value' => 'custom',
                'allow_null' => 1,
                'multiple' => 0,
                'ui' => 1,
                'ajax' => 0,
                'return_format' => 'value',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_62606a74e28fc',
                'label' => 'Custom Max Width',
                'name' => 'max-width_custom',
                'type' => 'number',
                'instructions' => 'Min Value: 100 Max Value: 2500',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_62606a1ce28fb',
                            'operator' => '==contains',
                            'value' => 'custom',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '25',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => 'px',
                'min' => 100,
                'max' => 2500,
                'step' => 100,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => false,
        'description' => '',
        'modified' => 1670969079,
    ));

endif;
