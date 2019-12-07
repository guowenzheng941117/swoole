<?php

namespace App\Model;

use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\DbManager;

class UserInfo
{
    public function getUserInfo($data)
    {
        $qb = new QueryBuilder();
        $qb->withTotalCount()->get('bjdl_tenant_contract', [$data['pageSize'] * ($data['page'] - 1), $data['pageSize']]);
        $result = DbManager::getInstance()->query($qb, true);
        $list = $result->getResult();
        $total = $result->getTotalCount();
        return ['total' => $total, 'list' => $list];
    }

    public function delUserInfo($data)
    {
        $start = DbManager::getInstance()->startTransaction();
        $qb = new QueryBuilder();
        $qb->where('id',$data['id']);
        $qb->delete('api_tracker_point_list');
        $result = DbManager::getInstance()->query($qb, true);
        $rollback = DbManager::getInstance()->rollback();
        // $commit = DbManager::getInstance()->commit();
        return $result->getAffectedRows();
    }
}
