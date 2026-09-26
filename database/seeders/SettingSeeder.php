<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Store
            ['key' => 'store_name',        'value' => 'My POS Shop',       'group' => 'store'],
            ['key' => 'store_address',     'value' => '',                 'group' => 'store'],
            ['key' => 'store_phone',       'value' => '',                 'group' => 'store'],
            ['key' => 'store_email',       'value' => '',                 'group' => 'store'],
            ['key' => 'store_logo',        'value' => '',                 'group' => 'store'],

            // Currency / Tax
            ['key' => 'currency_symbol',   'value' => 'Rs.',              'group' => 'tax'],
            ['key' => 'currency_position', 'value' => 'before',           'group' => 'tax'],
            ['key' => 'default_tax_rate',  'value' => '0',                'group' => 'tax'],
            ['key' => 'tax_inclusive',     'value' => '0',                'group' => 'tax'],

            // Receipt
            ['key' => 'receipt_header',    'value' => 'Thank you for shopping!', 'group' => 'receipt'],
            ['key' => 'receipt_footer',    'value' => 'No return after 7 days',  'group' => 'receipt'],
            ['key' => 'receipt_paper',     'value' => '80mm',             'group' => 'receipt'],
            ['key' => 'show_logo_receipt', 'value' => '1',                'group' => 'receipt'],

            // Invoice
            ['key' => 'invoice_prefix',    'value' => 'INV-',             'group' => 'receipt'],

            // POS
            ['key' => 'barcode_input_mode','value' => 'hardware',         'group' => 'pos'],
            ['key' => 'allow_negative_stock','value' => '0',              'group' => 'pos'],
            ['key' => 'round_off_total',   'value' => '0',                'group' => 'pos'],


                        // Receipt / Print
            ['key' => 'auto_print_receipt',  'value' => '0',                'group' => 'receipt'],
            ['key' => 'receipt_show_logo',   'value' => '1',                'group' => 'receipt'],
            ['key' => 'receipt_show_cashier','value' => '1',                'group' => 'receipt'],

            // Backup
            ['key' => 'backup_enabled',         'value' => '0',       'group' => 'backup'],
            ['key' => 'backup_frequency',       'value' => 'daily',   'group' => 'backup'],
            ['key' => 'backup_time',            'value' => '02:00',   'group' => 'backup'],
            ['key' => 'backup_retention_days',  'value' => '30',      'group' => 'backup'],
            ['key' => 'backup_notify_email',    'value' => '',        'group' => 'backup'],
            ['key' => 'backup_notify_on',       'value' => 'failure', 'group' => 'backup'],




        ];

        foreach ($defaults as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }

        $this->command->info('✅ Default settings seeded.');
    }
}