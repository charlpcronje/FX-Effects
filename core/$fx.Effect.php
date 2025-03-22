<?php
namespace fx\core;

/**
 * $fx.Effect.php
 *
 * Defines the Effect class (formerly PluginFactory) for creating and managing FX Effects (Plugins).
 */

use Exception;

/**
 * Class Effect (formerly PluginFactory)
 *
 * Creates and manages FX Effects (Plugins) dynamically.
 * This factory is responsible for instantiating and configuring FX Effect classes.
 */
class Effect {
    /**
     * Creates a new FX Effect (Plugin) instance dynamically.
     *
     * Dynamically loads and instantiates an Effect class based on the provided name,
     * applying any constructor arguments.
     *
     * @param string $effectName The name of the Effect to create (e.g., 'DB', 'API').
     * @param array $options An array of arguments to pass to the Effect's constructor.
     * @return BaseEffect An instance of the created Effect.
     * @throws Exception If the Effect file is not found or the class does not exist.
     * @access public
     * @static
     */
    public static function create(string $effectName, array $options = []): BaseEffect {
        $className = ucfirst($effectName) . "Effect";
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . '$fx.' . ucfirst(strtolower($effectName)) . '.php';

        if (!file_exists($filePath)) {
            throw new Exception("Effect file not found: $filePath");
        }

        require_once $filePath;

        if (!class_exists("fx\\".$className)) {
            throw new Exception("Class $className not found in file: $filePath");
        }

        $effect = new ("fx\\".$className)(...$options);

        return $effect;
    }
}

