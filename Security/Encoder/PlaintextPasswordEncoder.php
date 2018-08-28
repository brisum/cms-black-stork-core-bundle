<?php

namespace Brisum\Stork\Bundle\CoreBundle\Security\Encoder;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

// Copy of \Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder
class PlaintextPasswordEncoder extends BasePasswordEncoder
{
    /**
     * @var bool
     */
    private $ignorePasswordCase;

    /**
     * @param bool $ignorePasswordCase Compare password case-insensitive
     */
    public function __construct(bool $ignorePasswordCase = false)
    {
        $this->ignorePasswordCase = $ignorePasswordCase;
    }

    /**
     * {@inheritdoc}
     */
    public function encodePassword($raw, $salt)
    {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }

        /* ignore salt */
        // return $this->mergePasswordAndSalt($raw, $salt);
        return $raw;
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        if ($this->isPasswordTooLong($raw)) {
            return false;
        }

        /* ignore salt */
        // $pass2 = $this->mergePasswordAndSalt($raw, $salt);
        $pass2 = $raw;

        if (!$this->ignorePasswordCase) {
            return $this->comparePasswords($encoded, $pass2);
        }

        return $this->comparePasswords(strtolower($encoded), strtolower($pass2));
    }
}
