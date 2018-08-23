<?php

$port = "/dev/cu.Racer-DevB";

// ---

require __DIR__ . "/../vendor/autoload.php";

// ...load Amp through the React adapter, into Carica!
$adapter = new \Carica\Io\Event\Loop\React();
$adapter->loop(\Amp\ReactAdapter\ReactAdapter::get());

\Carica\Io\Event\Loop\Factory::set($adapter);

$loop = \Carica\Io\Event\Loop\Factory::get();

$board = new \Carica\Firmata\Board(
    \Carica\Io\Stream\Serial\Factory::create($port, 57600)
);

return [$loop, $board, $port];
