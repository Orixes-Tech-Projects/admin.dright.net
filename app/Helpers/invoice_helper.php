<?php

if (!function_exists('generate_invoice_number')) {

    function generate_invoice_number(string $hospitalName, string $productType, string $product): string
    {
        $hospitalInitials = get_hospital_initials($hospitalName);
        $productCode = get_product_code($productType, $product);
        $datePart = date('ymd');
        $sequenceNumber = get_sequence_number($productType, $product);

        return "{$hospitalInitials}/{$productCode}/{$datePart}-{$sequenceNumber}";
    }
}

if (!function_exists('get_hospital_initials')) {

    function get_hospital_initials(string $name): string
    {
        $words = explode(' ', strtoupper($name));
        $initials = '';

        foreach ($words as $word) {
            $initials .= substr($word, 0, 1);
        }

        return $initials;
    }
}

if (!function_exists('get_product_code')) {

    function get_product_code(string $type, string $product): string
    {
        $type = strtolower($type);
        $product = strtolower($product);

        if ($type === 'builder') {
            if ($product === 'hospitals') return 'HWB';
            if ($product === 'doctors') return 'DWB';
        }

        if ($type === 'extended') {
            if ($product === 'hospitals') return 'HCE';
            if ($product === 'pharmacy') return 'PCE';
        }

        return 'CE'; // Default code
    }
}

if (!function_exists('get_sequence_number')) {

    function get_sequence_number(string $productType, string $product): string
    {
        $db = \Config\Database::connect();

        $today = date('Y-m-d');
        $builder = $db->table('invoices');

        try {
            $count = $builder->where('ProductType', $productType)
                ->where('Product', $product)
                ->where('DATE(SystemDate)', $today)
                ->countAllResults();

            return str_pad($count + 1, 2, '0', STR_PAD_LEFT);

        } catch (\Exception $e) {
            return '00';
        }
    }
}