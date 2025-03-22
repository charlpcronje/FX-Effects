<?php
namespace fx\effects;

use fx\BaseEffect;
use fx\FXNode;
use WebSocketServer; // Assuming WebSocketServer class is globally available

/**
 * $fx.ws.php
 *
 * Defines the wsEffect class, an FX Effect for real-time synchronization using WebSockets.
 * Enables pushing updates from the backend to the frontend in real-time.
 */

/**
 * Class wsEffect
 *
 * Provides WebSocket synchronization for FX nodes, enabling real-time updates.
 */
class wsEffect extends BaseEffect {
    /** @var string The WebSocket server endpoint URL */
    private string $endpoint;

    /**
     * wsEffect constructor.
     *
     * @param string $endpoint The WebSocket server endpoint URL.
     * @access public
     */
    public function __construct(string $endpoint) {
        parent::__construct("save"); // Executes on save()
        $this->endpoint = $endpoint;
    }

    /**
     * Mutates the FXNode to broadcast updates via WebSocket.
     *
     * On 'save' event, broadcasts the updated node value to all connected WebSocket clients.
     *
     * @param FXNode $node The FXNode instance.
     * @param string $key The property key being accessed or modified.
     * @param mixed|null $value The value being set or retrieved.
     * @access public
     */
    public function mutate(FXNode $node, string $key, &$value) {
        if ($this->execTiming === "save") {
            self::broadcastUpdate($node, $key, $value);
        }
    }

    /**
     * Broadcasts a node update to all connected WebSocket clients.
     *
     * @param FXNode $node The FXNode instance that was updated.
     * @param string $key The property key that was updated.
     * @param mixed $newValue The new value of the property.
     * @access private
     * @static
     */
    private static function broadcastUpdate(FXNode $node, string $key, $newValue) {
        foreach (WebSocketServer::getConnectedClients() as $client) {
            $client->send(json_encode(["path" => $node->getPath() . "." . $key, "value" => $newValue]));
        }
    }
}
