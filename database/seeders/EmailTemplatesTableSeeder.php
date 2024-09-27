<?php
namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->insert([
            [ 
                'name' => 'New Order Notification',
                'subject' => "We've Received Your Order #[ORDER_NUMBER]!",
                'body' => "<p><strong>Dear [USER_NAME],&nbsp;</strong></p><p>Thank you for shopping with us! We're excited to let you know that we’ve received your order #[ORDER_NUMBER].</p><p><strong>Order Details:</strong></p><ul><li><strong>Order Date:</strong> [ORDER_DATE]</li><li><strong>Total Amount:</strong> [ORDER_AMOUNT]</li></ul><p><strong>Items Ordered:</strong></p><p>[ORDER_ITEMS]</p><p><strong>Shipping Address:</strong></p><p>[SHIPPING_ADDRESS]</p><p><strong>Billing Address:</strong></p><p>[BILLING_ADDRESS]</p><p>You will receive another email once your order is on its way, including tracking information and expected delivery dates.</p><p>If you have any questions about your order or need assistance, please don’t hesitate to contact us at [COMPANY_EMAIL] / [COMPANY_PHONE].</p><p>Thank you for choosing [COMPANY_NAME]. We appreciate your business and look forward to serving you again!</p><p>Best regards,</p><p>[COMPANY_NAME]<br>[COMPANY_ADDRESS]<br>[COMPANY_CITY], [COMPANY_STATE] [COMPANY_POSTCODE]<br>[COMPANY_COUNTRY]</p><p>&nbsp;</p>",
                'created_at' => '2024-08-15 00:19:06',
                'updated_at' => '2024-08-15 02:44:33',
            ],
            [ 
                'name' => 'Status Change Notification',
                'subject' => 'Status Updated for your Order #[ORDER_NUMBER]',
                'body' => "<p>Dear [USER_NAME],</p><p>&nbsp;</p><p>Status of your Order #[ORDER_NUMBER] has been changed to [ORDER_STATUS]</p><p>&nbsp;</p><p>Best regards,</p><p>[COMPANY_NAME]<br>[COMPANY_ADDRESS]<br>[COMPANY_CITY], [COMPANY_STATE] [COMPANY_POSTCODE]<br>[COMPANY_COUNTRY]</p><p>&nbsp;</p>",
                'created_at' => '2024-08-15 05:25:44',
                'updated_at' => '2024-08-15 05:25:44',
            ],
        ]);
    }
}
