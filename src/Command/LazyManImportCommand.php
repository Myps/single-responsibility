<?php

namespace App\Command;

use App\Service\ILazyManImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LazyManImportCommand extends Command
{
    protected static $defaultName = 'plant:import';

    /** @var ILazyManImportService */
    private $importService;

    /**
     * PlantImportCommand constructor.
     * @param ILazyManImportService $importService
     */
    public function __construct(ILazyManImportService $importService)
    {

        parent::__construct();
        $this->importService = $importService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import plant using xml file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->note("Import started.");

        try {
            $this->importService->run();

            $io->success('Import finished.');
        } catch (\Exception $exception) {
            $io->note($exception->getMessage());
            $io->error('Import failed.');
        }
    }
}
