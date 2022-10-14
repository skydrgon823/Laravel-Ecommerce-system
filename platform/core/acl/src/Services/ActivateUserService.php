<?php

namespace Botble\ACL\Services;

use Botble\ACL\Models\User;
use Botble\ACL\Repositories\Interfaces\ActivationInterface;

class ActivateUserService
{
    /**
     * @var ActivationInterface
     */
    protected $activationRepository;

    /**
     * ActivateUserService constructor.
     * @param ActivationInterface $activationRepository
     */
    public function __construct(ActivationInterface $activationRepository)
    {
        $this->activationRepository = $activationRepository;
    }

    /**
     * Activates the given user.
     *
     * @param User $user
     * @return bool
     */
    public function activate(User $user): bool
    {
        if ($this->activationRepository->completed($user)) {
            return false;
        }

        event('acl.activating', $user);

        $activation = $this->activationRepository->createUser($user);

        event('acl.activated', [$user, $activation]);

        return $this->activationRepository->complete($user, $activation->code);
    }
}
