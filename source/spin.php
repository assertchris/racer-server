<?php

[$loop, $board, $port] = require __DIR__ . "/bootstrap.php";

$op = 1;

print $op++ . ". connecting to {$port}\n";

$board
    ->activate()
    ->done(
        function () use ($loop, $board, $port, &$op) {
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

            $mode = 0;

            $loop->setInterval(function () use (&$op, &$mode, &$control1A, &$control2A, &$enableA, &$control1B, &$control2B, &$enableB) {
                print $op++ . ". changing configuration\n";

                if ($mode === 0) {
                    $control1A->digital = 1;
                    $control2A->digital = 0;
                    $enableA->digital = 1;

                    $control1B->digital = 0;
                    $control2B->digital = 1;
                    $enableB->digital = 1;

                    $mode = 1;
                    return;
                }

                if ($mode === 1) {
                    $enableA->digital = 0;
                    $enableB->digital = 0;

                    $mode = 2;
                    return;
                }

                if ($mode === 2) {
                    $control1A->digital = 0;
                    $control2A->digital = 1;
                    $enableA->digital = 1;

                    $control1B->digital = 1;
                    $control2B->digital = 0;
                    $enableB->digital = 1;

                    $mode = 3;
                    return;
                }

                if ($mode === 3) {
                    $enableA->digital = 0;
                    $enableB->digital = 0;

                    $mode = 0;
                    return;
                }
            }, 2500);
        }
    );

$loop->run();
