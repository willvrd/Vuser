<?php

namespace Modules\Vuser\Repositories\Cache;

use Modules\Vuser\Repositories\UserRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheUserDecorator extends BaseCacheDecorator implements UserRepository
{
    public function __construct(UserRepository $user)
    {
        parent::__construct();
        $this->entityName = 'user.users';
        $this->repository = $user;
    }



}
