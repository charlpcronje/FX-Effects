<?php
namespace fx\core;

/**
 * $fx.Bind.php
 *
 * Provides utility classes for binding existing PHP objects into the FX framework.
 */

/**
 * Class Bind
 *
 * Provides utility functions for binding existing PHP objects into FXNodes.
 */
class Bind {
    /**
     * Binds properties of an existing object to an FXNode.
     *
     * Iterates through the properties of the given object and sets them as
     * children of the provided FXNode, allowing seamless integration.
     *
     * @param FXNode $node The FXNode to bind to.
     * @param object $object The existing PHP object to bind.
     * @return FXNode The FXNode instance with object properties bound.
     * @access public
     * @static
     */
    public static function object(FXNode $node, object $object): FXNode
    {
        foreach (get_object_vars($object) as $prop => $val) {
            $node->set($prop, $val);
        }
        return $node;
    }
}
