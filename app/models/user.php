<?php

class User extends AppModel {

    var $name = 'Users';
    var $uses = array('User');
    var $belongsTo = array(
        'School' => array(
            'className' => 'School',
            'foreignKey' => 'school_id'
        )
        ,
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id'
        )
        ,
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id'
        )
        ,
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id'
        )
    );
    var $validate = array(
        'firstname' => array(
            'rule' => 'notEmpty',
            'message' => 'Firstname is required'
        ),
        'lastname' => array(
            'rule' => 'notEmpty',
            'message' => 'Lastname is required'
        ),
        'username' => array(
            'notempty' => array(
                'rule' => array('minLength', 1),
                'allowEmpty' => false,
                'message' => 'User name cannot be empty'
            ),
            'unique' => array(
                'rule' => array('checkUnique', 'username'),
                'message' => 'User name taken. Use another'
            )
        ),
        'password' => array(
            'rule' => array('minLength', 5),
            'message' => 'Password must be at least 5 characters long'
        ),
        'confirm_password' => array(
            'rule' => array('Confirm_password'),
            'message' => 'The Two password do not match'
        ),
        'school_id' => array(
            'rule' => 'notEmpty',
            'message' => 'School should not be empty'
        ),
        'year' => array(
            'rule' => 'notEmpty',
            'message' => 'Please select your graduation year'
        ),
        'school_status' => array(
            'rule' => 'notEmpty',
            'message' => 'You have to select the school status'
        ),
        'email' => array(
            'emailRule-1' => array(
                'rule' => 'notEmpty',
                'message' => 'Email Should not be empty',
                'last' => true
            ),
            'emailRule-2' => array(
                'rule' => 'email',
                'message' => 'Please enter a valid email address'
            ),
            'unique' => array(
                'rule' => array('checkUnique', 'email'),
                'message' => 'email name taken. Use another'
            )
        ),
        'major' => array(
            'rule' => 'notEmpty',
            'message' => 'Major should not be empty'
        ),
        'hometown' => array(
            'rule' => 'notEmpty',
            'message' => 'Hometown should not be empty'
        )
    );

    function Confirm_password($data) {

        $value = $_REQUEST['data']['User']['confirm_password'];

        if ($value == $_REQUEST['data']['User']['password']) {
            return true;
        } else {
            return false;
        }
    }

    function checkUnique($data, $fieldName) {
        $valid = false;
        if (isset($fieldName) && $this->hasField($fieldName)) {
            $valid = $this->isUnique(array($fieldName => $data));
        }
        return $valid;
    }

}

?>