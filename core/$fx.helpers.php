<?php
namespace fx\core;

/**
 * $fx.helpers.php
 *
 * Defines global helper functions for FX, providing shorthand access to FXNode functionalities.
 */

use fx\FX;

/**
 * Global helper function to get or set a value in an FXNode.
 *
 * If only the `$path` parameter is provided, the function returns
 * the current value of the node. Otherwise, it updates the value.
 *
 * @param string $path The FXNode identifier.
 * @param mixed|null $value The value to set (if provided).
 * @param mixed|null $default The default value if the node is null.
 * @return mixed|FXNode The current value or the FXNode instance.
 * @access public
 */
$val = fn(string $path, mixed $value = null, mixed $default = null) => __fx_variable_handler($path)->val($value, $default);

/**
 * Global helper function to set a key-value pair in an FXNode.
 *
 * @param string $path The FXNode identifier.
 * @param string $key The key to set.
 * @param mixed $value The value to assign.
 * @return FXNode The updated FXNode instance.
 * @access public
 */
$set = fn(string $path, string $key, mixed $value) => __fx_variable_handler($path)->set($key, $value);

/**
 * Global helper function to retrieve a value from an FXNode.
 *
 * @param string $path The FXNode identifier.
 * @param string $key The key to retrieve.
 * @param mixed|null $default The default value if the key does not exist.
 * @return FXNode The FXNode instance corresponding to the key.
 * @access public
 */
$get = fn(string $path, string $key, mixed $default = null) => __fx_variable_handler($path)->get($key, $default);

/**
 * Global helper function to check if an FXNode has a value.
 *
 * @param string $path The FXNode identifier.
 * @return bool True if the node has a value, false otherwise.
 * @access public
 */
$has = fn(string $path) => __fx_variable_handler($path)->val() !== null;

/**
 * Global helper function to execute a callback if an FXNode has a value.
 *
 * @param string $path The FXNode identifier.
 * @param callable $callback The function to execute if the node has a value.
 * @return mixed|null The result of the callback, or null if no value.
 * @access public
 */
$if = fn(string $path, callable $callback) => $has($path) ? $callback(__fx_variable_handler($path)) : null;

/**
 * Global helper function to iterate over child nodes of an FXNode.
 *
 * @param string $path The FXNode identifier.
 * @param callable $callback The function to execute for each child node.
 *                                 Receives the key and value (FXNode) as arguments.
 * @access public
 */
$each = fn(string $path, callable $callback) => fx\__fx_each_helper($path, $callback);

/**
 * Global helper function to ensure a specific type for an FXNode.
 *
 * If the node is not an instance of the specified class, returns a default instance.
 *
 * @param string $path The FXNode identifier.
 * @param string $typeClass The fully qualified class name to check against.
 * @param object $default A default instance of the class.
 * @return object The FXNode instance or the default object.
 * @access public
 */
$type = fn(string $path, string $typeClass, object $default) => __fx_variable_handler($path) instanceof $typeClass ? __fx_variable_handler($path) : $default;

/**
 * Helper function for $each to handle iteration logic.
 *
 * @param string $path The FXNode identifier.
 * @param callable $callback The function to execute for each child node.
 * @access private
 */
function __fx_each_helper(string $path, callable $callback): void {
    $node = __fx_variable_handler($path);
    if (is_iterable($node)) {
        foreach ($node as $key => $value) {
            $callback($key, $value);
        }
    }
}
