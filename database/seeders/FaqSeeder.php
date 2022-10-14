<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Faq\Models\Faq;
use Botble\Faq\Models\FaqCategory;
use Botble\Faq\Models\FaqCategoryTranslation;
use Botble\Faq\Models\FaqTranslation;
use Botble\Language\Models\LanguageMeta;

class FaqSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faq::truncate();
        FaqCategory::truncate();
        FaqTranslation::truncate();
        FaqCategoryTranslation::truncate();
        LanguageMeta::where('reference_type', FaqCategory::class)->delete();
        LanguageMeta::where('reference_type', Faq::class)->delete();

        $categories = [
            [
                'name' => 'Shipping',
            ],
            [
                'name' => 'Payment',
            ],
            [
                'name' => 'Order & Returns',
            ],
        ];

        foreach ($categories as $index => $value) {
            $value['order'] = $index;
            FaqCategory::create($value);
        }

        $faqItems = [
            [
                'question'    => 'What Shipping Methods Are Available?',
                'answer'      => 'Ex Portland Pitchfork irure mustache. Eutra fap before they sold out literally. Aliquip ugh bicycle rights actually mlkshk, seitan squid craft beer tempor.',
                'category_id' => 1,
            ],
            [
                'question'    => 'Do You Ship Internationally?',
                'answer'      => 'Hoodie tote bag mixtape tofu. Typewriter jean shorts wolf quinoa, messenger bag organic freegan cray.',
                'category_id' => 1,
            ],
            [
                'question'    => 'How Long Will It Take To Get My Package?',
                'answer'      => 'Swag slow-carb quinoa VHS typewriter pork belly brunch, paleo single-origin coffee Wes Anderson. Flexitarian Pitchfork forage, literally paleo fap pour-over. Wes Anderson Pinterest YOLO fanny pack meggings, deep v XOXO chambray sustainable slow-carb raw denim church-key fap chillwave Etsy. +1 typewriter kitsch, American Apparel tofu Banksy Vice.',
                'category_id' => 1,
            ],
            [
                'question'    => 'What Payment Methods Are Accepted?',
                'answer'      => 'Fashion axe DIY jean shorts, swag kale chips meh polaroid kogi butcher Wes Anderson chambray next level semiotics gentrify yr. Voluptate photo booth fugiat Vice. Austin sed Williamsburg, ea labore raw denim voluptate cred proident mixtape excepteur mustache. Twee chia photo booth readymade food truck, hoodie roof party swag keytar PBR DIY.',
                'category_id' => 2,
            ],
            [
                'question'    => 'Is Buying On-Line Safe?',
                'answer'      => 'Art party authentic freegan semiotics jean shorts chia cred. Neutra Austin roof party Brooklyn, synth Thundercats swag 8-bit photo booth. Plaid letterpress leggings craft beer meh ethical Pinterest.',
                'category_id' => 2,
            ],
            [
                'question'    => 'How do I place an Order?',
                'answer'      => 'Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb Odd Future seitan normcore. Master cleanse American Apparel gentrify flexitarian beard slow-carb next level. Raw denim polaroid paleo farm-to-table, put a bird on it lo-fi tattooed Wes Anderson Pinterest letterpress. Fingerstache McSweeney’s pour-over, letterpress Schlitz photo booth master cleanse bespoke hashtag chillwave gentrify.',
                'category_id' => 3,
            ],
            [
                'question'    => 'How Can I Cancel Or Change My Order?',
                'answer'      => 'Plaid letterpress leggings craft beer meh ethical Pinterest. Art party authentic freegan semiotics jean shorts chia cred. Neutra Austin roof party Brooklyn, synth Thundercats swag 8-bit photo booth.',
                'category_id' => 3,
            ],
            [
                'question'    => 'Do I need an account to place an order?',
                'answer'      => 'Thundercats swag 8-bit photo booth. Plaid letterpress leggings craft beer meh ethical Pinterest. Twee chia photo booth readymade food truck, hoodie roof party swag keytar PBR DIY. Cray ugh 3 wolf moon fap, fashion axe irony butcher cornhole typewriter chambray VHS banjo street art.',
                'category_id' => 3,
            ],
            [
                'question'    => 'How Do I Track My Order?',
                'answer'      => 'Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb @Odd Future seitan normcore. Master cleanse American Apparel gentrify flexitarian beard slow-carb next level.',
                'category_id' => 3,
            ],
            [
                'question'    => 'How Can I Return a Product?',
                'answer'      => 'Kale chips Truffaut Williamsburg, hashtag fixie Pinterest raw denim c hambray drinking vinegar Carles street art Bushwick gastropub. Wolf Tumblr paleo church-key. Plaid food truck Echo Park YOLO bitters hella, direct trade Thundercats leggings quinoa before they sold out. You probably haven’t heard of them wayfarers authentic umami drinking vinegar Pinterest Cosby sweater, fingerstache fap High Life.',
                'category_id' => 3,
            ],
        ];

        foreach ($faqItems as $value) {
            Faq::create($value);
        }

        $translations = [
            [
                'name' => 'VẬN CHUYỂN',
            ],
            [
                'name' => 'THANH TOÁN',
            ],
            [
                'name' => 'ĐƠN HÀNG & HOÀN TRẢ',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['faq_categories_id'] = $index + 1;

            FaqCategoryTranslation::insert($item);
        }

        $translations = [
            [
                'question' => 'Có những phương thức vận chuyển nào?',
                'answer'   => 'Ex Portland Pitchfork irure ria mép. Eutra fap trước khi họ bán hết theo đúng nghĩa đen. Aliquip ugh quyền xe đạp thực sự mlkshk, rượu bia thủ công mực seitan. ',
            ],
            [
                'question' => 'Bạn có giao hàng quốc tế không?',
                'answer'   => 'Áo hoodie túi tote Tofu mixtape. Quần đùi jean đánh chữ Wolf quinoa, túi messenger hữu cơ freegan cray. ',
            ],
            [
                'question' => 'Mất bao lâu để nhận được gói hàng của tôi?',
                'answer'   => 'Bữa nửa buổi ăn sáng bằng bụng heo quay từ máy đánh chữ VHS, cà phê có nguồn gốc đơn Paleo, Wes Anderson. Khoan Pitchfork linh hoạt, theo nghĩa đen là đổ qua fap theo nghĩa đen. Wes Anderson Pinterest YOLO fanny pack meggings, deep v XOXO chambray bền vững slow-carb raw denim Church-key fap chillwave Etsy. +1 bộ dụng cụ đánh máy, đậu phụ Banksy Vice của American Apparel. ',
            ],
            [
                'question' => 'Phương thức thanh toán nào được chấp nhận?',
                'answer'   => 'Fashion Axe DIY jean shorts, swag kale chips meh polaroid kogi butcher Wes Anderson chambray next level semiotics gentrify yr. Quầy ảnh Voluptate fugiat Vice. Austin sed Williamsburg, ea labore raw denim voluptate cred proident mixtape excepteur ria mép. Twee chia gian hàng chụp ảnh xe bán đồ ăn sẵn, bữa tiệc trên mái áo hoodie swag keytar PBR DIY. ',
            ],
            [
                'question' => 'Mua trực tuyến có an toàn không?',
                'answer'   => 'Bữa tiệc nghệ thuật đích thực freegan semiotics jean shorts chia credit. Tiệc trên mái nhà Neutra Austin Brooklyn, Thundercats swag synth gian hàng ảnh 8-bit. Xà cạp letterpress kẻ sọc thủ công bia meh đạo đức Pinterest. ',
            ],
            [
                'question' => 'Làm cách nào để đặt hàng?',
                'answer'   => 'Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb Odd Định mức seitan trong tương lai. Master làm sạch American Apparel nhẹ nhàng làm sạch râu linh hoạt chậm carb cấp độ tiếp theo. Vải thô denim polaroid nhạt từ trang trại đến bàn, đặt một con chim trên đó hình xăm lo-fi Wes Anderson Pinterest letterpress. Bậc thầy gian hàng ảnh Schlitz của Fingerstache McSweeney đang làm sạch thẻ bắt đầu bằng hashtag theo yêu cầu riêng, chillwave gentrify. ',
            ],
            [
                'question' => 'Làm cách nào để tôi có thể hủy hoặc thay đổi đơn hàng của mình?',
                'answer'   => 'Xà cạp letterpress kẻ sọc thủ công bia meh đạo đức Pinterest. Bữa tiệc nghệ thuật đích thực freegan semiotics jean shorts chia tín. Tiệc trên mái nhà Neutra Austin ở Brooklyn, synth Thundercats có gian hàng ảnh 8-bit. ',
            ],
            [
                'question' => 'Tôi có cần tài khoản để đặt hàng không?',
                'answer'   => 'Thundercats làm lung lay gian hàng ảnh 8-bit. Xà cạp letterpress kẻ sọc thủ công bia meh đạo đức Pinterest. Twee chia ảnh gian hàng xe bán thức ăn làm sẵn, bữa tiệc trên mái áo hoodie swag keytar PBR DIY. Cray ugh 3 wolf moon fap, rìu thời trang mỉa mai người bán thịt máy đánh chữ chambray VHS banjo nghệ thuật đường phố. ',
            ],
            [
                'question' => 'Làm cách nào để theo dõi đơn hàng của tôi?',
                'answer'   => 'Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb @Odd Định mức seitan trong tương lai. Bậc thầy làm sạch American Apparel nhẹ nhàng làm sạch râu linh hoạt theo kiểu chậm carb cấp độ tiếp theo. ',
            ],
            [
                'question' => 'Làm cách nào để trả lại sản phẩm?',
                'answer'   => 'Kale chips Truffaut Williamsburg, fixie hashtag Pinterest raw denim c hambray uống giấm Carles street art Bushwick gastropub. Chìa khóa nhà thờ Wolf Tumblr. Xe tải thực phẩm kẻ sọc Echo Park YOLO cắn hella, giao dịch trực tiếp Thundercats legging quinoa trước khi bán hết. Có thể bạn chưa từng nghe nói về họ những người truyền bá vị umami đích thực uống giấm Pinterest Áo len Cosby, fingerstache fap High Life. ',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['faqs_id'] = $index + 1;

            FaqTranslation::insert($item);
        }
    }
}
