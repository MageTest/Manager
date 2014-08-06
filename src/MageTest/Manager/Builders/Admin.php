<?php

namespace MageTest\Manager\Builders;

/**
 * Class Admin
 * @package MageTest\Manager\Builders
 */
class Admin extends AbstractBuilder implements BuilderInterface
{
    /**
     * @return \Mage_Admin_Model_User
     */
    public function build()
    {
        $this->model->addData($this->attributes);
        $this->model->save();

        $this->addAdminRole();

        return $this->model;
    }

    private function addAdminRole()
    {
        $role = \Mage::getModel("admin/role");
        $role->setParentId(1);
        $role->setTreeLevel(1);
        $role->setRoleType('U');
        $role->setUserId($this->model->getId());
        $role->save();
    }
}