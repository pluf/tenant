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
Pluf::loadFunction('Tenant_Shortcuts_GetMainTenant');

/**
 * Invoices view
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 * @author hadi<mohammad.hadi.mansouri@dpq.co.ir>
 */
class Tenant_Views_Invoice extends Pluf_Views
{

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public static function payment($request, $match)
    {
        $invoice = Pluf_Shortcuts_GetObjectOr404('Tenant_Invoice', $match['modelId']);

        $user = $request->user;
        $url = $request->REQUEST['callback'];
        $backend = $request->REQUEST['backend'];
        $price = $invoice->amount;

        // Check backend
        $be = new Tenant_BankBackend($backend);
        $mainTenant = Tenant_Shortcuts_GetMainTenant();
        if ($be->tenant !== $mainTenant->id) {
            throw new \Pluf\Exception('Invalid backend. Backend should be blong to main tenant.');
        }

        // check for discount
        if (isset($request->REQUEST['discount_code'])) {
            $discountCode = $request->REQUEST['discount_code'];
            $price = Discount_Service::getPrice($price, $discountCode, $request);
            $discount = Discount_Service::consumeDiscount($discountCode);
            $invoice->discount_code = $discountCode;
        }

        $receiptData = array(
            'amount' => $price, // مقدار پرداخت به تومان
            'title' => $invoice->title,
            'description' => $invoice->id . ' - ' . $invoice->title,
            'email' => $user->email,
            // 'phone' => $user->phone,
            'phone' => '',
            'callbackURL' => $url,
            'backend_id' => $backend
        );

        $payment = Tenant_BankService::create($receiptData, 'tenant-invoice', $invoice->id);

        $invoice->payment = $payment;
        $invoice->update();
        return new Pluf_HTTP_Response_Json($payment);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public static function checkPaymentState($request, $match)
    {
        $invoice = Pluf_Shortcuts_GetObjectOr404('Tenant_Invoice', $match['modelId']);
        $invoice = Tenant_Views_Invoice::updatePaymentStatus($invoice);
        return new Pluf_HTTP_Response_Json($invoice);
    }

    /**
     * Checks
     *
     * @param Tenant_Invoice $invoice
     * @return Tenant_Invoice
     */
    private static function updatePaymentStatus($invoice)
    {
        if (! $invoice->payment || $invoice->getStatus() === 'payed') {
            return $invoice;
        }
        $receipt = $invoice->get_payment();
        Bank_Service::update($receipt);
        if ($invoice->get_payment()->isPayed()) {
            $invoice->setStatus('payed');
            $invoice->update();
        }
        return $invoice;
    }
}
