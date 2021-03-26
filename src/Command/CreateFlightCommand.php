<?php

namespace App\Command;

use App\Service\CreateFlight;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateFlightCommand extends Command
{
    protected static $defaultName = 'vc:create:flight';
    protected static $defaultDescription = 'Add one flight';

    protected $createFlight;

    public function __construct(string $name = null, CreateFlight $createFlight)
    {
        parent::__construct($name);
        $this->createFlight = $createFlight;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->createFlight->createFlight();

        $io->success('You created new flight.');

        return Command::SUCCESS;
    }
}
