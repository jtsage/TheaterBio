<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class UserPermComponent extends Component
{
	/**
     * getAllPerms
     *
     * Grab a simple list of shows that a user is allowed to perfom an action in.
     *
     * @param string|null $id User Id.
     * @param string|null $perm Named permission (is_budget, is_pay_admin, is_paid).
     * @return Array of shows that $id has $perm in.
     */
	public function getAllPerm($id, $perm) {
		$this->ShowUserPerms = TableRegistry::get('ShowUserPerms');

        $perms = $this->ShowUserPerms->find('list', [
            'keyField' => 'id',
            'valueField' => 'show_id',
            'conditions' => ['ShowUserPerms.user_id' => $id, 'ShowUserPerms.' . $perm => 1]
        ]);

        if ( $perms->Count() > 0 ) {
            return $perms->toArray();
        } else {
            return [0];
        }
	}
	/**
     * checkShow
     *
     * Check if a user is allowed to perform an action in the specified show.
     *
     * @param string|null $userId User Id.
     * @param string|null $showId Show Id.
     * @param string|null $perm Named permission (is_budget, is_pay_admin, is_paid).
     * @return Bool $userId is allowed to $perm in $showId.
     */
	public function checkShow($userId, $showId, $perm) {
		$this->ShowUserPerms = TableRegistry::get('ShowUserPerms');
		
		$perms = $this->ShowUserPerms->find()
            ->where(['ShowUserPerms.user_id' => $userId])
            ->where(['ShowUserPerms.show_id' => $showId])
            ->select([
                'user_id' => 'ShowUserPerms.user_id',
                'show_id' => 'ShowUserPerms.show_id',
                'access' => 'ShowUserPerms.' . $perm
                ])
            ->first();

        return $perms->access;
    }

    /**
     * getShowPaidUsers
     *
     * Get a list of paid users for the specified show
     *
     * @param string|null $showId Show Id.
     * @return Array [$userID => {User Full Name}, ... ]
     */
    public function getShowPaidUsers($showId) {
        $this->ShowUserPerms = TableRegistry::get('ShowUserPerms');

        $hooper = $this->ShowUserPerms->find('list', ['valueField' => 'fullname', 'keyField' => 'user_id'])
            ->where(['ShowUserPerms.show_id' => $showId])
            ->where(['is_paid' => true])
            ->contain(['Users'])
            ->select(['fullname' => 'concat(Users.first, " ", Users.last, IF(Users.is_salary = 0, \'\', \' (salary employee)\'))', 'ShowUserPerms.user_id'])
            ->order(['Users.last' => 'ASC', 'Users.first' => 'DESC']);

        return $hooper->toArray();
    }
    public function getShowPayAdmins($showId) {
        $this->ShowUserPerms = TableRegistry::get('ShowUserPerms');

        $hooper = $this->ShowUserPerms->find('list', ['valueField' => 'fullname', 'keyField' => 'user_id'])
            ->where(['ShowUserPerms.show_id' => $showId])
            ->where(['is_pay_admin' => true])
            ->contain(['Users'])
            ->select(['fullname' => 'concat(Users.first, " ", Users.last, IF(Users.is_salary = 0, \'\', \' (salary employee)\'))', 'ShowUserPerms.user_id'])
            ->order(['Users.last' => 'ASC', 'Users.first' => 'DESC']);

        return $hooper->toArray();
    }
}
?>
