<?php

namespace Modules\Vuser\Repositories;

use Modules\Vcore\Repositories\BaseRepository;

interface PermissionRepository extends BaseRepository
{

    public function assign($data);

    public function revoke($data);

    public function getModel($data);

}
