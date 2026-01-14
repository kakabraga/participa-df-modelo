<?php

namespace App\Integrations\Python;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PythonRunner
{
    protected string $pathExe;
    protected string $pathVenv;
    protected string $module;
    protected string $flag;

    public function __construct(
        ?string $pathExe = null,
        ?string $pathVenv = null,
        string $module = 'app.cli.run',
        string $flag = '-m'

    ) {
        $this->pathExe = $pathExe ?? base_path('../python-models/.venv/Scripts/python.exe');
        $this->pathVenv = $pathVenv ?? base_path('../python-models/.venv');
        $this->module = $module;
        $this->flag = $flag;
    }

    public function run(array $args = []): array
    {
        $command = $this->criaComandoBase($args);
        $process = $this->iniciaProcesso($command);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return json_decode($process->getOutput(), true, flags: JSON_THROW_ON_ERROR);

    }

    protected function iniciaProcesso(array $command): Process
    {
        $process = new Process($command, base_path('../python-models'));
        $process->setTimeout(120);
        $process->run();

        return $process;
    }

    protected function criaComandoBase(array $args = []): array
    {
        return array_merge(
            [
                $this->pathExe,
                $this->flag,
                $this->module,
            ],
            $args
        );
    }
}
