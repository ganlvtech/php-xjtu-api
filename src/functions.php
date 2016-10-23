<?php
if (!function_exists('collapse_whitespace')) {
    function collapse_whitespace($str)
    {
        return trim(preg_replace('/[\s\0\x0B\xC2\xA0]+/su', ' ', html_entity_decode($str)));
    }
}
if (!function_exists('remove_html_tags')) {
    function remove_html_tags($html)
    {
        return preg_replace('/<.*?>/su', ' ', $html);
    }
}
if (!function_exists('html_to_text')) {
    function html_to_text($html)
    {
        return collapse_whitespace(remove_html_tags($html));
    }
}
