<?php
namespace App\Module;

use App\Model\RoleModel;
use App\Exceptions\ServiceException;


/**
 * Role module
 * @date 2018-06-01
 */
class Role extends BaseModule
{

    /**
     * get new model
     * @date 2018-06-01
     * @return RoleModel
     */
    public static function getModel()
    {
        return new RoleModel();
    }

    /**
     * 角色分配菜单
     * @author gaojian291
     * @date 2017-05-19
     * @param string $id required 角色ID
     * @return array
     * @throws ServiceException
     */
    public static function selectMenu($id)
    {
        $data = self::_getModel(['id'=>$id])->toArray();
        if ($data && $data['is_admin'] == 1) {
            throw new ServiceException('ADMIN_ROLE_CAN_NOT_ALIGN');
        }
        $menuID = explode(',', $data['menu_json']);
        $menu = MenuModel::where('type', 0)->get(['id', 'name'])->toArray();
        foreach ($menu as $key => &$val) {
            if (in_array($val['id'], $menuID)) {
                $val['selected'] = true;
            } else {
                $val['selected'] = false;
            }
        }
        return $menu;
    }



    /**
     * 根据角色ID获取角色信息
     * @author gaojian291
     * @date 2017-05-19
     * @param array $ids required 角色IDs
     * @return array
     */
    public static function getRoleInfoByIDs($ids)
    {
        return RoleModel::whereIn('id', $ids)->lists('name', 'id')->toArray();
    }


    /**
     * 获取数据model
     * @author gaojian291
     * @date 2017-05-16
     * @param array $param required 请求参数
     * @throws ServiceException
     * @return model
     */
    private static function _getModel($param)
    {
        $model = RoleModel::where($param)->first();
        if (!$model) {
            throw new ServiceException('ROLE_NOT_FIND_EXCEPTION');
        }
        return $model;
    }


}