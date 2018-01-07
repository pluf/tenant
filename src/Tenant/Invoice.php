<?php

class Tenant_Invoice extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'tenant_invoice';
        $this->_a['verbose'] = 'Tenant Invoice';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            'title' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'is_null' => true,
                'size' => 256,
                'editable' => true,
                'readable' => true
            ),
            'description' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'is_null' => true,
                'size' => 500,
                'editable' => true,
                'readable' => true
            ),
            'amount' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'is_null' => false
            ),
            'due_dtime' => array(
                'type' => 'Pluf_DB_Field_Date',
                'blank' => false,
                'is_null' => false,
                'editable' => true,
                'readable' => true
            ),
            'status' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'is_null' => false,
                'size' => 50,
                'editable' => false,
                'readable' => true
            ),
            'discount_code' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'is_null' => true,
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
            'payment' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Tenant_BankReceipt',
                'blank' => false,
                'editable' => false,
                'readable' => true,
                'relate_name' => 'payment'
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

    /**
     * Returns status of this invoice
     *
     * @return string
     */
    function getStatus()
    {
        return $this->status;
    }

    function setStatus($status)
    {
        if ($this->status === 'payed') {
            return;
        }
        // It is first time to update status of invoice
        // Note: Hadi - 1396-04: time is base on day
//         $day = Tenant_Service::setting(Tenant_Constants::SETTING_KEY_INVOICE_VALID_DAY, '30');
//         $expiryDay = ' +' . $day . ' day';
//         $this->expiry = date('Y-m-d H:i:s', strtotime($expiryDay));
        $this->status = $status;
        $this->update();
    }
}