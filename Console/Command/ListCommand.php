<?php
declare(strict_types=1);

namespace Yireo\ExampleDealersCli\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

use Yireo\ExampleDealers\Api\DealerRepositoryInterface;

class ListCommand extends Command
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
        $this->setName('example_dealers:list')
            ->setDescription('Listing of dealers');
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
        $searchCriteriaBuilder = $this->dealerRepository->getSearchCriteriaBuilder();
        $searchCriteria = $searchCriteriaBuilder->create();
        $dealers = $this->dealerRepository->getItems($searchCriteria);

        $headers = ['ID', 'Dealer Name', 'URL Key', 'Dealer Address'];
        $rows = [];

        foreach ($dealers as $dealer) {
            $rows[] = [
                $dealer->getId(),
                $dealer->getName(),
                $dealer->getUrlKey(),
                $dealer->getAddress()
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($rows);
        $table->render();
    }
}
