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
 * This form is same as Bank_Form_RecieptNew form except that in this form given backend 
 * sould blong to main tenant
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class Tenant_Form_BankReceiptNew extends Bank_Form_ReceiptNew
{

    function clean_backend()
    {
        $backend = Pluf::factory('Tenant_BankBackend', $this->cleaned_data['backend']);
        if ($backend->isAnonymous()) {
            throw new Pluf_Exception('backend not found');
        }
        // Check if backend blong to main tenant
        $mainTenant = Tenant_Shortcuts_GetMainTenant();
        if ($backend->tenant !== $mainTenant->getId()) {
            throw new Pluf_Exception('backend is not valid for this payment');
        }
        // XXX: maso, 1395: گرفتن پشتوانه
        return $backend->id;
    }
    
    /**
     *
     * @param string $commit
     * @throws Pluf_Exception
     * @return Tenant_BankBackend
     */
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            // TODO: maso, 1395: باید از خطای مدل فرم استفاده شود.
            throw new \Pluf\Exception(
                'Cannot save a receipt from an invalid form.');
        }
        // Set attributes
        $receipt = new Tenant_BankReceipt();
        $receipt->setFromFormData($this->cleaned_data);
        $receipt->secure_id = $this->getSecureKey();
        // موجودیت قرار گیرد.
        if ($commit) {
            if (! $receipt->create()) {
                throw new \Pluf\Exception('fail to create the recipt.');
            }
        }
        return $receipt;
    }
    
    /**
     * یک کد جدید برای موجودیت ایجاد می‌کند.
     *
     * @return Tenant_BankReceipt
     */
    private function getSecureKey ()
    {
        $recipt = new Tenant_BankReceipt();
        while (1) {
            $key = sha1(
                microtime() . rand(0, 123456789) . Pluf::f('secret_key'));
            $sess = $recipt->getList(
                array(
                    'filter' => 'secure_id=\'' . $key . '\''
                ));
            if (count($sess) == 0) {
                break;
            }
        }
        return $key;
    }
}

