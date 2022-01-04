<?php

/**
 * Clevada: #1 Free Business Suite and Website Builder.
 *
 * Copyright (C) 2021  Chimilevschi Iosif Gabriel, https://clevada.com.
 *
 * LICENSE:
 * Clevada is licensed under the GNU General Public License v3.0
 * Permissions of this strong copyleft license are conditioned on making available complete source code 
 * of licensed works and modifications, which include larger works using a licensed work, under the same license. 
 * Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.
 *    
 * @copyright   Copyright (c) 2021, Chimilevschi Iosif Gabriel, https://clevada.com.
 * @license     https://opensource.org/licenses/GPL-3.0  GPL-3.0 License.
 * @version     2.1.1
 * @author      Chimilevschi Iosif Gabriel <office@clevada.com>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Template extends Model
{
    protected $table = 'sys_config';

    protected $fillable = ['name', 'value'];

    public function __construct()
    {
    }


    /**
     * Get configs from database
     *
     * @return null
     */
    public static function generate_global_css($template_id)
    {
        $css_destination = public_path(). DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . $template_id . '.css';        

        $css_file = fopen($css_destination, "w");

        // 1. IMPORT FONTS
        $fonts_array = array();

        // Global font
        $font_family = get_template_value($template_id, 'font_family') ?? config('defaults.font_family');
        $arr1 = explode(',', $font_family);
        $font_family_import = str_replace('\'', '', $arr1[0]);
        $font_family_import = str_replace(' ', '+', $font_family_import);
        array_push($fonts_array, $font_family_import);

        // Headings font
        $font_family_headings = get_template_value($template_id, 'font_family_headings') ?? config('defaults.font_family');
        $arr2 = explode(',', $font_family_headings);
        $font_family_headings_import = str_replace('\'', '', $arr2[0]);
        $font_family_headings_import = str_replace(' ', '+', $font_family_headings_import);
        array_push($fonts_array, $font_family_headings_import);

        // Nav font
        $font_family_nav = get_template_value($template_id, 'font_family_nav') ?? config('defaults.font_family');
        $arr3 = explode(',', $font_family_nav);
        $font_family_nav_import = str_replace('\'', '', $arr3[0]);
        $font_family_nav_import = str_replace(' ', '+', $font_family_nav_import);
        array_push($fonts_array, $font_family_nav_import);

        // Footer font
        $font_family_footer = get_template_value($template_id, 'font_family_footer') ?? config('defaults.font_family');
        $arr4 = explode(',', $font_family_footer);
        $font_family_footer_import = str_replace('\'', '', $arr4[0]);
        $font_family_footer_import = str_replace(' ', '+', $font_family_footer_import);
        array_push($fonts_array, $font_family_footer_import);

        $fonts_array = array_unique($fonts_array);
        $fonts_list = '';
        foreach ($fonts_array as $font_import) {
            $fonts_list = $fonts_list . '&family=' . $font_import;
        }

        if (substr($fonts_list, 0, 8) == '&family=') $fonts_list = substr($fonts_list, 8);
        $import_url = 'https://fonts.googleapis.com/css2?family=' . $fonts_list . '&display=swap';

        $write_import = "@import url('" . $import_url . "');\n ";
        fwrite($css_file, $write_import);


        // 2. FONTS AND COLORS
        $font_color = get_template_value($template_id, 'font_color') ?? config('defaults.font_color');
        $bg_color = get_template_value($template_id, 'bg_color') ?? config('defaults.bg_color');
        $headings_color = get_template_value($template_id, 'headings_color') ?? config('defaults.font_color');
        $light_color = get_template_value($template_id, 'light_color') ?? config('defaults.font_color');
        $font_size = get_template_value($template_id, 'font_size') ?? config('defaults.font_size');
        $h1_size = get_template_value($template_id, 'h1_size') ?? config('defaults.h1_size');
        $h2_size = get_template_value($template_id, 'h2_size') ?? config('defaults.h2_size');
        $h3_size = get_template_value($template_id, 'h3_size') ?? config('defaults.h3_size');
        $h4_size = get_template_value($template_id, 'h4_size') ?? config('defaults.h4_size');

        $write = "body { font-family: $font_family !important; color: $font_color !important; background-color: $bg_color !important; font-size: $font_size !important;} ";
        fwrite($css_file, $write);

        $write = "h1, h2, h3, h4 {  font-family: $font_family_headings !important; color: $headings_color !important;} ";
        fwrite($css_file, $write);

        $write = ".light { color: $light_color !important;} ";
        fwrite($css_file, $write);

        $write = ".navbar, .navbar2, .navbar3 { font-family: $font_family_nav !important;} ";
        fwrite($css_file, $write);

        $write = "#footer { font-family: $font_family_footer !important;} ";
        fwrite($css_file, $write);

        $write = "h1 { font-size: $h1_size !important; line-height: 1.5em; margin-bottom: 20px; } ";
        fwrite($css_file, $write);

        $write = "h2 { font-size: $h2_size !important; line-height: 1.2em;} ";
        fwrite($css_file, $write);

        $write = "h3 { font-size: $h3_size !important; line-height: 1em;} ";
        fwrite($css_file, $write);

        $write = "h4 { font-size: $h4_size !important;} ";
        fwrite($css_file, $write);


        // 2. LINKS
        $link_color = get_template_value($template_id, 'link_color') ?? config('defaults.link_color');
        $link_color_hover = get_template_value($template_id, 'link_color_hover') ?? config('defaults.link_color_hover');
        $link_decoration = get_template_value($template_id, 'link_decoration') ?? config('defaults.link_decoration');
        $link_hover_decoration = get_template_value($template_id, 'link_hover_decoration') ?? config('defaults.link_hover_decoration');

        //$write = "a { color: $link_color !important; text-decoration: $link_decoration !important; text-underline-offset: 0.25em;} ";

        //$write .= "a:hover { color: $link_color_hover !important; text-decoration: $link_hover_decoration !important; text-underline-offset: 0.25em;} ";
        //fwrite($css_file, $write);

        // 3. BUTTONS
        $button1_bg_color = get_template_value($template_id, 'button1_bg_color') ?? config('defaults.button_bg_color');
        $button1_font_color = get_template_value($template_id, 'button1_font_color') ?? config('defaults.button_font_color');
        $button1_bg_color_hover = get_template_value($template_id, 'button1_bg_color_hover') ?? config('defaults.button_bg_color_hover');
        $button1_font_color_hover = get_template_value($template_id, 'button1_font_color_hover') ?? config('defaults.button_font_color');
        $button1_border_color = get_template_value($template_id, 'button1_border_color') ?? config('defaults.button_bg_color');
        $button1_border_color_hover = get_template_value($template_id, 'button1_border_color_hover') ?? config('defaults.button_bg_color_hover');

        $button2_bg_color = get_template_value($template_id, 'button2_bg_color') ?? config('defaults.button_bg_color');
        $button2_font_color = get_template_value($template_id, 'button2_font_color') ?? config('defaults.button_font_color');
        $button2_bg_color_hover = get_template_value($template_id, 'button2_bg_color_hover') ?? config('defaults.button_bg_color_hover');
        $button2_font_color_hover = get_template_value($template_id, 'button2_font_color_hover') ?? config('defaults.button_font_color');
        $button2_border_color = get_template_value($template_id, 'button2_border_color') ?? config('defaults.button_bg_color');
        $button2_border_color_hover = get_template_value($template_id, 'button2_border_color_hover') ?? config('defaults.button_bg_color_hover');

        $button3_bg_color = get_template_value($template_id, 'button3_bg_color') ?? config('defaults.button_bg_color');
        $button3_font_color = get_template_value($template_id, 'button3_font_color') ?? config('defaults.button_font_color');
        $button3_bg_color_hover = get_template_value($template_id, 'button3_bg_color_hover') ?? config('defaults.button_bg_color_hover');
        $button3_font_color_hover = get_template_value($template_id, 'button3_font_color_hover') ?? config('defaults.button_font_color');
        $button3_border_color = get_template_value($template_id, 'button3_border_color') ?? config('defaults.button_bg_color');
        $button3_border_color_hover = get_template_value($template_id, 'button3_border_color_hover') ?? config('defaults.button_bg_color_hover');

        $write = ".btn1, .btn1 a { color: $button1_font_color !important; background-color: $button1_bg_color; border-color: $button1_border_color;} \n";
        $write .= ".btn1:hover, .btn1 a:hover { color: $button1_font_color_hover !important; background-color: $button1_bg_color_hover; border-color: $button1_border_color_hover;} \n";

        $write .= ".btn2, .btn2 a { color: $button2_font_color !important; background-color: $button2_bg_color; border-color: $button2_border_color;} \n";
        $write .= ".btn2:hover, .btn2 a:hover { color: $button2_font_color_hover !important; background-color: $button2_bg_color_hover; border-color: $button2_border_color_hover;} \n";

        $write .= ".btn3, .btn3 a { color: $button3_font_color !important; background-color: $button3_bg_color; border-color: $button3_border_color;} \n";
        $write .= ".btn3:hover, .btn3 a:hover { color: $button3_font_color_hover !important; background-color: $button3_bg_color_hover; border-color: $button3_border_color_hover;} \n";

        fwrite($css_file, $write);

        // 4. BREADCRUMBS
        $breadcrumb_style = get_template_value($template_id, 'breadcrumb_style') ?? 'simple';
        $breadcrumb_bg_color = get_template_value($template_id, 'breadcrumb_bg_color') ?? config('defaults.bg_color');
        $breadcrumb_border_color = get_template_value($template_id, 'breadcrumb_border_color') ?? config('defaults.bg_color');
        $breadcrumb_font_color = get_template_value($template_id, 'breadcrumb_font_color') ?? config('defaults.font_color');
        $breadcrumb_link_color = get_template_value($template_id, 'breadcrumb_link_color') ?? config('defaults.link_color');
        $breadcrumb_link_color_hover = get_template_value($template_id, 'breadcrumb_link_color_hover') ?? config('defaults.link_color_hover');
        $breadcrumb_link_decoration = get_template_value($template_id, 'breadcrumb_link_decoration') ?? config('defaults.link_decoration');
        $breadcrumb_link_hover_decoration = get_template_value($template_id, 'breadcrumb_link_hover_decoration') ?? config('defaults.link_hover_decoration');
        $breadcrumb_link_font_weight = get_template_value($template_id, 'breadcrumb_link_font_weight') ?? 'normal';

        $write = ".breadcrumb { color: $breadcrumb_font_color !important;} ";

        if ($breadcrumb_style == 'box') {
            $write .= ".breadcrumb { padding: 5px 10px; background-color: $breadcrumb_bg_color !important; border: 1px solid  $breadcrumb_border_color !important;} ";
        }

        $write .= ".breadcrumb, .breadcrumb-item+.breadcrumb-item::before { color: $breadcrumb_font_color !important;} ";

        $write .= ".breadcrumb a { color: $breadcrumb_link_color !important; text-decoration: $breadcrumb_link_decoration !important; font-weight: $breadcrumb_link_font_weight; text-underline-offset: 0.25em;} ";

        $write .= ".breadcrumb  a:hover { color: $breadcrumb_link_color_hover !important; text-decoration: $breadcrumb_link_hover_decoration !important; text-underline-offset: 0.25em;} ";

        fwrite($css_file, $write);

        // 5. MAIN NAVBAR
        $navbar_font_size = get_template_value($template_id, 'navbar_font_size') ?? config('defaults.font_size');
        $navbar_link_font_weight = get_template_value($template_id, 'navbar_link_font_weight') ?? 'normal';
        $navbar_bg_color = get_template_value($template_id, 'navbar_bg_color') ?? config('defaults.nav_bg_color');
        $navbar_font_color = get_template_value($template_id, 'navbar_font_color') ?? config('defaults.nav_font_color');
        $navbar_link_color = get_template_value($template_id, 'navbar_link_color') ?? config('defaults.nav_link_color');
        $navbar_link_color_hover = get_template_value($template_id, 'navbar_link_color_hover') ?? config('defaults.nav_link_color_hover');
        $navbar_link_color_underline = get_template_value($template_id, 'navbar_link_color_underline') ?? config('defaults.nav_link_color_hover');
        $navbar_link_decoration = get_template_value($template_id, 'navbar_link_decoration') ?? 'none';
        $navbar_link_hover_decoration = get_template_value($template_id, 'navbar_link_hover_decoration') ?? config('defaults.nav_link_hover_decoration');
        $navbar_link_hover_decoration_animation = get_template_value($template_id, 'navbar_link_hover_decoration_animation') ?? 'none';
        $navbar_shaddow = get_template_value($template_id, 'navbar_shaddow');

        $write = ".navbar { font-size: $navbar_font_size !important; color: $navbar_font_color !important; background-color: $navbar_bg_color !important;} ";

        $write .= ".navbar a { color: $navbar_link_color !important; text-decoration: $navbar_link_decoration !important; font-weight: $navbar_link_font_weight !important; -webkit-text-decoration-color: $navbar_link_color_underline; text-decoration-color: $navbar_link_color_underline !important;} ";

        $write .= ".navbar a:hover { color: $navbar_link_color_hover !important; text-decoration: $navbar_link_hover_decoration !important; -webkit-text-decoration-color: $navbar_link_color_underline; text-decoration-color: $navbar_link_color_underline !important;} ";
        fwrite($css_file, $write);

        if ($navbar_link_hover_decoration == 'underline' && $navbar_link_hover_decoration_animation == 'slide_left') {
            if ($navbar_link_font_weight == 'bold') $underline_weight = '0.15em';
            else $underline_weight = '0.1em';

            $write = ".navbar a, .navbar a:hover { text-decoration: none !important;} ";
            $write .= ".navbar a {text-decoration: $navbar_link_decoration !important; -webkit-text-decoration-color: $navbar_link_color_underline; text-decoration-color: $navbar_link_color_underline !important; position: relative !important;} ";
            $write .= ".navbar a:hover {text-decoration: none !important;} ";
            $write .= ".navbar a::before { content: '';	position: absolute; display: block; width: 100%; height: $underline_weight; bottom: 0; left: 0; background-color: $navbar_link_color_underline; transform: scaleX(0); transform-origin: top left; transition: transform 0.3s ease;} ";
            $write .= ".navbar a:hover::before {transform: scaleX(1);} ";
            fwrite($css_file, $write);
        }

        if ($navbar_link_hover_decoration == 'underline' && $navbar_link_hover_decoration_animation == 'slide_right') {
            if ($navbar_link_font_weight == 'bold') $underline_weight = '0.15em';
            else $underline_weight = '0.1em';

            $write = ".navbar a, .navbar a:hover { text-decoration: none !important;} ";
            $write .= ".navbar a {text-decoration: $navbar_link_decoration !important; -webkit-text-decoration-color: $navbar_link_color_underline; text-decoration-color: $navbar_link_color_underline !important; position: relative !important;} ";
            $write .= ".navbar a:hover {text-decoration: none !important;} ";
            $write .= ".navbar a::before { content: '';	position: absolute; display: block; width: 100%; height: $underline_weight; bottom: 0; left: 0; background-color: $navbar_link_color_underline; transform: scaleX(0); transform-origin: top left; transition: transform 0.3s ease;} ";
            $write .= ".navbar a:hover::before {transform: scaleX(1);} ";
            fwrite($css_file, $write);
        }

        if ($navbar_link_hover_decoration == 'underline' && $navbar_link_hover_decoration_animation == 'scale') {
            if ($navbar_link_font_weight == 'bold') $underline_weight = '0.15em';
            else $underline_weight = '0.1em';

            $write = ".navbar a, .navbar a:hover { text-decoration: none !important;} ";
            $write .= ".animation {text-decoration: $navbar_link_decoration !important; -webkit-text-decoration-color: $navbar_link_color_underline; text-decoration-color: $navbar_link_color_underline !important; position: relative !important;} ";
            $write .= ".animation:hover {text-decoration: none !important;} ";
            $write .= ".animation::before { content: ''; position: absolute; display: block; width: 100%; height: $underline_weight; bottom: 0; left: 0; background-color: $navbar_link_color_underline; transform: scaleX(0);	transition: transform 0.3s ease;} ";
            $write .= ".animation:hover::before {transform: scaleX(1);} ";
            fwrite($css_file, $write);
        }


        if ($navbar_shaddow == 'on') {
            $write = ".navbar {box-shadow: 0 5px 8px 0 rgba(0,0,0,0.08);} ";
            fwrite($css_file, $write);
        }


        // 6. NAVBAR 2
        $navbar2_font_size = get_template_value($template_id, 'navbar2_font_size') ?? config('defaults.font_size');
        $navbar2_bg_color = get_template_value($template_id, 'navbar2_bg_color') ?? config('defaults.nav_bg_color');
        $navbar2_font_color = get_template_value($template_id, 'navbar2_font_color') ?? config('defaults.nav_font_color');
        $navbar2_link_color = get_template_value($template_id, 'navbar2_link_color') ?? config('defaults.nav_link_color');
        $navbar2_link_color_hover = get_template_value($template_id, 'navbar2_link_color_hover') ?? 'none';

        $write = ".navbar2 { font-size: $navbar2_font_size !important; color: $navbar2_font_color !important; background-color: $navbar2_bg_color !important;} ";
        $write .= ".navbar2 a { color: $navbar2_link_color !important;} ";
        $write .= ".navbar2 a:hover { color: $navbar2_link_color_hover !important; text-decoration: none !important;} ";
        fwrite($css_file, $write);


        // 7. DROPDOWN
        $dropdown_font_size = get_template_value($template_id, 'dropdown_font_size') ?? config('defaults.font_size');
        $dropdown_font_color = get_template_value($template_id, 'dropdown_font_color') ?? config('defaults.dropdown_font_color');
        $dropdown_link_font_weight = get_template_value($template_id, 'dropdown_link_font_weight') ?? 'mormal';
        $dropdown_bg_color = get_template_value($template_id, 'dropdown_bg_color') ?? config('defaults.dropdown_bg_color');
        $dropdown_link_color = get_template_value($template_id, 'dropdown_link_color') ?? config('defaults.dropdown_link_color');
        $dropdown_link_color_hover = get_template_value($template_id, 'dropdown_link_color_hover') ?? config('defaults.dropdown_link_color_hover');
        $dropdown_link_color_underline = get_template_value($template_id, 'dropdown_link_color_underline') ?? config('defaults.dropdown_link_color');
        $dropdown_link_decoration = get_template_value($template_id, 'dropdown_link_decoration') ?? 'none';
        $dropdown_link_hover_decoration = get_template_value($template_id, 'dropdown_link_hover_decoration') ?? 'none';
        $dropdown_link_hover_decoration_animation = get_template_value($template_id, 'dropdown_link_hover_decoration_animation') ?? 'none';
        $dropdown_shaddow = get_template_value($template_id, 'dropdown_shaddow');

        $write = ".dropdown-menu { font-size: $dropdown_font_size !important; color: $dropdown_font_color !important; background-color: $dropdown_bg_color !important; -webkit-text-decoration-color: $navbar_link_color_underline; text-decoration-color: $navbar_link_color_underline !important; } ";
        $write .= ".dropdown-menu a { color: $dropdown_link_color !important; font-size: $dropdown_font_size !important; font-weight: $dropdown_link_font_weight !important; text-decoration: $dropdown_link_decoration !important; -webkit-text-decoration-color: $navbar_link_color_underline; text-decoration-color: $navbar_link_color_underline !important; } ";
        $write .= ".dropdown-menu a:hover { color: $dropdown_link_color_hover !important; text-decoration: $dropdown_link_decoration !important;} ";
        fwrite($css_file, $write);

        if ($dropdown_shaddow == 'on') {
            $write = ".dropdown-menu {-webkit-box-shadow: 0 15px 30px 0 rgb(0 0 0 / 20%); box-shadow: 0 15px 30px 0 rgb(0 0 0 / 20%);}";
            fwrite($css_file, $write);
        }


        // 8. NAVBAR 3 (NOTIFICATIONS)
        $navbar3_font_size = get_template_value($template_id, 'navbar3_font_size') ?? config('defaults.font_size');
        $navbar3_bg_color = get_template_value($template_id, 'navbar3_bg_color') ?? config('defaults.nav_bg_color');
        $navbar3_font_color = get_template_value($template_id, 'navbar3_font_color') ?? config('defaults.nav_font_color');
        $navbar3_link_color = get_template_value($template_id, 'navbar3_link_color') ?? config('defaults.nav_link_color');
        $navbar3_link_color_hover = get_template_value($template_id, 'navbar3_link_color_hover') ?? config('defaults.nav_link_color');
        $navbar3_link_decoration = get_template_value($template_id, 'navbar3_link_decoration') ?? 'none';
        $navbar3_link_hover_decoration = get_template_value($template_id, 'navbar3_link_hover_decoration') ?? 'none';

        $write = ".navbar3 { font-size: $navbar3_font_size !important; color: $navbar3_font_color !important; background-color: $navbar3_bg_color !important;} ";

        $write .= ".navbar3 a { color: $navbar3_link_color !important; text-decoration: $navbar3_link_decoration !important;} ";

        $write .= ".navbar3 a:hover { color: $navbar3_link_color_hover !important; text-decoration: $navbar3_link_hover_decoration !important;} ";

        fwrite($css_file, $write);


        // 9. FOOTER (PRIMARY)
        $footer_font_size = get_template_value($template_id, 'footer_font_size') ?? config('defaults.font_size');
        $footer_bg_color = get_template_value($template_id, 'footer_bg_color') ?? config('defaults.nav_bg_color');
        $footer_font_color = get_template_value($template_id, 'footer_font_color') ?? config('defaults.nav_font_color');
        $footer_link_color = get_template_value($template_id, 'footer_link_color') ?? config('defaults.nav_link_color');
        $footer_link_color_hover = get_template_value($template_id, 'footer_link_color_hover') ?? config('defaults.nav_link_color');
        $footer_link_color_underline = get_template_value($template_id, 'footer_link_color_underline') ?? config('defaults.nav_link_color');
        $footer_link_decoration = get_template_value($template_id, 'footer_link_decoration') ?? 'none';
        $footer_link_hover_decoration = get_template_value($template_id, 'footer_link_hover_decoration') ?? 'none';

        $write = ".footer { font-size: $footer_font_size !important; color: $footer_font_color !important; background-color: $footer_bg_color !important;} ";

        $write .= ".footer a { color: $footer_link_color !important; text-decoration: $footer_link_decoration !important; -webkit-text-decoration-color: $footer_link_color_underline; text-decoration-color: $footer_link_color_underline !important; } ";

        $write .= ".footer a:hover { color: $footer_link_color_hover !important; text-decoration: $footer_link_hover_decoration !important; -webkit-text-decoration-color: $footer_link_color_underline; text-decoration-color: $footer_link_color_underline !important;} ";

        fwrite($css_file, $write);

        // FOOTER SECONDARY)
        $footer2_font_size = get_template_value($template_id, 'footer2_font_size') ?? config('defaults.font_size');
        $footer2_bg_color = get_template_value($template_id, 'footer2_bg_color') ?? config('defaults.nav_bg_color');
        $footer2_font_color = get_template_value($template_id, 'footer2_font_color') ?? config('defaults.nav_font_color');
        $footer2_link_color = get_template_value($template_id, 'footer2_link_color') ?? config('defaults.nav_link_color');
        $footer2_link_color_hover = get_template_value($template_id, 'navbar3_link_color_hover') ?? config('defaults.nav_link_color');
        $footer2_link_color_underline = get_template_value($template_id, 'footer2_link_color_underline') ?? config('defaults.nav_link_color');
        $footer2_link_decoration = get_template_value($template_id, 'footer2_link_decoration') ?? 'none';
        $footer2_link_hover_decoration = get_template_value($template_id, 'footer2_link_hover_decoration') ?? 'none';

        $write = ".footer2 { font-size: $footer2_font_size !important; color: $footer2_font_color !important; background-color: $footer2_bg_color !important;} ";

        $write .= ".footer2 a { color: $footer2_link_color !important; text-decoration: $footer2_link_decoration !important; -webkit-text-decoration-color: $footer2_link_color_underline; text-decoration-color: $footer2_link_color_underline !important; } ";

        $write .= ".footer2 a:hover { color: $footer2_link_color_hover !important; text-decoration: $footer2_link_hover_decoration !important; -webkit-text-decoration-color: $footer2_link_color_underline; text-decoration-color: $footer2_link_color_underline !important; } ";

        fwrite($css_file, $write);


        // 10. COMMUNITY
        $forum_card_header_bg_color = get_template_value($template_id, 'forum_card_header_bg_color') ?? '#16537E';
        $forum_card_header_link_color = get_template_value($template_id, 'forum_card_header_link_color') ?? '#FFFFFF';
        $forum_card_header_font_color = get_template_value($template_id, 'forum_card_header_font_color') ?? '#FFFFFF';

        $forum_categ_link_color = get_template_value($template_id, 'forum_categ_link_color') ?? '#16537E';
        $forum_categ_link_color_hover = get_template_value($template_id, 'forum_categ_link_color_hover') ?? config('defaults.link_color_hover');
        $forum_categ_link_color_underline = get_template_value($template_id, 'forum_categ_link_color_underline') ?? config('defaults.link_color_hover');
        $forum_categ_bg_color = get_template_value($template_id, 'forum_categ_bg_color') ?? '#F8F9FA';

        $forum_categ_border_color = get_template_value($template_id, 'forum_categ_border_color') ?? '#F8F9FA';
        $forum_categ_font_color = get_template_value($template_id, 'forum_categ_font_color') ?? config('defaults.font_color');
        $forum_categ_link_decoration = get_template_value($template_id, 'forum_categ_link_decoration') ?? 'none';
        $forum_categ_link_hover_decoration = get_template_value($template_id, 'forum_categ_link_hover_decoration') ?? 'none';

        $forum_categ_font_size = get_template_value($template_id, 'forum_categ_font_size') ?? config('defaults.font_size');
        $forum_categ_font_weight = get_template_value($template_id, 'forum_categ_font_weight') ?? 'normal';

        $forum_subcateg_font_size = get_template_value($template_id, 'forum_subcateg_font_size') ?? config('defaults.font_size');
        $forum_subcateg_font_weight = get_template_value($template_id, 'forum_subcateg_font_weight') ?? 'normal';

        $write = ".forum-card-header { color: $forum_card_header_font_color !important; background-color: $forum_card_header_bg_color !important;} ";
        $write .= ".forum-card-header a { color: $forum_card_header_link_color !important;} ";

        $write .= ".forum-categ-card-body, .forum-categ-card-body table { background-color: $forum_categ_bg_color !important; color: $forum_categ_font_color !important; border-color: $forum_categ_border_color !important;} ";

        $write .= ".forum-categ-link { color: $forum_categ_link_color !important; font-size: $forum_categ_font_size !important; text-decoration: $forum_categ_link_decoration !important; font-weight: $forum_categ_font_weight !important; } ";
        $write .= ".forum-categ-link:hover { color: $forum_categ_link_color_hover !important; text-decoration: $forum_categ_link_hover_decoration !important; -webkit-text-decoration-color: $forum_categ_link_color_underline; text-decoration-color: $forum_categ_link_color_underline !important;} ";

        $write .= ".forum-subcateg-link { color: $forum_categ_link_color !important; font-size: $forum_subcateg_font_size !important; text-decoration: $forum_categ_link_decoration !important; font-weight: $forum_subcateg_font_weight !important; } ";
        $write .= ".forum-subcateg-link:hover { color: $forum_categ_link_color_hover !important; text-decoration: $forum_categ_link_hover_decoration !important; -webkit-text-decoration-color: $forum_categ_link_color_underline; text-decoration-color: $forum_categ_link_color_underline !important;} ";

        fwrite($css_file, $write);


        // 11. POSTS		
        $posts_title_link_color = get_template_value($template_id, 'posts_title_link_color') ?? config('defaults.link_color');
        $posts_title_link_color_hover = get_template_value($template_id, 'posts_title_link_color_hover') ?? config('defaults.link_color_hover');
        $posts_title_link_color_underline = get_template_value($template_id, 'posts_title_link_color_underline') ?? config('defaults.link_color_hover');
        $posts_title_link_decoration = get_template_value($template_id, 'posts_title_link_decoration') ?? 'none';
        $posts_title_link_hover_decoration = get_template_value($template_id, 'posts_title_link_hover_decoration') ?? 'none';
        $posts_titles_font_size = get_template_value($template_id, 'posts_titles_font_size') ?? config('defaults.h4_size');

        $post_image_shaddow = get_template_value($template_id, 'post_image_shaddow') ?? 'original';
        $post_image_height = get_template_value($template_id, 'post_image_height') ?? 'original';
        $post_image_force_full_width = get_template_value($template_id, 'post_image_force_full_width');
        $post_tags_style = get_template_value($template_id, 'post_tags_style') ?? 'links';
        $post_tags_box_bg_color = get_template_value($template_id, 'post_tags_box_bg_color') ?? '#999999';
        $post_tags_box_font_color = get_template_value($template_id, 'post_tags_box_font_color') ?? '#ffffff';

        $write = ".listing-box .title {font-size: $posts_titles_font_size !important; } ";

        $write .= ".listing-box .title a {color: $posts_title_link_color !important; text-decoration: $posts_title_link_decoration !important; -webkit-text-decoration-color: $posts_title_link_color_underline; text-decoration-color: $posts_title_link_color_underline !important; } ";
        
        $write .= ".listing-box .title a:hover { color: $posts_title_link_color_hover !important; text-decoration: $posts_title_link_hover_decoration !important; -webkit-text-decoration-color: $posts_title_link_color_underline; text-decoration-color: $posts_title_link_color_underline !important; } ";

        fwrite($css_file, $write);

        if ($post_image_shaddow == 'on') {
            $write = ".post .main-image img {box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%), 0 6px 20px 0 rgb(0 0 0 / 19%);} ";
            fwrite($css_file, $write);
        }

        if ($post_image_force_full_width)
            $write = ".post .main-image img { width: 100% !important; height: auto; }";
        else
            $write = ".post .main-image img { width: auto !important; height: auto; }";
        fwrite($css_file, $write);

        if ($post_image_height != 'original') $write = ".post .main-image img { width: 100vw !important; height: $post_image_height !important; object-fit: cover !important; overflow: hidden !important; }";
        fwrite($css_file, $write);

        if ($post_tags_style == 'box') {
            $write = ".post .tag {padding: 5px 10px; background-color: $post_tags_box_bg_color !important; color: $post_tags_box_font_color !important } ";
            $write .= ".post .tag a {color: $post_tags_box_font_color !important } ";
            fwrite($css_file, $write);
        }


        // 12. USERS AREA
        $users_nav_bg_color = get_template_value($template_id, 'users_nav_bg_color') ?? config('defaults.nav_bg_color');
        $users_nav_font_color = get_template_value($template_id, 'users_nav_font_color') ?? config('defaults.nav_font_color');
        $users_nav_bg_color_hover = get_template_value($template_id, 'users_nav_bg_color_hover') ?? config('defaults.nav_link_bg_hover');
        $users_nav_font_color_hover = get_template_value($template_id, 'users_nav_font_color_hover') ?? config('defaults.nav_link_color_hover');
        $users_nav_active_bg_color = get_template_value($template_id, 'users_nav_active_bg_color') ?? config('defaults.nav_active_bg_color');
        $users_nav_active_font_color = get_template_value($template_id, 'users_nav_active_font_color') ?? config('defaults.nav_active_font_color');

        $write = ".user-nav { color: $users_nav_font_color !important; background-color: $users_nav_bg_color !important; } ";
        $write .= ".user-nav ul li a { color: $users_nav_font_color !important; } ";
        $write .= ".user-nav ul li a:hover { color: $users_nav_font_color_hover !important; background-color: $users_nav_bg_color_hover !important; } ";
        $write .= ".user-nav ul .active, .user-nav ul li .active:hover { color: $users_nav_active_font_color !important; background-color: $users_nav_active_bg_color !important; } ";

        fwrite($css_file, $write);


        // END. Close the file
        fclose($css_file);

        return;
    }
}
