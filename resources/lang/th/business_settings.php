<?php

return [
    'business_settings' => 'การตั้งค่าธุรกิจ',
    'settings' => 'การตั้งค่า',

    // ธุรกิจ
    'business_name' => 'ชื่อธุรกิจ',
    'start_date' => 'วันที่เริ่มต้น',
    'default_profit_percent' => 'เปอร์เซ็นต์กำไรเริ่มต้น',
    'currency' => 'สกุลเงิน',
    'currency_symbol_placement' => 'การวางสัญลักษณ์สกุลเงิน',
    'time_zone' => 'เขตเวลา',
    'update_logo' => 'อัปเดตโลโก้',
    'financial_year_start_month' => 'เดือนที่เริ่มต้นปีการเงิน',
    'stock_accounting_method' => 'วิธีบัญชีสต็อก',
    'transaction_edit_days' => 'วันที่แก้ไขธุรกรรม',
    'date_format' => 'รูปแบบวันที่',
    'time_format' => 'รูปแบบเวลา',
    'currency_position' => 'ตำแหน่งสกุลเงิน',
    'quantity_precision' => 'ความแม่นยำของจำนวน',

    // ภาษี
    'tax_1_name' => 'ชื่อภาษี 1',
    'tax_1_no' => 'หมายเลขภาษี 1',
    'tax_2_name' => 'ชื่อภาษี 2',
    'tax_2_no' => 'หมายเลขภาษี 2',
    'enable_inline_tax_in_purchase_and_sell' => 'เปิดใช้งานภาษีในการซื้อและการขายออนไลน์',


    // Product
    'sku_prefix' => 'คำนำหน้า SKU',
    'enable_product_expiry' => 'เปิดใช้งานการหมดอายุสินค้า',
    'enable_brands' => 'เปิดใช้งานแบรนด์',
    'enable_categories' => 'เปิดใช้งานหมวดหมู่',
    'default_units' => 'หน่วยเริ่มต้น',
    'enable_sub_unit' => 'เปิดใช้งานหน่วยย่อย',
    'enable_racks' => 'เปิดใช้งานชั้นวางสินค้า',
    'enable_position' => 'เปิดใช้งานตำแหน่ง',
    'enable_warranty' => 'เปิดใช้งานระยะเวลาการรับประกัน',
    'enable_secondary_unit' => 'เปิดใช้งานหน่วยรอง',

    // Contact
    'default_credit_limit' => 'วงเงินเครดิตเริ่มต้น',

    // Sale
    'enable_line_discount_for_sale' => 'เปิดใช้งานส่วนลดแบบเส้นสำหรับการขาย',
    'default_sale_discount' => 'ส่วนลดการขายเริ่มต้น',
    'default_sale_tax' => 'ภาษีการขายเริ่มต้น',
    'sales_item_addition_method' => 'วิธีการเพิ่มสินค้าในการขาย',
    'amount_rounding_method' => 'วิธีการปัดเศษจำนวนเงิน',
    'sales_price_is_minimum_selling_price' => 'ราคาขายเป็นราคาขายต่ำสุด',
    'allow_overselling' => 'อนุญาตให้ขายมากกว่าสินค้าคงคลัง',
    'enable_sales_order' => 'เปิดใช้งานใบสั่งขาย',
    'is_pay_term_required' => 'ต้องการเงื่อนไขการชำระเงินหรือไม่?',
    'sales_commission_agent' => 'ตัวแทนค่าคอมมิชชั่นการขาย',
    'commission_calculation_type' => 'ประเภทการคำนวณค่าคอมมิชชั่น',
    'is_commission_agent_required' => 'ต้องการตัวแทนค่าคอมมิชชั่นหรือไม่?',
    'enable_payment_link' => 'เปิดใช้งานลิงก์การชำระเงิน',
    'razorpay_key_id' => 'Razorpay Key ID',
    'razorapay_key_secret' => 'Razorpay Key Secret',
    'stripe_public_key' => 'Stripe คีย์สาธารณะ',
    'stripe_secret_key' => 'Stripe คีย์ลับ',

    // POS
    'express_checkout' => 'ชำระเงินแบบด่วน',
    'pay_and_checkout' => 'ชำระเงินและเช็คเอาท์',
    'pos_draft' => 'แบบร่าง POS',
    'cancel' => 'ยกเลิก',
    'go_to_product_quantity' => 'ไปยังจำนวนสินค้า',
    'weighing_scale' => 'เครื่องชั่งน้ำหนัก',
    'edit_order_tax' => 'แก้ไขภาษีการสั่งซื้อ',
    'add_payment_row' => 'เพิ่มแถวการชำระเงิน',
    'finalize_payment' => 'ยืนยันการชำระเงิน',
    'add_new_product' => 'เพิ่มสินค้าใหม่',
    'disable_multiple_pay' => 'ปิดใช้งานการชำระเงินหลายรายการ',
    'display_draft' => 'แสดงแบบร่าง',
    'display_express_checkout' => 'แสดงชำระเงินแบบด่วน',
    'dont_show_product_suggestion' => 'ไม่แสดงคำแนะนำสินค้า',
    'dont_show_recent_transactions' => 'ไม่แสดงรายการที่เกิดขึ้นเร็วๆ นี้',
    'disable_discount' => 'ปิดใช้งานส่วนลด',
    'disable_order_tax' => 'ปิดใช้งานภาษีการสั่งซื้อ',
    'subtotal_editable' => 'สามารถแก้ไขยอดรวมย่อยได้',
    'disable_suspend_sale' => 'ปิดใช้งานการระงับการขาย',
    'enable_transaction_date_on_pos_screen' => 'เปิดใช้งานวันที่ธุรกรรมบนหน้าจอ POS',
    'enable_service_staff_in_product_line' => 'เปิดใช้งานบุคลากรบริการในบรรทัดสินค้า',
    'is_service_staff_required' => 'ต้องการบุคลากรบริการหรือไม่?',
    'disable_credit_sale_button' => 'ปิดใช้งานปุ่มการขายเครดิต',
    'enable_weighing_scale' => 'เปิดใช้งานเครื่องชั่งน้ำหนัก',
    'show_invoice_scheme' => 'แสดงแผนการออกใบแจ้งหนี้',
    'show_invoice_layout_dropdown' => 'แสดงเมนูแบบรูปแบบใบแจ้งหนี้',
    'print_invoice_on_suspend' => 'พิมพ์ใบแจ้งหนี้เมื่อระงับการขาย',
    'show_pricing_on_product_suggestion_tooltip' => 'แสดงราคาในคำแนะนำสินค้า',
    'barcode_prefix' => 'คำนำหน้าบาร์โค้ด',
    'barcode_product_sku_length' => 'ความยาว SKU สินค้าในบาร์โค้ด',
    'quantity_integer_part_length' => 'ความยาวส่วนจำนวนเต็ม',
    'quantity_fractional_part_length' => 'ความยาวส่วนจำนวนทศนิยม',
    'enable_line_discount' => 'เปิดใช้งานส่วนลดแบบเส้น',
    'enable_editing_product_price_from_purchase_screen' => 'เปิดใช้งานการแก้ไขราคาสินค้าจากหน้าจอการซื้อ',
    'enable_purchase_status' => 'เปิดใช้งานสถานะการซื้อ',
    'enable_lot_number' => 'เปิดใช้งานหมายเลขการสุ่ม',
    'enable_purchase_order' => 'เปิดใช้งานใบสั่งซื้อ',


    // Payment Tab
    'enable_cash_denomination_on' => 'เปิดใช้งานการระบุเงินสดใน',
    'enable_cash_denomination_for_payment_methods' => 'เปิดใช้งานการระบุเงินสดสำหรับวิธีการชำระเงิน',
    'strict_check' => 'ตรวจสอบอย่างเคร่งครัด',

    // Dashboard
    'view_stock_expiry_alert_for' => 'ดูการแจ้งเตือนหมดอายุสินค้าสำหรับ',

    // System
    'theme_color' => 'สีธีม',
    'default_datatable_page_entries' => 'จำนวนรายการเริ่มต้นในตารางข้อมูล',
    'show_help_text' => 'แสดงข้อความความช่วยเหลือ',

    // Prefix
    'purchase_return' => 'การคืนสินค้าจากการซื้อ',
    'purchase_order' => 'ใบสั่งซื้อ',
    'stock_transfer' => 'การโอนสินค้า',
    'stock_adjustment' => 'การปรับสต็อก',
    'sell_return' => 'การคืนสินค้าจากการขาย',
    'expenses' => 'ค่าใช้จ่าย',
    'contacts' => 'รายชื่อผู้ติดต่อ',
    'purchase_payment' => 'การชำระเงินการซื้อ',
    'sell_payment' => 'การชำระเงินการขาย',
    'expense_payment' => 'การชำระเงินค่าใช้จ่าย',
    'business_location' => 'สถานที่ธุรกิจ',
    'prefix_username' => 'คำนำหน้าชื่อผู้ใช้',
    'subscription_no' => 'หมายเลขการสมัครสมาชิก',
    'prefix_draft' => 'คำนำหน้าร่าง',
    'sales_order' => 'ใบสั่งขาย',

    // Email Settings
    'mail_driver' => 'ไดรเวอร์การส่งอีเมล',
    'mail_host' => 'โฮสต์อีเมล',
    'mail_port' => 'พอร์ตอีเมล',
    'mail_username' => 'ชื่อผู้ใช้อีเมล',
    'mail_password' => 'รหัสผ่านอีเมล',
    'mail_encryption' => 'การเข้ารหัสอีเมล',
    'mail_address_from' => 'ที่อยู่อีเมล (จาก)',
    'mail_name_from' => 'ชื่ออีเมล (จาก)',

    // SMS
    'sms_service' => 'บริการ SMS',
    'sms_url' => 'URL สำหรับ SMS',
    'send_to_parameter_name' => 'ชื่อพารามิเตอร์สำหรับการส่งไปยัง',
    'message_parameter_name' => 'ชื่อพารามิเตอร์ข้อความ',
    'request_method_for_sms' => 'วิธีการขอข้อมูลสำหรับ SMS',
    'header_1_key' => 'คีย์ส่วนหัว 1',
    'header_1_value' => 'ค่าส่วนหัว 1',
    'header_2_key' => 'คีย์ส่วนหัว 2',
    'header_2_value' => 'ค่าส่วนหัว 2',
    'header_3_key' => 'คีย์ส่วนหัว 3',
    'header_3_value' => 'ค่าส่วนหัว 3',
    'parameter_1_value' => 'ค่าพารามิเตอร์ 1',
    'parameter_1_key' => 'คีย์พารามิเตอร์ 1',
    'parameter_2_value' => 'ค่าพารามิเตอร์ 2',
    'parameter_2_key' => 'คีย์พารามิเตอร์ 2',
    'parameter_3_value' => 'ค่าพารามิเตอร์ 3',
    'parameter_3_key' => 'คีย์พารามิเตอร์ 3',
    'parameter_4_value' => 'ค่าพารามิเตอร์ 4',
    'parameter_4_key' => 'คีย์พารามิเตอร์ 4',
    'parameter_5_value' => 'ค่าพารามิเตอร์ 5',
    'parameter_5_key' => 'คีย์พารามิเตอร์ 5',
    'parameter_6_value' => 'ค่าพารามิเตอร์ 6',
    'parameter_6_key' => 'คีย์พารามิเตอร์ 6',
    'parameter_7_value' => 'ค่าพารามิเตอร์ 7',
    'parameter_7_key' => 'คีย์พารามิเตอร์ 7',
    'parameter_8_value' => 'ค่าพารามิเตอร์ 8',
    'parameter_8_key' => 'คีย์พารามิเตอร์ 8',
    'parameter_9_value' => 'ค่าพารามิเตอร์ 9',
    'parameter_9_key' => 'คีย์พารามิเตอร์ 9',
    'parameter_10_value' => 'ค่าพารามิเตอร์ 10',
    'parameter_10_key' => 'คีย์พารามิเตอร์ 10',
    'nexmo_key' => 'คีย์ Nexmo',
    'nexmo_secret' => 'ความลับ Nexmo',
    'from_nexmo' => 'จาก (Nexmo)',
    'twilio_key' => 'คีย์ Twilio',
    'twilio_secret' => 'ความลับ Twilio',
    'from_twilio' => 'จาก (Twilio)',


    // Reward Point
    'enable_reward_point' => 'เปิดใช้งานคะแนนสะสม',
    'reward_point_display_name' => 'ชื่อการแสดงคะแนนสะสม',
    'amount_spend_for_unit_point' => 'จำนวนเงินที่ใช้สำหรับคะแนนหน่วย',
    'minimum_order_total_to_earn_reward' => 'ยอดรวมการสั่งซื้อขั้นต่ำเพื่อรับคะแนนสะสม',
    'maximum_points_per_order' => 'คะแนนสูงสุดต่อคำสั่งซื้อ',
    'redeem_amount_per_unit_point' => 'จำนวนเงินที่ใช้แลกคะแนนต่อหน่วย',
    'minimum_order_total_to_redeem_points' => 'ยอดรวมการสั่งซื้อขั้นต่ำเพื่อแลกคะแนน',
    'minimum_redeem_point' => 'คะแนนที่ต้องการแลกขั้นต่ำ',
    'maximum_redeem_point_per_order' => 'คะแนนสูงสุดต่อคำสั่งซื้อ',
    'reward_point_expiry_period' => 'ระยะเวลาหมดอายุของคะแนนสะสม',
    'purchases' => 'การซื้อ',
    'add_sale' => 'เพิ่มการขาย',
    'pos' => 'POS',
    'stock_transfers' => 'การโอนสินค้า',
    'expenses_module' => 'โมดูลค่าใช้จ่าย',
    'account' => 'บัญชี',
    'table' => 'โต๊ะ',
    'modifiers' => 'ตัวแก้ไข',
    'service_staff' => 'บริการพนักงาน',
    'enable_bookings' => 'เปิดใช้งานการจอง',
    'order_for_restaurants' => 'สั่งอาหาร (สำหรับร้านอาหาร)',
    'enable_subscription' => 'เปิดใช้งานการสมัครสมาชิก',
    'types_of_service' => 'ประเภทบริการ',

    // Custom Label
    'custom_payment_1' => 'การชำระเงินแบบกำหนดเอง 1',
    'custom_payment_2' => 'การชำระเงินแบบกำหนดเอง 2',
    'custom_payment_3' => 'การชำระเงินแบบกำหนดเอง 3',
    'custom_payment_4' => 'การชำระเงินแบบกำหนดเอง 4',
    'custom_payment_5' => 'การชำระเงินแบบกำหนดเอง 5',
    'custom_payment_6' => 'การชำระเงินแบบกำหนดเอง 6',
    'custom_payment_7' => 'การชำระเงินแบบกำหนดเอง 7',
    'contact_custom_field_1' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 1',
    'contact_custom_field_2' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 2',
    'contact_custom_field_3' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 3',
    'contact_custom_field_4' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 4',
    'contact_custom_field_5' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 5',
    'contact_custom_field_6' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 6',
    'contact_custom_field_7' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 7',
    'contact_custom_field_8' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 8',
    'contact_custom_field_9' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 9',
    'contact_custom_field_10' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับผู้ติดต่อ 10',
    'product_custom_field_1' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับสินค้า 1',
    'product_custom_field_2' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับสินค้า 2',
    'product_custom_field_3' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับสินค้า 3',
    'product_custom_field_4' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับสินค้า 4',
    'location_custom_field_1' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับตำแหน่ง 1',
    'location_custom_field_2' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับตำแหน่ง 2',
    'location_custom_field_3' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับตำแหน่ง 3',
    'location_custom_field_4' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับตำแหน่ง 4',
    'purchase_shipping_custom_field_1' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการซื้อ 1',
    'purchase_shipping_custom_field_2' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการซื้อ 2',
    'purchase_shipping_custom_field_3' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการซื้อ 3',
    'purchase_shipping_custom_field_4' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการซื้อ 4',
    'purchase_shipping_custom_field_5' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการซื้อ 5',
    'sell_custom_field_1' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการขาย 1',
    'sell_custom_field_2' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการขาย 2',
    'sell_custom_field_3' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการขาย 3',
    'sell_custom_field_4' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการขาย 4',
    'sale_shipping_custom_field_1' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการขาย 1',
    'sale_shipping_custom_field_2' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการขาย 2',
    'sale_shipping_custom_field_3' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการขาย 3',
    'sale_shipping_custom_field_4' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการขาย 4',
    'sale_shipping_custom_field_5' => 'ช่องป้อนข้อมูลที่กำหนดเองสำหรับการจัดส่งสินค้าในการขาย 5',
    'service_custom_payment_1' => 'การชำระเงินแบบกำหนดเองสำหรับบริการ 1',
    'service_custom_payment_2' => 'การชำระเงินแบบกำหนดเองสำหรับบริการ 2',
    'service_custom_payment_3' => 'การชำระเงินแบบกำหนดเองสำหรับบริการ 3',
    'service_custom_payment_4' => 'การชำระเงินแบบกำหนดเองสำหรับบริการ 4',
    'service_custom_payment_5' => 'การชำระเงินแบบกำหนดเองสำหรับบริการ 5',
    'service_custom_payment_6' => 'การชำระเงินแบบกำหนดเองสำหรับบริการ 6',


    'business' => 'ธุรกิจ',
    'tax' => 'ภาษี',
    'product' => 'สินค้า',
    'contact' => 'ติดต่อ',
    'payment' => 'การชำระเงิน',
    'dashboard' => 'แดชบอร์ด',
    'system' => 'ระบบ',
    'prefix' => 'คำนำหน้า',
    'sms_setting' => 'การตั้งค่า SMS',

    'reward_point_setting' => 'การตั้งค่าคะแนนสะสม',
    'modules' => 'โมดูล',
    'customs_label' => 'ป้ายกำกับแบบกำหนดเอง',
    'owner_name' => 'ชื่อเจ้าของ',
    'lot_control' => 'ควบคุม Lot',
    'use_payment_account' => 'ใช้บัญชีการชำระเงิน',
    'upload_logo' => 'อัปโหลดโลโก้',
    'browses' => 'เรียกดู',

    'fifo' => 'FIFO (ครั้งแรกเข้าครั้งแรกออก)',
    'lifo' => 'LIFO (ครั้งสุดท้ายเข้าครั้งแรกออก)',
    'number_of_days_from_transaction_date' => 'จำนวนวันจากวันที่ธุรกรรมจนถึงวันที่สามารถแก้ไขธุรกรรมได้',

    'currency_decimal_places' => 'ทศนิยมสกุลเงิน',
    'quantity_decimal_places' => 'ทศนิยมปริมาณ',
    'add_item_expiry' => 'เพิ่มวันหมดอายุสินค้า',
    'add_manufacturing_date_and_expiry_period' => 'เพิ่มวันผลิตและระยะเวลาหมดอายุ',
    'on_product_expiry' => 'เมื่อสินค้าหมดอายุ',
    'keep_selling' => 'จัดเก็บการขาย',
    'stop_selling_n_day_before' => 'หยุดการขาย n วันก่อน',
    'enable_sub_categories' => 'เปิดใช้งานหมวดหมู่ย่อย',
    'enable_price_and_tax_info' => 'เปิดใช้งานข้อมูลราคาและภาษี',
    'please_select' => 'โปรดเลือก',
    'enable_sub_units' => 'เปิดใช้งานหน่วยย่อย',
    'enable_row' => 'เปิดใช้งานแถว',
    'sale' => 'การขาย',
    'none' => 'ไม่มี',
    'add_item_in_new_row' => 'เพิ่มรายการในแถวใหม่',
    'increase_item_quantity_if_it_already_exists' => 'เพิ่มปริมาณรายการหากมีอยู่แล้ว',
    'round_to_nearest_whole_number' => 'ปัดเศษใกล้ที่สุดเป็นจำนวนเต็ม',
    'commission_agent' => 'ตัวแทนค่าคอมมิชชั่น',
    'disabled' => 'ปิดการใช้งาน',
    'logged_in_user' => 'ผู้ใช้ที่เข้าสู่ระบบ',
    'select_from_user_list' => 'เลือกจากรายการผู้ใช้',
    'select_from_commission_agent_list' => 'เลือกจากรายการตัวแทนค่าคอมมิชชั่น',
    'invoice_value' => 'มูลค่าใบแจ้งหนี้',
    'payment_received' => 'การรับชำระเงิน',
    'payment_link' => 'ลิงก์การชำระเงิน',
    'razorpay' => 'Razorpay',
    'for_inr_india' => '(สำหรับ INR อินเดีย)',
    'key_id' => 'รหัสคีย์',
    'key_secret' => 'ความลับคีย์',
    'stripe' => 'Stripe',
    'add_keyboard_shortcuts' => 'เพิ่มลัดหน้าคีย์บอร์ด',
    'shortcut_should_be_the_names_of_the_keys_separated_by' => 'ลัดหน้าคีย์บอร์ดควรเป็นชื่อของปุ่มที่แยกกันด้วย',
    'example' => 'ตัวอย่าง',
    'available_key_names_are' => 'ชื่อปุ่มที่ใช้ได้คือ',
    'operations' => 'การดำเนินงาน',
    'keyboard_shortcut' => 'ลัดหน้าคีย์บอร์ด',
    'edit_discount' => 'แก้ไขส่วนลด',
    'pos_settings' => 'การตั้งค่า POS',
    'disable_draft' => 'ปิดใช้งานแบบร่าง',
    'disable_express_checkout' => 'ปิดใช้งานการชำระเงินด่วน',
    'pos_screen' => 'หน้าจอ POS',
    'all_screen' => 'หน้าจอทั้งหมด',
    'cash' => 'เงินสด',
    'card' => 'บัตร',
    'cheque' => 'เช็ค',
    'bank_transfer' => 'การโอนเงินผ่านธนาคาร',
    'other' => 'อื่นๆ',
    'dashboard_setting' => 'การตั้งค่าแดชบอร์ด',
    'day' => 'วัน',
    'system_setting' => 'การตั้งค่าระบบ',
    'send_test_mail' => 'ส่งอีเมลทดสอบ',
    'url' => 'URL',
    'comma_separated_values_example' => 'ตัวอย่างค่าที่แยกด้วยเครื่องหมายจุลภาค',
    'cash_denominations' => 'การหารค่าเงินสด',
    'earning_points_settings' => 'การตั้งค่าคะแนนที่ได้รับ',

    'express' => 'ด่วน',
    'custom_field_1' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 1',
    'custom_field_2' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 2',
    'custom_field_3' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 3',
    'custom_field_4' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 4',
    'custom_field_5' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 5',
    'custom_field_6' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 6',
    'custom_field_7' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 7',
    'custom_field_8' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 8',
    'custom_field_9' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 9',
    'custom_field_10' => 'ช่องป้อนข้อมูลแบบกำหนดเอง 10',
    'product_sku_length' => 'ความยาว SKU สินค้า',
    'configure_barcode_as_per_your_weighing_scale' => 'กำหนดรหัสบาร์โค้ดตามตัวชั่งของคุณ',
    'weighing_sale_barcode_setting' => 'การตั้งค่าบาร์โค้ดสำหรับการชั่งขาย',
    'draft' => 'ร่าง',
    'email_setting' => 'การตั้งค่าอีเมล',
    'smtp' => 'SMTP',
    'host' => 'โฮสต์',
    'port' => 'พอร์ต',
    'username' => 'ชื่อผู้ใช้',
    'password' => 'รหัสผ่าน',
    'encryption' => 'การเข้ารหัส',
    'from_name' => 'จากชื่อ',
    'from_address' => 'จากที่อยู่',
    'request_method' => 'วิธีการร้องขอ',
    'get' => 'GET',
    'post' => 'POST',
    'from' => 'จาก',
    'redeem_points_settings' => 'การตั้งค่าการแลกรางวัล',
    'enable_disable_modules' => 'เปิด/ปิดใช้งานโมดูล',
    'tables' => 'ตาราง',
    'custom_labels' => 'ป้ายกำกับแบบกำหนดเอง',
    'labels_for_custom_payments' => 'ป้ายกำกับสำหรับการชำระเงินแบบกำหนดเอง',
    'labels_for_contact_custom_fields' => 'ป้ายกำกับสำหรับช่องป้อนข้อมูลแบบกำหนดเองสำหรับติดต่อ',
    'labels_for_product_custom_fields' => 'ป้ายกำกับสำหรับช่องป้อนข้อมูลแบบกำหนดเองสำหรับสินค้า',
    'labels_for_location_custom_fields' => 'ป้ายกำกับสำหรับช่องป้อนข้อมูลแบบกำหนดเองสำหรับตำแหน่ง',
    'labels_for_user_custom_fields' => 'ป้ายกำกับสำหรับช่องป้อนข้อมูลแบบกำหนดเองสำหรับผู้ใช้',
    'labels_for_purchase_shipping_custom_fields' => 'ป้ายกำกับสำหรับช่องป้อนข้อมูลแบบกำหนดเองสำหรับการจัดส่งสินค้าที่ซื้อ',
    'labels_for_sell_custom_fields' => 'ป้ายกำกับสำหรับช่องป้อนข้อมูลแบบกำหนดเองสำหรับการขาย',
    'labels_for_sale_shipping_custom_fields' => 'ป้ายกำกับสำหรับช่องป้อนข้อมูลแบบกำหนดเองสำหรับการจัดส่งสินค้าที่ขาย',
    'is_required' => 'จำเป็น',
    'is_default_for_contact' => 'ค่าเริ่มต้นสำหรับติดต่อหรือไม่?',
    'labels_for_types_of_service_custom_fields' => 'ป้ายกำกับสำหรับช่องป้อนข้อมูลแบบกำหนดเองสำหรับประเภทบริการ',

    'save' => 'บันทึก',


];