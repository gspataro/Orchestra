<?php

namespace Orchestra\Console\Command;

use RuntimeException;

final class RehearsalCommand extends BaseCommand
{
    protected string $name = 'rehearsal';
    protected ?string $description = 'Run preview server';

    /**
     * @return array<string,array<string,mixed>>
     */
    public function options(): array
    {
        $options = [];

        $options['host'] = [
            'type' => 'optional',
            'description' => 'Web server hostname (default: localhost)'
        ];

        $options['port'] = [
            'type' => 'optional',
            'description' => 'Web server port (default: 8080)'
        ];

        return $options;
    }

    public function main(): void
    {
        $this->output->print('{bold}Starting the rehearsal server...');

        $host = $this->argument('host') ?? 'localhost';
        $port = $this->argument('port') ?? 8080;

        $command = sprintf(
            "php -S %s:%d %s",
            $host,
            $port,
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

        $this->output->print("{bold}{fg_green}Rehearsal server running at http://{$host}:{$port}{nl}");

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
