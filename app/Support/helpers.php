<?php

if (!function_exists('composite_table')) {
    function composite_table($id = '', array $headers = array(), $collection, array $fields = array(), $is_mobile = false)
    {
        $output = "<table id='{$id}'>";
        if ($is_mobile) {

        } else {
            $output .= "<thead><tr>";
            foreach ($headers as $hs) {
                $output .= "<th>{$hs}</th>";
            }
            $output .= "</tr></thead>";
            $output .= "<tbody>";
            foreach ($collection as $c) {
                $output .= "<tr>";
                foreach ($fields as $f) {
                    $output .= "<td>" . $c[$f] . "</td>";
                }
                $output .= "</tr>";
            }
            $output .= "</tbody>";
        }
        $output .= "</table>";

        return $output;
    }
}

if (!function_exists('callback_function_row')) {
    function callback_function_row($callback, $value)
    {
    }
}