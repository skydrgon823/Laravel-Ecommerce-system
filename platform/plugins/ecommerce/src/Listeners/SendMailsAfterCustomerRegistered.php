<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Models\Customer;
use EcommerceHelper;
use EmailHandler;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Throwable;

class SendMailsAfterCustomerRegistered
{
    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function handle(Registered $event)
    {
        $customer = $event->user;

        if (get_class($customer) == Customer::class) {
            EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'customer_name' => $customer->name,
                ])
                ->sendUsingTemplate('welcome', $customer->email);

            if (EcommerceHelper::isEnableEmailVerification()) {
                // Notify the user
                $customer->sendEmailVerificationNotification();
            }
        }
    }
}
