<?php


namespace  tpModelFilter;


use think\console\command\Make;


class FilterCommond extends Make
{
    protected $type = "Model";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:filter')
            ->setDescription('Create a new modelFilter class');
    }

    protected function getStub(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'filter.stub';
    }

    protected function getNamespace(string $app): string
    {
        return parent::getNamespace($app) . '\\modelFilter';
    }
}

