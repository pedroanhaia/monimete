<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
declare(strict_types=1);

/**
 * An identity decorator implementing both Authorization\IdentityInterface and
 * Authentication\IdentityInterface.
 */
namespace Authorization;

use ArrayAccess;
use Authentication\IdentityInterface as AuthenIdentityInterface;

class Identity extends IdentityDecorator implements AuthenIdentityInterface
{
    /**
     * Identity data
     *
     * @var \Authentication\IdentityInterface
     * @psalm-suppress NonInvariantDocblockPropertyType
     */
    protected ArrayAccess|array $identity;

    /**
     * Constructor
     *
     * @param \Authorization\AuthorizationServiceInterface $service The authorization service.
     * @param \Authentication\IdentityInterface $identity Identity data
     */
    public function __construct(AuthorizationServiceInterface $service, AuthenIdentityInterface $identity)
    {
        $this->authorization = $service;
        $this->identity = $identity;
    }

    /**
     * Get the primary key/id field for the identity.
     *
     * @return array<array-key, mixed>|string|int|null
     */
    public function getIdentifier(): string|int|array|null
    {
        return $this->identity->getIdentifier();
    }

    /**
     * @inheritDoc
     */
    public function getOriginalData(): ArrayAccess|array
    {
        return $this->identity->getOriginalData();
    }
}
