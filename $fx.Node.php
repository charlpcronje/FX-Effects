<?php
namespace fx;

/**
 * FXNode.php
 *
 * Represents a dynamic node that supports value storage,
 * array-like access, iteration, and plugin-based extensibility.
 */

/**
 * Class FXNode
 *
 * Represents a dynamic node that supports value storage,
 * array-like access, iteration, and plugin-based extensibility.
 */
class FXNode implements \ArrayAccess, \Iterator {
    /** @var array Holds child nodes */
    private array $nodes = [];

    /** @var mixed The value of the node */
    private mixed $value = null;

    /** @var array List of plugins attached to the node */
    private array $plugins = [];

    /** @var bool Whether the node is read-only */
    private bool $readOnly = false;

    /** @var string|null The bound type of the node, if any */
    private ?string $boundType = null;

    /** @var int Current position for iteration */
    private int $position = 0;

    /**
     * FXNode constructor.
     *
     * @param mixed $value Initial value of the node.
     * @access public
     */
    public function __construct(mixed $value = null) {
        if (is_array($value)) {
            $this->nodes = array_map(fn ($v) => new self($v), $value);
        } else {
            $this->value = $value;
        }
    }

    /**
     * Get or set the value of the node.
     *
     * @param mixed|null $newValue New value to set.
     * @param mixed|null $defaultValue Default value if the current value is null.
     * @return mixed|self Returns the current value or the instance when setting.
     * @throws \Exception If the node is read-only.
     * @access public
     */
    public function val(mixed $newValue = null, mixed $defaultValue = null) {
        if (func_num_args() === 0) {
            return $this->value;
        }
        if ($this->readOnly) {
            throw new \Exception("Cannot modify a read-only node.");
        }
        if ($this->value === null && $defaultValue !== null) {
            $this->value = $defaultValue;
        } else {
            $this->value = $newValue;
        }
        return $this;
    }

    /**
     * Set a key-value pair in the node.
     *
     * @param string $key The key to set.
     * @param mixed $value The value to assign.
     * @param mixed|null $defaultValue Default value if the key does not exist.
     * @return $this
     * @throws \Exception If the node is read-only.
     * @access public
     */
    public function set(string $key, mixed $value, mixed $defaultValue = null) {
        if ($this->readOnly) {
            throw new \Exception("Cannot modify a read-only node.");
        }
        if (!isset($this->nodes[$key]) || $this->nodes[$key] === null) {
            $this->nodes[$key] = new self($defaultValue ?? $value);
        } else {
            $this->nodes[$key]->val($value, $defaultValue);
        }
        return $this;
    }

    /**
     * Retrieve a node property.
     *
     * @param string $key The key to retrieve.
     * @param mixed|null $defaultValue Default value if the key does not exist.
     * @return self The corresponding FXNode instance.
     * @access public
     */
    public function get(string $key, mixed $defaultValue = null): self {
        return $this->nodes[$key] ?? new self($defaultValue);
    }

    /**
     * Set the read-only state of the node.
     *
     * @param bool $state Whether to enable or disable read-only mode.
     * @return $this
     * @access public
     */
    public function readOnly(bool $state = true) {
        $this->readOnly = $state;
        return $this;
    }

    /**
     * Bind a plugin to the node.
     *
     * @param mixed $plugin The plugin to bind.
     * @return $this
     * @access public
     */
    public function bind($plugin) {
        $this->plugins[] = $plugin;
        return $this;
    }

    /**
     * Magic method to get a property using dot notation.
     *
     * @param string $key The key to retrieve.
     * @return self The corresponding FXNode instance.
     * @access public
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * Magic method to set a property using dot notation.
     *
     * @param string $key The key to set.
     * @param mixed $value The value to assign.
     * @access public
     */
    public function __set($key, $value) {
        $this->set($key, $value);
    }

    /**
     * Check if an offset exists (ArrayAccess).
     *
     * @param mixed $offset The offset key.
     * @return bool True if the offset exists, false otherwise.
     * @access public
     */
    public function offsetExists($offset): bool {
        return isset($this->nodes[$offset]);
    }

    /**
     * Get an offset value (ArrayAccess).
     *
     * @param mixed $offset The offset key.
     * @return mixed The value of the offset.
     * @access public
     */
    public function offsetGet($offset): mixed {
        return $this->get($offset);
    }

    /**
     * Set an offset value (ArrayAccess).
     *
     * @param mixed $offset The offset key.
     * @param mixed $value The value to set.
     * @access public
     */
    public function offsetSet($offset, $value): void {
        $this->set($offset, $value);
    }

    /**
     * Unset an offset (ArrayAccess).
     *
     * @param mixed $offset The offset key.
     * @access public
     */
    public function offsetUnset($offset): void {
        unset($this->nodes[$offset]);
    }

    /**
     * Get the current element (Iterator).
     *
     * @return mixed The current node.
     * @access public
     */
    public function current(): mixed {
        return array_values($this->nodes)[$this->position] ?? null;
    }

    /**
     * Move forward to the next element (Iterator).
     * @access public
     */
    public function next(): void {
        $this->position++;
    }

    /**
     * Get the key of the current element (Iterator).
     *
     * @return mixed The current key.
     * @access public
     */
    public function key(): mixed {
        return array_keys($this->nodes)[$this->position] ?? null;
    }

    /**
     * Check if the current position is valid (Iterator).
     *
     * @return bool True if the position is valid, false otherwise.
     * @access public
     */
    public function valid(): bool {
        return isset(array_keys($this->nodes)[$this->position]);
    }

    /**
     * Rewind the iterator to the first element (Iterator).
     * @access public
     */
    public function rewind(): void {
        $this->position = 0;
    }
}