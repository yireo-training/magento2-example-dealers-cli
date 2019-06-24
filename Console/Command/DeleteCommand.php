<?php
declare(strict_types=1);

namespace Yireo\ExampleDealersCli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Yireo\ExampleDealers\Api\DealerRepositoryInterface;

/**
 * Class DeleteCommand
 * @package Yireo\ExampleDealersCli\Console\Command
 */
class DeleteCommand extends Command
{
    /**
     * @var DealerRepositoryInterface
     */
    private $dealerRepository;

    /**
     * ListCommand constructor.
     * @param DealerRepositoryInterface $dealerRepository
     * @param string|null $name
     */
    public function __construct(
        DealerRepositoryInterface $dealerRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->dealerRepository = $dealerRepository;
    }

    /**
     * Configure this Symfony command
     */
    protected function configure()
    {
        $this->setName('example_dealers:delete')
            ->setDescription('Delete an existing dealer')
            ->addArgument('id', InputArgument::OPTIONAL, 'ID of the dealer');
    }

    /**
     * Execute this Symfony command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $id = (int)$input->getArgument('id');
        if (empty($id)) {
            $question = new Question('ID of the dealer: ', '');
            $id = (int)$helper->ask($input, $output, $question);
        }

        if (empty($id)) {
            $output->writeln('<error>No ID given</error>');
            return;
        }

        $dealer = $this->dealerRepository->getById($id);
        $this->dealerRepository->delete($dealer);

        $output->writeln('Deleted existing dealer with ID ' . $id);
    }
}
