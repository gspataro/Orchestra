<?php

namespace Orchestra\Console\Command;

use RuntimeException;

final class RehearsalCommand extends BaseCommand
{
    protected string $name = 'rehearsal';
    protected ?string $description = 'Run preview server';

    public function main(): void
    {
        $this->output->print('{bold}Starting the rehearsal server...');

        $command = sprintf(
            "php -S %s:%d %s",
            'localhost',
            8080,
            dirname(__DIR__, 2) . '/Rehearsal/resources/server.php'
        );

        $descriptors = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $process = proc_open($command, $descriptors, $pipes);

        if (!is_resource($process)) {
            throw new RuntimeException("Unable to start the rehearsal server");
        }

        $this->output->print('{bold}{fg_green}Rehearsal server running at http://localhost:8080{nl}');

        while (true) {
            $status = proc_get_status($process);

            if (!$status['running']) {
                break;
            }

            usleep(200000);
        }

        proc_close($process);
    }
}
