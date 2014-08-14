<?php

namespace Wscn\Forms;

use Eva\EvaUser\Forms\UserForm as BaseUserForm;
use Phalcon\Forms\Element\Radio;

class UserForm extends BaseUserForm
{
    public function getGender()
    {
        $genders = array(
            'male' => '男士',
            'female' => '女士',
            'other' => '其他',
        );
        
        $radios = array();
        $user = $this->getModel();
        foreach($genders as $value => $label) {
            $radio = new Radio('gender', array(
                'value' => $value
            ));
            $radio->setLabel($label);
            $radio->setDefault($user->gender);
            $radios[$value] = $radio;
        }
        return $this->gender = $radios;
    }

    public function afterSetModel()
    {
        //p($this->get('email')->getValue());
    }

    public function initialize($entity = null, $options = null)
    {
        parent::initialize($entity, $options);
    }
}
