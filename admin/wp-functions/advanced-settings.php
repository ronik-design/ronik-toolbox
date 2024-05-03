<?php 

function ronikdesigns_AdvancedSpacingSettings($fieldType, $f_pageID = null){ 
    if($fieldType == 'flex'){
		$get_acf_type = 'get_field';
	} else{
		$get_acf_type = 'get_field';
	}


    $f_margin_top = $get_acf_type( 'advanced_settings_margin-top', $f_pageID );
    if($f_margin_top || $f_margin_top == '0'){
        $f_margin_top = 'data-mt_desktop="' .$f_margin_top . 'px"';
    } else{
        $f_margin_top = false; 
    }
    $f_margin_btm = $get_acf_type( 'advanced_settings_margin-bottom', $f_pageID );
    if($f_margin_btm || $f_margin_btm == '0'){
        $f_margin_bottom = 'data-mbtm_desktop="' .$f_margin_btm . 'px"';
    } else{
        $f_margin_bottom = false;
    }
    $f_padding_top = $get_acf_type( 'advanced_settings_padding-top', $f_pageID );
    if($f_padding_top || $f_padding_top == '0'){
        $f_padding_top = 'data-pt_desktop="' .$f_padding_top . 'px"';
    } else{
        $f_padding_top = false; 
    }
    $f_padding_btm = $get_acf_type( 'advanced_settings_padding-bottom', $f_pageID );
    if($f_padding_btm || $f_padding_btm == '0'){
        $f_padding_bottom = 'data-pbtm_desktop="' .$f_padding_btm . 'px"';
    } else{
        $f_padding_bottom = false;
    }

    $f_margin_top_tablet = $get_acf_type( 'advanced_settings_margin-top_tablet', $f_pageID );
    if($f_margin_top_tablet || $f_margin_top_tablet == '0'){
        $f_margin_top_tablet = 'data-mt_tablet="' .$f_margin_top_tablet . 'px"';
    } else{
        $f_margin_top_tablet = false; 
    }
    $f_margin_btm_tablet = $get_acf_type( 'advanced_settings_margin-bottom_tablet', $f_pageID );
    if($f_margin_btm_tablet || $f_margin_btm_tablet == '0'){
        $f_margin_btm_tablet = 'data-mbtm_tablet="' .$f_margin_btm_tablet . 'px"';
    } else{
        $f_margin_btm_tablet = false;
    }
    $f_padding_top_tablet = $get_acf_type( 'advanced_settings_padding-top_tablet', $f_pageID );
    if($f_padding_top_tablet || $f_padding_top_tablet == '0'){
        $f_padding_top_tablet = 'data-pt_tablet="' .$f_padding_top_tablet . 'px"';
    } else{
        $f_padding_top_tablet = false; 
    }
    $f_padding_btm_tablet = $get_acf_type( 'advanced_settings_padding-bottom_tablet', $f_pageID );
    if($f_padding_btm_tablet || $f_padding_btm_tablet == '0'){
        $f_padding_btm_tablet = 'data-pbtm_tablet="' .$f_padding_btm_tablet . 'px"';
    } else{
        $f_padding_btm_tablet = false;
    }


    $f_margin_top_mobile = $get_acf_type( 'advanced_settings_margin-top_mobile', $f_pageID );
    if($f_margin_top_mobile || $f_margin_top_mobile == '0'){
        $f_margin_top_mobile = 'data-mt_mobile="' .$f_margin_top_mobile . 'px"';
    } else{
        $f_margin_top_mobile = false; 
    }
    $f_margin_btm_mobile = $get_acf_type( 'advanced_settings_margin-bottom_mobile', $f_pageID );
    if($f_margin_btm_mobile || $f_margin_btm_mobile == '0'){
        $f_margin_btm_mobile = 'data-mbtm_mobile="' .$f_margin_btm_mobile . 'px"';
    } else{
        $f_margin_btm_mobile = false;
    }
    $f_padding_top_mobile = $get_acf_type( 'advanced_settings_padding-top_mobile', $f_pageID );
    if($f_padding_top_mobile || $f_padding_top_mobile == '0'){
        $f_padding_top_mobile = 'data-pt_mobile="' .$f_padding_top_mobile . 'px"';
    } else{
        $f_padding_top_mobile = false; 
    }
    $f_padding_btm_mobile = $get_acf_type( 'advanced_settings_padding-bottom_mobile', $f_pageID );
    if($f_padding_btm_mobile || $f_padding_btm_mobile == '0'){
        $f_padding_btm_mobile = 'data-pbtm_mobile="' .$f_padding_btm_mobile . 'px"';
    } else{
        $f_padding_btm_mobile = false;
    }

    $f_advanced_resizing = $get_acf_type( 'advanced_settings_enable_dyn_space_resizing', $f_pageID );
    if($f_advanced_resizing && $f_advanced_resizing == '1'){        
        $f_advanced_resizing = 'data-adv-resize="valid"';
    } else{
        $f_advanced_resizing = false;
    }

    


    return $f_advanced_resizing . $f_margin_top . $f_margin_bottom . $f_padding_bottom . $f_padding_top . $f_margin_top_tablet . $f_margin_btm_tablet . $f_padding_top_tablet . $f_padding_btm_tablet . $f_margin_top_mobile . $f_margin_btm_mobile . $f_padding_top_mobile . $f_padding_btm_mobile;
}

function ronikdesigns_AdvancedSettings($fieldType, $f_pageID = null){ 
	if($fieldType == 'flex'){
		$get_acf_type = 'get_field';
	} else{
		$get_acf_type = 'get_field';
	}
    $f_zindex = $get_acf_type( 'advanced_settings_z-index', $f_pageID);
    if(!$f_zindex){ 
        $f_zindex = ''; 
    } else{ 
        $f_zindex = 'z-index:'.$f_zindex.';'; 
    }


    $f_content_align = $get_acf_type( 'advanced_settings_content-alignment', $f_pageID );
    $f_alignment = 'display: flex;align-items: center;';
    switch ($f_content_align) {
        case 'left':
            $f_alignment .= 'justify-content: flex-start;';
            break;
        case 'center':
            $f_alignment .= 'justify-content: center;';
            break;
        case 'right':
            $f_alignment .= 'justify-content: flex-end;';
            break;
    }
    return $f_zindex . $f_alignment;
}


function ronikdesigns_AdvancedSettingsInner($fieldType, $f_pageID = null){
	if($fieldType == 'flex'){
		$get_acf_type = 'get_field';
	} else{
		$get_acf_type = 'get_field';
	}
    $f_max_width = $get_acf_type( 'advanced_settings_max-width', $f_pageID );
    if($f_max_width){
        $f_mw = 'max-width:';
        switch ($f_max_width) {
            case 'sm':
                $f_mw .= '450px;';
                break;
            case 'md':
                $f_mw .= '750px;';
                break;
            case 'lg':
                $f_mw .= '1050px;';
                break;
            case 'custom':
                $f_max_width_custom = $get_acf_type( 'advanced_settings_max-width_custom', $f_pageID );
                if($f_max_width_custom){
                    $f_mw .=  $f_max_width_custom.'px;'; 
                    if($f_max_width_custom == ''){
                        $f_mw = 'max-width:100%;'; 
                    }
                } else{
                    $f_mw = 'max-width:100%;'; 
                }
                break;
        }
    } else{
        $f_mw = '';
    }
    $f_min_height = $get_acf_type( 'advanced_settings_min-height', $f_pageID );
    if($f_min_height){
        $f_mh = 'min-height:';
        switch ($f_min_height) {
            case 'sm':
                $f_mh .= '450px; display:flex;';
                break;
            case 'md':
                $f_mh .= '750px; display:flex;';
                break;
            case 'lg':
                $f_mh .= '1050px; display:flex;';
                break;
            case 'custom':
                $f_min_height_custom = $get_acf_type( 'advanced_settings_min-height_custom', $f_pageID );
                if($f_min_height_custom){
                    $f_mh .=  $f_min_height_custom.'px;'; 
                    if($f_min_height_custom == ''){
                        $f_mh = 'min-height:100%;'; 
                    }
                } else{
                    $f_mh = 'min-height:100%;'; 
                }
                break;
        }
    } else{
        $f_mh = '';
    }
    return $f_mw . ' ' . $f_mh;
}
