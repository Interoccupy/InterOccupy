<?php
/*
Plugin Name: Polylang Auto Translate
Plugin URI: http://moutons.ch
Description: Add auto-translation functionnality to Polylang plugin.
Version: 0.1
Author: El Khalifa Karim
Author URI: http://moutons.ch
License: GPL2
*/

/*  Copyright 2011-2012 K. El Khalifa

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class PolylangAT {
    // used to cache results
    private $languages_list = array();
    
    
    public function __construct() {
        load_plugin_textdomain('polylangat', false, basename( dirname( __FILE__ ) ) . '/languages' );
        add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
        
        add_action('admin_print_footer_scripts', array(&$this,'do_autotranslation_javascript'));
        add_action('wp_ajax_do_autotranslation', array(&$this, 'do_autotranslation_callback'));
        
    }
    
    // adds the Language box in the 'Edit Post' and 'Edit Page' panels (as well as in custom post types panels)
    public function add_meta_boxes($post_type) {
        add_meta_box('plat_box', __("Auto-translate", "polylangat"), array(&$this,'getForm'), $post_type, 'side', 'high');
    }
    
    public function getForm() {
        global $post_ID;
        $post_type = get_post_type($post_ID);

        $lang = ($lg = $this->get_post_language($post_ID)) ? $lg : (isset($_GET['new_lang']) ? $this->get_language($_GET['new_lang']) : $this->get_default_language());

        echo '<p><em>' . sprintf(__("%s from the article in %s.", 'polylangat'),
            '<input type="submit" id="auto-translate" name="auto-translate" value="' . __('Translate', 'polylangat') . '" />',
            $this->dropdown_languages(array('name' => 'post_autotranslate_lang_choice', 'class' => '', 'selected' => $lang ? $lang->slug : ''))
        ) . '</em></p>' .
            '<p><em>' . __("Automatically translate:", "polylangat") . '</em></p>
            <p><input type="checkbox" class="polylangat_wtt" name="polylangat_title_can_change" id="polylangat_title_can_change" checked="checked" /> ' . __("the title", "polylangat") . '<br />
               <input type="checkbox" class="polylangat_wtt" name="polylangat_text_can_change" id="polylangat_text_can_change" checked="checked" /> ' . __("the text", "polylangat") . '</p>';
    }
    
    
    
    // retrieves the dropdown list of the languages
    function dropdown_languages($args = array()) {
        global $post_ID;
        $args = apply_filters('pll_dropdown_language_args', $args);
        $defaults = array('name' => 'lang_choice', 'class' => '', 'add_options' => array(), 'hide_empty' => false, 'value' => 'slug', 'selected' => '');
        extract(wp_parse_args($args, $defaults));


        $out = sprintf('<select name="%1$s" id="%1$s"%2$s>'."\n", esc_attr($name), $class ? ' class="'.esc_attr($class).'"' : '');

        foreach ($this->get_languages_list($args) as $language) {
            
            $value = $this->get_translation('post', $post_ID, $language);
        
            if (!$value || $value == $post_ID) // $value == $post_ID happens if the post has been (auto)saved before changing the language
                $value = '';
            if (isset($_GET['from_post']))
                $value = $this->get_post($_GET['from_post'], $language);

            if($language->slug != $selected) {
                $out .= sprintf("<option value=\"%s\" title=\"%s\">%s</option>\n",
                        esc_attr($value),
                        esc_attr($language->slug),
                        esc_html($language->name)
                );
            }
        }
        $out .= "</select>\n";
        return $out;
    }
    
    // returns the id of the translation of a post or term
    // $type: either 'post' or 'term'
    // $id: post id or term id
    // $lang: object or slug (in the order of preference latest to avoid)
    function get_translation($type, $id, $lang) {
            $translations = $this->get_translations($type, $id);
            $slug = $this->get_language($lang)->slug;
            return isset($translations[$slug]) ? (int) $translations[$slug] : false;
    }

    // returns an array of translations of a post or term
    function get_translations($type, $id) {
            // maybe_unserialize due to useless serialization in versions < 0.9
            return maybe_unserialize(get_metadata($type, $id, '_translations', true)); 
    }

    // returns the language of a post
    public function get_post_language($post_id) {
        $lang = get_the_terms($post_id, 'language' );
        return ($lang) ? reset($lang) : false; // there's only one language per post : first element of the array returned
    }
    
    function get_languages_list($args = array()) {
        // although get_terms is cached, it is efficient to add our own cache
        if (isset($this->languages_list[$cache_key = md5(serialize($args))]))
            return $this->languages_list[$cache_key];

        $defaults = array('hide_empty' => false, 'orderby'=> 'term_group');
        $args = wp_parse_args($args, $defaults);		
        return $this->languages_list[$cache_key] = get_terms('language', $args);
    }
    // returns either the user preferred language or the default language
    function get_default_language() {
        $default_language = $this->get_language(($lg = get_user_meta(get_current_user_id(), 'pll_filter_content', true)) ? $lg : $this->options['default_lang']);
        return apply_filters('pll_get_default_language', $default_language);
    }
    
    // returns the language by its id or its slug
    // Note: it seems that a numeric value is better for performance (3.2.1)
    function get_language($value) {
        $lang = is_object($value) ? $value :
            ((is_numeric($value) || (int) $value) ? get_term((int) $value, 'language') :
            (is_string($value) ? get_term_by('slug', $value , 'language') : // seems it is not cached in 3.2.1
        false));
        return isset($lang) && $lang && !is_wp_error($lang) ? $lang : false;
    }
    // among the post and its translations, returns the id of the post which is in $lang
    function get_post($post_id, $lang) {
            $post_lang = $this->get_post_language($post_id);
            if (!$lang || !$post_lang)
                    return false;

            $lang = $this->get_language($lang);
            return $post_lang->term_id == $lang->term_id ? $post_id : $this->get_translation('post', $post_id, $lang);
    }
        
    
    public function do_autotranslation_javascript() {
        ?>
        <script type="text/javascript" >
        jQuery(document).ready(function($) {
            jQuery("#auto-translate").click(function(event) {
                event.preventDefault();
                
                $(".polylangat-notice").remove();
                
                if($(".polylangat_wtt:checked").length > 0) {
                
                    $("#content-html").click();

                    var data = {
                            action: 'do_autotranslation',
                            to_lang: $("#post_lang_choice").val(),
                            from_lang: $("#post_autotranslate_lang_choice option:selected").attr("title"),
                            post_id: $("#post_autotranslate_lang_choice").val()
                    };



                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: data,
                        success: function(response) {
                            $("#content").val(response);
                            if($("#content").css("display") == "none") {
                                if(tinymce.editors.length) {
                                    tinymce.editors[0].setContent(response.text);
                                }
                            } else {
                                $("#content").val(response.text);
                            }
                            $("#title").focus().val(response.title);
                        },
                        dataType: "json"
                    });
                } else {
                    var alertdiv = $('<div class="polylangat-notice error below-h2"><p>' + <?php echo json_encode(__("Please tick at least one translation options.", "polylangat")); ?> + '</p></div>');
                    $('.wrap > h2').after(alertdiv);
                    
     
                }
                return false;
            });
            
            jQuery(".polylangat_wtt").click(function() {
                if($(".polylangat_wtt:checked").length > 0) {
                    $(".polylangat-notice").remove();
                }
            });
               
        });
        </script>
        <?php
    }

        
        
        

    public function do_autotranslation_callback() {

        $post = get_post($_REQUEST["post_id"], ARRAY_A);

        $output = array('title' => '', 'text' => '');
        
        $title = $post["post_title"];
        $text = $post["post_content"];
        $from = $_REQUEST["from_lang"];
        $to = $_REQUEST["to_lang"];
        $url = "http://mymemory.translated.net/api/get";
        $limit = 80;
        
        
        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,array('q'=>"{$title}",'langpair'=>"{$from}|{$to}"));
        
        $response = curl_exec($ch);
        
        if($response !== false) {
            $json = json_decode($response, true);
            if(isset($json["responseData"]["translatedText"])) {
                $output["title"] = $json["responseData"]["translatedText"];
            }
        }
        curl_close($ch);
        
        
        // the text be carefull of the limitation of 1500 bytes !
        
        $texts = explode("\n", $text);
        
        $to_translate = array('');
        $i = 0;
        while($i < sizeof($texts)) {
            if(strlen($to_translate[sizeof($to_translate) - 1] . $texts[$i]) > $limit) {
                $to_translate[] = $texts[$i] . " ___ ";
            } else {
                 $to_translate[sizeof($to_translate) - 1] .= $texts[$i] . " ___ ";
            }
            
            $i++;
        }
        

        foreach($to_translate as $text) {
            if(strlen(trim($text)) > 0) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_POST,true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,array('q'=>"{$text}",'langpair'=>"{$from}|{$to}"));

                $response = curl_exec($ch);

                if($response !== false) {

                    $json = json_decode($response, true);
                    if(isset($json["responseData"]["translatedText"])) {
                        $output["text"] .= str_replace("___", "
" , $json["responseData"]["translatedText"] . "
");

                    }
                }
                curl_close($ch);
            }
        }

        echo json_encode($output);
        

	die(); // this is required to return a proper result
    }
    
}

new PolylangAT();
?>
