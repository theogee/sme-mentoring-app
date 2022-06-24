<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require __DIR__."/../db/ChatManager.php";

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Server started...\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;

        $data = json_decode($msg, true);
        $data["dt"] = date("d/m/y h:i A");

        if ($data["scope"] == "mr")
            $chatManager = new \ChatManager(null, __DIR__."/../../utility/dbconn.php", $data["scope"], $data["roomID"]);
        else
            $chatManager = new \ChatManager((int) $data["requestPageID"], __DIR__."/../../utility/dbconn.php", $data["scope"]);

        $chatManager->setUserType($data["userType"]);
        $chatManager->setSenderID((int) $data["senderID"]);
        $chatManager->setMsg($data["msg"]);
        $chatManager->setCreatedOn(date("y-m-d h:i"));

        if ($chatManager->save()) {

            $data = json_encode($data); 
            echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n", $from->resourceId, $data, $numRecv, $numRecv == 1 ? '' : 's');

            foreach ($this->clients as $client) {
                $client->send($data);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}