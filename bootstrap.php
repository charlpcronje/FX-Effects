<?php
require __DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'constants.php';
require __DIR__.DS.'$fx.php';
require __DIR__.DS.'$fx.Node.php';
require __DIR__.DS.'core'.DS.'fx.Bind.php';
require __DIR__.DS.'core'.DS.'fx.Effect.php';
include __DIR__.DS.'core'.DS.'fx.helpers.php';
include __DIR__.DS.'core'.DS.'fx.Router.php';
/**
 * Bootstrap.php
 * Registers an autoloader function for FX classes.
 *
 * This autoloader follows PSR-4 conventions, converting namespace separators
 * to directory separators and including the corresponding PHP file.
 *
 * @access public
 */
spl_autoload_register(function ($class) {
    // Remove leading backslashes
    $class = ltrim($class, '\\');

    // Convert namespace separators to directory separators
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    // Define the base directory
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

    // If the class is within the `fx` namespace, prioritize `$fx.{ClassName}.php`
    if (str_starts_with($class, 'fx\\')) {
        $fxFile = str_replace(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . '$fx.', $file);

        // Try loading `$fx.{ClassName}.php` first
        if (file_exists($baseDir . $fxFile)) {
            require_once $baseDir . $fxFile;
            return;
        }
    }

    // Fallback: Load `{ClassName}.php` if `$fx.{ClassName}.php` was not found
    $fullPath = $baseDir . $file;
    if (file_exists($fullPath)) {
        require_once $fullPath;
    }
});

// Load and instantiate the config globally as soon as possible
$configPath = __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . '$fx.config.php';
if (file_exists($configPath)) {
    $GLOBALS['config'] = require_once $configPath;
}
