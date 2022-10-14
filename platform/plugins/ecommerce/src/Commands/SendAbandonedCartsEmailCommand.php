<?php

namespace Botble\Ecommerce\Commands;

use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use EmailHandler;
use Illuminate\Console\Command;
use OrderHelper;
use Throwable;

class SendAbandonedCartsEmailCommand extends Command
{
    /**
     * @var OrderInterface
     */
    public $orderRepository;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:abandoned-carts:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails abandoned carts';

    /**
     * @param OrderInterface $orderRepository
     */
    public function __construct(OrderInterface $orderRepository)
    {
        parent::__construct();
        $this->orderRepository = $orderRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = $this->orderRepository->getModel()
            ->with(['user', 'address'])
            ->where('is_finished', 0)
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            $email = $order->user->email ?: $order->address->email;

            if (!$email) {
                continue;
            }

            try {
                $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
                $order->dont_show_order_info_in_product_list = true;
                OrderHelper::setEmailVariables($order);

                $mailer->sendUsingTemplate('order_recover', $email);

                $count++;
            } catch (Throwable $exception) {
                info($exception->getMessage());
            }
        }

        $this->info('Send ' . $count . ' email' . ($count != 1 ? 's' : '') . ' successfully!');
    }
}
