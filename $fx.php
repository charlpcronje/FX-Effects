<?php
namespace fx;
use fx\FXNode;


/**
 * FX.php
 *
 * Manages globally accessible FXNode instances using a singleton pattern.
 * This file sets up the core FX class and global helper functions.
 */

/**
 * Class FX
 *
 * Manages globally accessible FXNode instances using a singleton pattern.
 */
class FX {
    /**
     * @var array<string, FXNode> Stores singleton instances of FXNode.
     * @access private
     * @static
     */
    private static array $instances = [];

    /**
     * Private constructor to prevent direct instantiation.
     * @access private
     */
    private function __construct() {
        // Private constructor to prevent direct instantiation.
    }

    /**
     * Retrieves a singleton instance of FXNode for a given key.
     *
     * @param string $key The key identifying the FXNode instance.
     * @return FXNode The FXNode instance associated with the key.
     * @access public
     * @static
     */
    public static function getInstance(string $key): FXNode {
        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = new FXNode();
        }
        return self::$instances[$key];
    }
}

/**
 * Handles dynamic variable resolution for FXNode instances.
 *
 * @param string $name The name of the FXNode instance.
 * @return FXNode The corresponding FXNode instance.
 * @access private
 */
function __fx_variable_handler(string $name): FXNode {
    return FX::getInstance($name);
}

/**
 * Automatically registers global variables as FXNode instances.
 *
 * When an undefined class is referenced, this function initializes
 * a corresponding global variable dynamically.
 *
 * @param string $name The name of the variable to be registered.
 * @access public
 */
spl_autoload_register(function ($name) {
    global $$name;
    $$name = __fx_variable_handler($name);
});
