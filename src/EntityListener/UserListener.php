<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function prePersist(User $user): void
    {
        $this->encodePassword($user);
    }

    public function preUpdate(User $user): void
    {
        $this->encodePassword($user);
    }

    /**
     * Encode the password before persisting the User object
     * @param User $user
     */
    public function encodePassword(User $user): void
    {
        if ($user->getPlainPassword() === null) {
            return;
        }
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
        $user->setPlainPassword(null);
    }
}