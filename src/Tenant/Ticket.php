<?php

class Tenant_Ticket extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'tenant_ticket';
        $this->_a['verbose'] = 'Tenant Ticket';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            'type' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'is_null' => true,
                'size' => 256
            ),
            'subject' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'is_null' => false,
                'size' => 256
            ),
            'description' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'is_null' => true,
                'size' => 2048
            ),
            'status' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'is_null' => false,
                'size' => 50,
                'editable' => false,
                'readable' => true
            ),
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'is_null' => true,
                'editable' => false,
                'readable' => true
            ),
            'modif_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'is_null' => true,
                'editable' => false,
                'readable' => true
            ),
            // relations
            'requester' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Pluf_User',
                'blank' => false,
                'editable' => false,
                'readable' => true,
                'relate_name' => 'requester'
            )
        );
        
        // $this->_a['idx'] = array(
        // 'ticket_tenant_idx' => array(
        // 'col' => 'secure_ticket',
        // 'type' => 'unique', // normal, unique, fulltext, spatial
        // 'index_type' => '', // hash, btree
        // 'index_option' => '',
        // 'algorithm_option' => '',
        // 'lock_option' => ''
        // )
        // );
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

    /**
     * Returns status of this ticket
     *
     * @return string
     */
    function getStatus()
    {
        return $this->status;
    }

    function setStatus($status)
    {
        $this->status = $status;
        $this->update();
    }
}