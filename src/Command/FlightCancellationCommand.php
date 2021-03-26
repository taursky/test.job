<?php

namespace App\Command;

use App\Service\FlightService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FlightCancellationCommand extends Command
{
    protected static $defaultName = 'vc:flight:cancellation';
    protected static $defaultDescription = 'Flight cancellation';

    protected $flightService;

    public function __construct(string $name = null, FlightService $flightService)
    {
        parent::__construct($name);
        $this->flightService = $flightService;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }
        $this->flightService->flightCancellation();

        $io->success('You executed the command.');

        return Command::SUCCESS;
    }
}
