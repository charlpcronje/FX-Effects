<?php
class FX {
    private static array $nodes = [];
    private static string $interfaceDir = __DIR__ . "/fx_interfaces/";

    public function __get($name) {
        if (!isset(self::$nodes[$name])) {
            self::$nodes[$name] = (object) [
                "node" => (object) [], // Holds all properties and methods dynamically
                "value" => null, // Holds the value if applicable
                "prototype" => null, // Reference to another FX node as a prototype
            ];
        }
        return self::$nodes[$name];
    }

    public static function Proto($name, array $settings = []): object {
        $newNode = self::__get(uniqid("fx_")); // Auto-create new node
        $newNode->prototype = self::__get($name); // Link to prototype

        // Inherit all subnodes dynamically
        foreach ($newNode->prototype->node as $key => $value) {
            $newNode->node->$key = &$newNode->prototype->node->$key; // Reference shared properties
        }

        // Apply settings (modified properties become local subnodes)
        foreach ($settings as $key => $value) {
            $newNode->node->$key = $value;
        }

        return $newNode;
    }

    public static function ensureImplements(object $node, string $expectedType) {
        // Ensure the interface exists, generating it if necessary
        self::generateInterface($expectedType, $node);

        if (!interface_exists($expectedType)) {
            throw new Exception("Expected interface '$expectedType' does not exist.");
        }

        // Ensure object has required properties and methods
        $requiredMethods = get_class_methods($expectedType);
        $requiredProperties = array_keys(get_class_vars($expectedType));

        foreach ($requiredMethods as $method) {
            if (!isset($node->node->$method) && !method_exists($node->prototype, $method)) {
                throw new Exception("FX Node does not implement required method '$method' from $expectedType.");
            }
        }

        foreach ($requiredProperties as $property) {
            if (!isset($node->node->$property) && !property_exists($node->prototype, $property)) {
                throw new Exception("FX Node does not implement required property '$property' from $expectedType.");
            }
        }
    }

    private static function generateInterface(string $interfaceName, object $node) {
        $filePath = self::$interfaceDir . "$interfaceName.php";

        if (!file_exists($filePath)) {
            if (!is_dir(self::$interfaceDir)) {
                mkdir(self::$interfaceDir, 0777, true);
            }

            $methods = [];
            $properties = [];

            // Scan the node for properties and methods
            foreach ($node->node as $key => $value) {
                if (is_callable($value)) {
                    $methods[] = "    public function $key();";
                } else {
                    $properties[] = "    public \$$key;";
                }
            }

            $interfaceCode = "<?php\n\ninterface $interfaceName {\n" .
                             implode("\n", $properties) . "\n" .
                             implode("\n", $methods) . "\n" .
                             "}\n";

            file_put_contents($filePath, $interfaceCode);
        }

        include_once $filePath;
    }
}
