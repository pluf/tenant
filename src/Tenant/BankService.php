<?php

class Tenant_BankService
{

    /**
     * یک پرداخت جدید ایجاد می‌کند
     *
     * روالی که برای ایجاد یک پرداخت دنبال می‌شه می‌تونه خیلی متفاوت باشه
     * و ساختارهای رو برای خودش ایجاد کنه. برای همین ما پارامترهای ارسالی در
     * در خواست رو هم ارسال می‌کنیم.
     *
     * پرداخت ایجاد شده بر اساس اطلاعاتی است که با متغیر $reciptParam ارسال
     * می‌شود. این پارامترها
     * باید به صورت یک آرایه بوده و شامل موارد زیر باشد:
     *
     * <pre><code>
     * $param = array(
     * 'amount' => 1000, // مقدار پرداخت به ریال
     * 'title' => 'payment title',
     * 'description' => 'description',
     * 'email' => 'user@email.address',
     * 'phone' => '0917222222',
     * 'callbackURL' => 'http://.....',
     * 'backend_id' => 2
     * );
     * </code></pre>
     *
     * <ul>
     * <li>*amount: مقدار بر اساس ریال</li>
     * <li>*title: عنوان پرداخت</li>
     * <li>*description: توضیحات</li>
     * <li>email: رایانامه مشتری</li>
     * <li>phone: شماره تماس مشتری</li>
     * <li>callbackURL: آدرسی که بعد از تکمیل باید فراخوانی شود</li>
     * <li>*backend: درگاه پرداخت مورد نظر</li>
     * </ul>
     *
     * در نهایت باید موجودیتی تعیین بشه که این پرداخت رو می‌خواهیم براش ایجاد
     * کنیم.
     *
     * نکته مهم اینکه در این پیاده‌سازی backend باید مربوط به ملک اصلی باشه.
     *
     * @param array $param
     * @param Pluf_Model $owner
     * @return Bank_Receipt
     */
    public static function create ($param, $owner = null, $ownerId = null)
    {
        $form = new Tenant_Form_BankReceiptNew($param);
        $receipt = $form->save(false);
//         $backend = Pluf::factory('Tenant_BankBackend', $receipt->backend);
        $backend = $receipt->get_backend();
        $engine = $backend->get_engine();
        $engine->create($receipt);
        if ($owner instanceof Pluf_Model) { // Pluf module
            $receipt->owner_class = $owner->getClass();
            $receipt->owner_id = $owner->getId();
        } elseif (! is_null($owner)) { // module
            $receipt->owner_class = $owner;
            $receipt->owner_id = $ownerId;
        }
        $receipt->create();
        return $receipt;
    }
    
}