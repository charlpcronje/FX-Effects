<?php
namespace fx\effects;

/**
 * BaseEffect.php
 *
 * Defines the abstract BaseEffect class, serving as a base for all FX Effect (Plugin) classes.
 * Enforces a consistent structure and execution timing for Effects.
 */

/**
 * Abstract Class BaseEffect
 *
 * Abstract base class for all FX Effect (Plugin) classes.
 * Defines the structure and execution timing for Effects.
 */
abstract class BaseEffect {
    /** @var string Determines when the effect should be executed (e.g., "set", "get", "save") */
    public string $execTiming;

    /**
     * BaseEffect constructor.
     *
     * @param string $execTiming Specifies when the effect should be triggered.
     *                           Possible values: "set", "get", "save". Default is "set".
     * @access public
     */
    public function __construct(string $execTiming = "set")
    {
        $this->execTiming = $execTiming;
    }

    /**
     * Abstract method to be implemented by child classes to define the effect's action.
     *
     * This method will be triggered based on the 'execTiming' property, allowing
     * Effects to modify, validate, or react to changes in FXNodes.
     *
     * @param FXNode $node The FXNode instance being affected.
     * @param string $key The property key being accessed or modified.
     * @param mixed|null $value The value being set or retrieved.
     * @access public
     * @abstract
     */
    abstract public function mutate(FXNode $node, string $key, &$value);
}
