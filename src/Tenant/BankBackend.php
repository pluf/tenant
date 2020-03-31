<?php

class Tenant_BankBackend extends Bank_Backend
{

    function init()
    {
        parent::init();
        $this->_a['multitenant'] = false;
        $this->_a['cols'] = array_merge($this->_a['cols'], array(
            'tenant' => array(
                'type' => 'Foreignkey',
                'model' => 'Pluf_Tenant',
                'blank' => false,
                'editable' => false
            )
        ));
    }
}
