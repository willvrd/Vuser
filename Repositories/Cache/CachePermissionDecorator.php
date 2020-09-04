<?php

namespace Modules\Vuser\Repositories\Cache;

use Modules\Vuser\Repositories\PermissionRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePermissionDecorator extends BaseCacheDecorator implements PermissionRepository
{
    public function __construct(PermissionRepository $permission)
    {
        parent::__construct();
        $this->entityName = 'user.permissions';
        $this->repository = $permission;
    }


    public function assign($data)
    {
        $this->cache->tags($this->entityName)->flush();
        return $this->repository->assign($data);
    }

    public function revoke($data)
    {
        $this->cache->tags($this->entityName)->flush();
        return $this->repository->revoke($data);
    }

    public function getModel($data)
    {
        return $this->remember(function () use ($data) {
            return $this->repository->getModel($data);
        });
    }

}
