<?php

declare(strict_types=1);

namespace Yireo\ExampleDealersCli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Yireo\ExampleDealers\Api\DealerRepositoryInterface;

class UpdateCommand extends Command
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
        $this->setName('example_dealers:update')
            ->setDescription('Update a specific dealer entry')
            ->addArgument('id', InputArgument::OPTIONAL, 'ID of the dealer')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of the dealer')
            ->addArgument('url_key', InputArgument::OPTIONAL, 'URL key of the dealer')
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

        $name = (string)$input->getArgument('name');
        if (empty($name)) {
            $question = new Question('Name of the dealer (' . $dealer->getName() . '): ', '');
            $name = $helper->ask($input, $output, $question);
        }

        if (empty($name)) {
            $name = $dealer->getName();
        }

        $urlKey = (string)$input->getArgument('url_key');
        if (empty($urlKey)) {
            $question = new Question('URL key of the dealer (' . $dealer->getUrlKey() . '): ', '');
            $urlKey = $helper->ask($input, $output, $question);
        }

        if (empty($urlKey)) {
            $urlKey = $dealer->getUrlKey();
        }

        $address = (string)$input->getArgument('address');
        if (empty($address)) {
            $question = new Question('Address of the dealer (' . $dealer->getAddress() . '): ', '');
            $address = $helper->ask($input, $output, $question);
        }

        if (empty($address)) {
            $address = $dealer->getAddress();
        }

        $description = (string)$input->getArgument('description');
        if (empty($description)) {
            $question = new Question('Description of the dealer (' . $dealer->getDescription() . '): ', '');
            $description = $helper->ask($input, $output, $question);
        }

        if (empty($description)) {
            $description = $dealer->getDescription();
        }

        $dealer->setName($name);
        $dealer->setUrlKey($urlKey);
        $dealer->setAddress($address);
        $dealer->setDescription($description);

        $this->dealerRepository->save($dealer);
        $output->writeln('Updated existing dealer');
    }
}
