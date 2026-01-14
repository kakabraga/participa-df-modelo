<?php
namespace App\Integrations\Python;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PythonRunner
{
    public function run(array $args = []): string
    {
        $pythonPath = base_path('../python-models/.venv/Scripts/python.exe');
        $workingDir = base_path('../python-models/.venv');

        $command = array_merge(
            [$pythonPath, '-m', 'app.cli.run'],
            $args
        );

        $process = new Process($command, $workingDir);
        $process->setTimeout(120);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
