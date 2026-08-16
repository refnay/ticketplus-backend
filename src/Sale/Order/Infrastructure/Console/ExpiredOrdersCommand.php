<?php

namespace App\Sale\Order\Infrastructure\Console;

use App\Sale\Order\Application\Expire\OrderExpirator;
use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ExpiredOrdersCommand extends Command
{
    public function __construct(private OrderExpirator $expirator, private OrderRepository $repository)
    {
        parent::__construct('app:orders:expired');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Order[] $orders */
        $orders = $this->repository->findAll();
        
        foreach ($orders as $order) {
            $this->expirator->__invoke($order->id());
        }

        return Command::SUCCESS;
    }
}