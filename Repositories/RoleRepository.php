<?php

namespace Modules\Vuser\Repositories;

use Modules\Vcore\Repositories\BaseRepository;

interface RoleRepository extends BaseRepository
{

    public function assign($data);

    public function unassign($data);

}
