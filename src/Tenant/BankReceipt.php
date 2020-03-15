<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * ساختارهای داده‌ای برای رسید را ایجاد می‌کند.
 *
 * رسید عبارت است از یک مجموعه از داده‌ها که برای پرداخت به بانک ارسال
 * می‌شود. این رسید زمانی که بانک تایید کند به روز شده و اطلاعات دریافتی
 * از بانک نیز به آن اضافه می شود.
 *
 * رسید در اینجا مثل رسید در ماژول بانک است با این تفاوت که این رسید تنها برای پرداخت‌های مربوط به tenant است.
 *
 * @author hadi
 *        
 */
class Tenant_BankReceipt extends Bank_Receipt
{

    /**
     *
     * @see Bank_Receipt::init()
     */
    function init()
    {
        parent::init();
        // Change class type of backend foreingkey from Bank_Backend to Tenant_BankBackend
        $this->_a['cols'] = array_merge($this->_a['cols'], array(
            'backend_id' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Tenant_BankBackend',
                'blank' => false,
                'relate_name' => 'backend'
            )
        ));
    }
}