<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Date;

/**
 * Reimbursement Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $volunteer_site_id
 * @property \Cake\I18n\FrozenTime $date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $submitted
 * @property bool $deleted
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\VolunteerSite $volunteer_site
 */
class Reimbursement extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'volunteer_site_id' => true,
        'date' => true,
        'created' => true,
        'modified' => true,
        'submitted' => true,
        'deleted' => true,

        'receipts' => true,
        'other_riders' => true,
        
        'user' => false,
        'volunteer_site' => false
    ];

    protected function _getDateString() {
        if (isset($this->_properties['date_string'])) {
            return $this->_properties['date_string'];
        }
        if (empty($this->date)) {
            return '';
        }
        $date = new Date($this->date);
        return $date->i18nFormat("M/d");
    }
}
