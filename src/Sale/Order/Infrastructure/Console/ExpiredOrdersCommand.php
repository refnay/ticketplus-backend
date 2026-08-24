<?php

namespace App\Sale\Order\Infrastructure\Console;

use App\Sale\Order\Application\Expire\OrderExpirator;
use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderRepository;
use App\Shared\Domain\Persistence\TransactionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class ExpiredOrdersCommand extends Command
{
    public function __construct(
        private OrderExpirator $expirator,
        private OrderRepository $repository,
        private TransactionService $transaction,
    ) {
        parent::__construct('app:orders:expired');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Order[] $orders */
        $orders = $this->repository->findExpireds();

        foreach ($orders as $order) {
            try {
                $this->transaction->begin();
                $this->expirator->__invoke($order->id());
                $this->transaction->commit();
            } catch (Throwable) {
                $this->transaction->rollback();
            }
        }

        return Command::SUCCESS;
    }
}
