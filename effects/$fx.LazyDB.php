<?php
namespace fx\effects;

use fx\BaseEffect;
use fx\FXNode;
use DB; // Assuming DB class is globally available

/**
 * $fx.LazyDB.php
 *
 * Defines the LazyDBEffect class, an FX Effect for lazy-loading data from a database.
 * Improves performance by fetching data only when accessed.
 */

/**
 * Class LazyDBEffect
 *
 * Provides lazy-loading database integration for FX nodes, optimizing data retrieval.
 */
class LazyDBEffect extends BaseEffect {
    /** @var string The database table name */
    private string $table;

    /** @var array Stores the record set fetched from the database */
    private array $recordSet = [];

    /** @var bool Indicates if the record set has been loaded */
    private bool $isLoaded = false;

    /**
     * LazyDBEffect constructor.
     *
     * @param string $table The database table name.
     * @access public
     */
    public function __construct(string $table) {
        parent::__construct("get"); // Executes on get()
        $this->table = $table;
    }

    /**
     * Mutates the FXNode to lazily load data from the database.
     *
     * On 'get' event, fetches data from the database only when a property is accessed,
     * and caches the record set for subsequent accesses.
     *
     * @param FXNode $node The FXNode instance.
     * @param string $key The property key being accessed.
     * @param mixed|null $value The value being retrieved (passed by reference).
     * @access public
     */
    public function mutate(FXNode $node, string $key, &$value) {
        if (!$this->isLoaded) {
            $this->recordSet = DB::query("SELECT * FROM $this->table");
            $this->isLoaded = true;
        }

        if (!isset($this->recordSet[$key])) {
            return;
        }

        if (!isset($node->nodes[$key])) {
            $record = $this->recordSet[$key];
            $node->nodes[$key] = new FXNode($record);
        }

        $value = $node->nodes[$key];
    }
}
