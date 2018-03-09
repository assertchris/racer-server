<?php

[$loop, $board, $port] = require __DIR__ . "/bootstrap.php";

function respond($request, $content)
{
    return $request->createResponse(
        new \Carica\Io\Network\Http\Response\Content\Text($content . "\n")
    );
}

$op = 1;

print $op++ . ". connecting to {$port}\n";

$board
    ->activate()
    ->done(function () use ($loop, $board, $port, &$op) {
        print $op++ . ". connected to {$port}\n";

        $control1A = $board->pins[2];
        $control1A->mode = \Carica\Firmata\Pin::MODE_OUTPUT;
        $control2A = $board->pins[3];
        $control2A->mode = \Carica\Firmata\Pin::MODE_OUTPUT;
        $enableA = $board->pins[9];
        $enableA->mode = \Carica\Firmata\Pin::MODE_OUTPUT;

        $control1B = $board->pins[4];
        $control1B->mode = \Carica\Firmata\Pin::MODE_OUTPUT;
        $control2B = $board->pins[5];
        $control2B->mode = \Carica\Firmata\Pin::MODE_OUTPUT;
        $enableB = $board->pins[10];
        $enableB->mode = \Carica\Firmata\Pin::MODE_OUTPUT;

        $router = new \Carica\Io\Network\Http\Route();

        $router->match(
            "/forward",
            function (\Carica\Io\Network\Http\Request $request) use (&$op, &$control1A, &$control2A, &$enableA, &$control1B, &$control2B, &$enableB) {
                print $op++ . ". moving forward\n";

                $enableA->digital = 1;
                $enableB->digital = 1;

                $control1A->digital = 1;
                $control2A->digital = 0;

                $control1B->digital = 1;
                $control2B->digital = 0;

                return respond($request, "done");
            }
        );

        $router->match(
            "/stop",
            function (\Carica\Io\Network\Http\Request $request) use (&$op, &$control1A, &$control2A, &$enableA, &$control1B, &$control2B, &$enableB) {
                print $op++ . ". stopping\n";

                $enableA->digital = 0;
                $enableB->digital = 0;

                return respond($request, "done");
            }
        );


        $server = new \Carica\Io\Network\Http\Server($router);
        $server->listen(8080);
    });

$loop->run();
