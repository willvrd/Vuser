<?php

namespace Modules\Vuser\Repositories\Cache;

use Modules\Vuser\Repositories\RoleRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheRoleDecorator extends BaseCacheDecorator implements RoleRepository
{
    public function __construct(RoleRepository $role)
    {
        parent::__construct();
        $this->entityName = 'user.roles';
        $this->repository = $role;
    }


    public function assign($data)
    {
        $this->cache->tags($this->entityName)->flush();
        return $this->repository->assign($data);
    }

    public function unassign($data)
    {
        $this->cache->tags($this->entityName)->flush();
        return $this->repository->unassign($data);
    }


}
