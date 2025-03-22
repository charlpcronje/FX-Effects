<?php
namespace fx\effects;

use fx\BaseEffect;
use fx\FXNode;
use DB; // Assuming DB class is globally available

/**
 * $fx.DB.php
 *
 * Defines the DBEffect class, an FX Effect for integrating with databases.
 * Provides functionalities for data persistence and retrieval from a database.
 */

/**
 * Class DBEffect
 *
 * Provides database integration for FX nodes, enabling data persistence and retrieval.
 */
class DBEffect extends BaseEffect {
    /** @var string The database table name */
    private string $table;

    /** @var int The ID of the record in the database */
    private int $id;

    /**
     * DBEffect constructor.
     *
     * @param string $table The database table name.
     * @param int $id The ID of the database record.
     * @access public
     */
    public function __construct(string $table, int $id) {
        parent::__construct("save"); // Executes on save()
        $this->table = $table;
        $this->id = $id;
    }

    /**
     * Mutates the FXNode for database interactions.
     *
     * Handles 'get', 'set', and 'save' events to interact with the database.
     *
     * @param FXNode $node The FXNode instance.
     * @param string $key The property key being accessed or modified.
     * @param mixed|null $value The value being set or retrieved.
     * @access public
     */
    public function mutate(FXNode $node, string $key, &$value) {
        if ($this->execTiming === "get") {
            $stmt = DB::query("SELECT $key FROM $this->table WHERE id = ?", [$this->id]);
            if ($stmt) {
                $value = $stmt[$key];
            }
        } elseif ($this->execTiming === "set") {
            DB::query("UPDATE $this->table SET $key = ? WHERE id = ?", [$value, $this->id]);
        }
    }
}
