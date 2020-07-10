<?php
declare(strict_types=1);

namespace Yireo\ExampleDealersCli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

use Yireo\ExampleDealers\Api\DealerRepositoryInterface;

class CreateCommand extends Command
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
        $this->setName('example_dealers:create')
            ->setDescription('Create a new dealer')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the dealer')
            ->addArgument('address', InputArgument::OPTIONAL, 'Address of the dealer')
            ->addArgument('description', InputArgument::OPTIONAL, 'Description of the dealer');
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

        $name = (string)$input->getArgument('name');
        if (empty($name)) {
            $question = new Question('Name of the dealer: ', '');
            $name = $helper->ask($input, $output, $question);
        }

        $address = (string)$input->getArgument('address');
        if (empty($address)) {
            $question = new Question('Address of the dealer: ', '');
            $address = $helper->ask($input, $output, $question);
        }

        $description = (string)$input->getArgument('description');
        if (empty($description)) {
            $question = new Question('Description of the dealer: ', '');
            $description = $helper->ask($input, $output, $question);
        }

        $newDealer = $this->dealerRepository->getEmpty();
        $newDealer->setName($name);
        $newDealer->setAddress($address);
        $newDealer->setDescription($description);

        $this->dealerRepository->save($newDealer);
        $output->writeln('Created new dealer');
    }
}
