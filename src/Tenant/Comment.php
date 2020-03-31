<?php

class Tenant_Comment extends Pluf_Model
{

    /**
     *
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'tenant_comments';
        $this->_a['verbose'] = 'Tenant Comment';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Sequence',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            'title' => array(
                'type' => 'Varchar',
                'blank' => false,
                'is_null' => false,
                'size' => 256
            ),
            'description' => array(
                'type' => 'Varchar',
                'blank' => true,
                'is_null' => true,
                'size' => 2048
            ),
            'creation_dtime' => array(
                'type' => 'Datetime',
                'blank' => true,
                'is_null' => true,
                'editable' => false,
                'readable' => true
            ),
            'modif_dtime' => array(
                'type' => 'Datetime',
                'blank' => true,
                'is_null' => true,
                'editable' => false,
                'readable' => true
            ),
            /*
            * Foreign keys
            */
            'author_id' => array(
                'type' => 'Foreignkey',
                'model' => 'User_Account',
                'is_null' => false,
                'editable' => false,
                'readable' => true,
                'name' => 'author',
                'graphql_feild' => 'author'
            ),
            'ticket_id' => array(
                'type' => 'Foreignkey',
                'model' => 'Tenant_Ticket',
                'is_null' => false,
                'editable' => false,
                'readable' => true,
                'name' => 'ticket',
                'graphql_feild' => 'ticket'
            )
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * @param boolean $create
     *            حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave($create = false)
    {
        //
    }
}