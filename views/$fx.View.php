<?php
namespace fx\views;

use fx\core;

/**
 * $fx.View.php
 *
 * Defines the View class, providing a templating engine for rendering FX nodes in HTML.
 * Supports custom tags for dynamic content, iterations, and conditional rendering.
 */

/**
 * Class View
 *
 * Provides a templating engine for rendering FX nodes in HTML,
 * supporting custom tags and dynamic content.
 */
class View
{
    /**
     * Renders an HTML template file with FX node data.
     *
     * Parses the HTML template, processes custom tags (<fx-repeat>, <fx-if>, <fx-sum>),
     * and replaces placeholders with data from the provided FXNode.
     *
     * @param string $template The filename of the HTML template (without path).
     * @param FXNode $node The FXNode instance containing data to render.
     * @return string The rendered HTML content.
     * @access public
     * @static
     */
    public static function render(string $template, FXNode $node): string
    {
        $html = file_get_contents("views/$template.html");

        // Process repeat regions
        $html = preg_replace_callback('/<fx-repeat data="(.*?)">(.*?)<\/fx-repeat>/s', function ($matches) use ($node) {
            $data = $node->{$matches[1]};
            $output = "";
            foreach ($data as $child) {
                $output .= str_replace("{{item}}", $child->val(), $matches[2]);
            }
            return $output;
        }, $html);

        // Process conditional rendering
        $html = preg_replace_callback('/<fx-if condition="(.*?)">(.*?)<\/fx-if>/s', function ($matches) use ($node) {
            return $node->{$matches[1]}->val() ? $matches[2] : "";
        }, $html);

        // Process summation
        $html = preg_replace_callback('/<fx-sum data="(.*?)">(.*?)<\/fx-sum>/s', function ($matches) use ($node) {
            $data = $node->{$matches[1]};
            return array_sum(array_map(fn ($item) => $item->val(), iterator_to_array($data)));
        }, $html);

        // Process simple value bindings
        return preg_replace_callback('/{{\s*(.*?)\s*}}/', function ($matches) use ($node) {
            return $node->{$matches[1]}->val();
        }, $html);
    }
}

