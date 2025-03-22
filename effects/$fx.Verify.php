<?php
namespace fx\effects;

use fx\BaseEffect;
use fx\FXNode;

/**
 * $fx.Verify.php
 *
 * Defines the VerifyEffect class, an FX Effect for data validation.
 * Enforces validation rules on FX node properties when values are set.
 */

/**
 * Class VerifyEffect
 *
 * Provides data validation for FX nodes, ensuring data integrity through rules.
 */
class VerifyEffect extends BaseEffect {
    /** @var array Validation rules to be applied */
    private array $rules;

    /**
     * VerifyEffect constructor.
     *
     * @param array $rules An associative array of validation rules, where keys are property names
     *                     and values are validation types (e.g., 'email', 'numeric').
     * @access public
     */
    public function __construct(array $rules) {
        parent::__construct("set"); // Executes on set()
        $this->rules = $rules;
    }

    /**
     * Mutates the FXNode to apply validation rules on property sets.
     *
     * On 'set' event, checks if the new value for a property adheres to the defined validation rules.
     * Throws an exception if validation fails.
     *
     * @param FXNode $node The FXNode instance.
     * @param string $key The property key being set.
     * @param mixed|null $value The value being set.
     * @throws \Exception If validation fails for any property.
     * @access public
     */
    public function mutate(FXNode $node, string $key, &$value) {
        if (isset($this->rules[$key])) {
            if ($this->rules[$key] === "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("$key must be a valid email.");
            }
            if ($this->rules[$key] === "numeric" && !is_numeric($value)) {
                throw new \Exception("$key must be a number.");
            }
            // Add more validation rules here as needed
        }
    }
}
