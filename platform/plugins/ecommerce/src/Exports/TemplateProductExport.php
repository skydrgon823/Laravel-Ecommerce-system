<?php

namespace Botble\Ecommerce\Exports;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Enums\StockStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\BrandInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Botble\Ecommerce\Repositories\Interfaces\TaxInterface;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Carbon\Carbon;
use EcommerceHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TemplateProductExport implements
    FromCollection,
    WithHeadings,
    WithEvents,
    WithStrictNullComparison,
    WithColumnWidths,
    ShouldAutoSize
{
    use Exportable;

    /**
     * @var Collection
     */
    protected $results;

    /**
     * @var string
     */
    protected $exportType;

    /**
     * @var int
     */
    protected $totalRow;

    /**
     * @var Collection
     */
    protected $taxes;

    /**
     * @var Collection
     */
    protected $brands;

    /**
     * @var bool
     */
    protected $enabledDigital;

    /**
     * @var bool
     */
    protected $isMarketplaceActive;

    /**
     * @param string $exportType
     */
    public function __construct(string $exportType = Excel::XLSX)
    {
        $this->exportType = $exportType;

        $productNames = collect([
            'Bread - Sour Sticks With Onion',
            'Cheese - Cheddar, Mild',
            'Creme De Banane - Marie',
        ]);

        $descriptions = collect([
            'Praesent blandit. Nam nulla. Integer pede justo, lacinia eget, tincidunt eget, tempus vel, pede.',
            'Proin eu mi. Nulla ac enim. In tempor, turpis nec euismod scelerisque, quam turpis adipiscing lorem.',
            'Cras mi pede, malesuada in, imperdiet et, commodo vulputate, justo. In blandit ultrices enim.',
        ]);

        $productName = $productNames->random();

        $categories = app(ProductCategoryInterface::class)->getModel()->inRandomOrder()->limit(2)->get();
        $brands = app(BrandInterface::class)->pluck('name', 'id');
        $this->brands = collect($brands);
        $taxes = app(TaxInterface::class)->pluck('title', 'id');
        $this->taxes = collect($taxes);

        $productTags = app(ProductTagInterface::class)->getModel()->inRandomOrder()->limit(2)->get();

        $productAttributeSets = app(ProductAttributeSetInterface::class)->getModel()->inRandomOrder()->limit(2)->get();
        $price = rand(20, 100);

        $attributeSets = $productAttributeSets->sortByDesc('order');

        $this->isMarketplaceActive = is_plugin_active('marketplace');

        $this->enabledDigital = EcommerceHelper::isEnabledSupportDigitalProducts();

        $product = [
            'name'                             => $productName,
            'description'                      => $descriptions->random(),
            'slug'                             => '',
            'sku'                              => Str::upper(Str::random(7)),
            'auto_generate_sku'                => '',
            'categories'                       => $categories->pluck('name')->implode(','),
            'status'                           => BaseStatusEnum::PUBLISHED,
            'is_featured'                      => Arr::random(['Yes', 'No']),
            'brand'                            => $this->brands->count() ? $this->brands->random() : null,
            'product_collections'              => '',
            'labels'                           => '',
            'tax'                              => $this->taxes->count() ? $this->taxes->random() : null,
            'images'                           => 'products/1.jpg',
            'price'                            => $price,
            'product_attributes'               => $attributeSets->pluck('title')->implode(','),
            'import_type'                      => 'product',
            'is_variation_default'             => '',
            'stock_status'                     => '',
            'with_storehouse_management'       => '',
            'quantity'                         => '',
            'allow_checkout_when_out_of_stock' => '',
            'sale_price'                       => '',
            'start_date_sale_price'            => '',
            'end_date_sale_price'              => '',
            'weight'                           => '',
            'length'                           => '',
            'wide'                             => '',
            'height'                           => '',
            'content'                          => '',
            'tags'                             => $productTags->pluck('name')->implode(','),
        ];

        if ($this->enabledDigital) {
            $product['product_type'] = ProductTypeEnum::PHYSICAL;
        }

        if ($this->isMarketplaceActive) {
            $stores = app(StoreInterface::class)->pluck('name', 'id');
            $stores = collect($stores);

            $product['vendor'] = $stores->count() ? $stores->random() : null;
        }

        $attributes1 = [];
        foreach ($attributeSets as $set) {
            $attributes1[] = $set->title . ':' . ($set->attributes->count() ? $set->attributes->random()->title : null);
        }

        $productVariation1 = [
            'name'                             => $productName,
            'description'                      => '',
            'slug'                             => '',
            'sku'                              => '',
            'auto_generate_sku'                => 'Yes',
            'categories'                       => '',
            'status'                           => BaseStatusEnum::PUBLISHED,
            'is_featured'                      => Arr::random(['Yes', 'No']),
            'brand'                            => '',
            'product_collections'              => '',
            'labels'                           => '',
            'tax'                              => '',
            'images'                           => 'products/1.jpg,products/2.jpg',
            'price'                            => $price,
            'product_attributes'               => implode(',', $attributes1),
            'import_type'                      => 'variation',
            'is_variation_default'             => 'Yes',
            'stock_status'                     => StockStatusEnum::IN_STOCK,
            'with_storehouse_management'       => 'Yes',
            'quantity'                         => rand(20, 300),
            'allow_checkout_when_out_of_stock' => '',
            'sale_price'                       => $price - rand(2, 5),
            'start_date_sale_price'            => Carbon::now()->startOfDay()->format('Y-m-d H:i:s'),
            'end_date_sale_price'              => Carbon::now()->addDays(20)->endOfDay()->format('Y-m-d H:i:s'),
            'weight'                           => rand(20, 300),
            'length'                           => rand(20, 300),
            'wide'                             => rand(20, 300),
            'height'                           => rand(20, 300),
            'content'                          => '',
            'tags'                             => '',
        ];

        $attributes2 = [];
        foreach ($attributeSets as $set) {
            $attr = $set->title . ':' . ($set->attributes->count() ? $set->attributes->random()->title : null);

            if (in_array($attr, $attributes1)) {
                $attr = $set->title . ':' . ($set->attributes->count() ? $set->attributes->random()->title : null);
            }

            $attributes2[] = $attr;
        }

        $productVariation2 = [
            'name'                             => $productName,
            'description'                      => '',
            'slug'                             => '',
            'sku'                              => '',
            'auto_generate_sku'                => 'Yes',
            'categories'                       => '',
            'status'                           => BaseStatusEnum::PUBLISHED,
            'is_featured'                      => Arr::random(['Yes', 'No']),
            'brand'                            => '',
            'product_collections'              => '',
            'labels'                           => '',
            'tax'                              => '',
            'images'                           => 'products/1.jpg,products/3.jpg',
            'price'                            => $price,
            'product_attributes'               => implode(',', $attributes2),
            'import_type'                      => 'variation',
            'is_variation_default'             => 'No',
            'stock_status'                     => StockStatusEnum::IN_STOCK,
            'with_storehouse_management'       => 'No',
            'quantity'                         => '',
            'allow_checkout_when_out_of_stock' => '',
            'sale_price'                       => $price,
            'start_date_sale_price'            => '',
            'end_date_sale_price'              => '',
            'weight'                           => rand(20, 300),
            'length'                           => rand(20, 300),
            'wide'                             => rand(20, 300),
            'height'                           => rand(20, 300),
            'content'                          => '',
            'tags'                             => '',
        ];

        $this->results = collect([
            $product,
            $productVariation1,
            $productVariation2,
        ]);

        $this->totalRow = $exportType == Excel::XLSX ? 100 : ($this->results->count() + 1);
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->results;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            'name'                             => 'Product name',
            'description'                      => 'Description',
            'slug'                             => 'Slug',
            'sku'                              => 'SKU',
            'auto_generate_sku'                => 'Auto Generate SKU',
            'categories'                       => 'Categories',
            'status'                           => 'Status',
            'is_featured'                      => 'Is featured?',
            'brand'                            => 'Brand',
            'product_collections'              => 'Product collections',
            'labels'                           => 'Labels',
            'tax'                              => 'Tax',
            'images'                           => 'Images',
            'price'                            => 'Price',
            'product_attributes'               => 'Product attributes',
            'import_type'                      => 'Import type',
            'is_variation_default'             => 'Is variation default?',
            'stock_status'                     => 'Stock status',
            'with_storehouse_management'       => 'With storehouse management',
            'quantity'                         => 'Quantity',
            'allow_checkout_when_out_of_stock' => 'Allow checkout when out of stock',
            'sale_price'                       => 'Sale price',
            'start_date_sale_price'            => 'Start date sale price',
            'end_date_sale_price'              => 'End date sale price',
            'weight'                           => 'Weight',
            'length'                           => 'Length',
            'wide'                             => 'Wide',
            'height'                           => 'Height',
            'content'                          => 'Content',
            'tags'                             => 'Tags',
        ];

        if ($this->enabledDigital) {
            $headings['product_type'] = 'Product type';
        }

        if ($this->isMarketplaceActive) {
            $headings['vendor'] = 'Vendor';
        }

        return $headings;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {
                $statusColumn = 'G';
                $stockColumn = 'R';
                $autoGenerateSKUColumn = 'E';
                $isFeaturedColumn = 'H';
                $brandColumn = 'I';
                $taxColumn = 'L';

                $importTypeColumn = 'P';
                $isVariationDefaultColumn = 'Q';
                $withStorehouseManagementColumn = 'S';
                $allowCheckoutWhenOutOfStockColumn = 'U';
                $quantityColumn = 'T';
                $priceColumn = 'N';
                $saleColumn = 'V';
                $weightColumn = 'Y';
                $lengthColumn = 'Z';
                $wideColumn = 'AA';
                $heightColumn = 'AB';
                $productTypeColumn = 'AE';

                // set dropdown list for first data row
                $statusValidation = $this->getStatusValidation();
                $stockValidation = $this->getStockValidation();
                $booleanValidation = $this->getBooleanValidation();
                $importTypeValidation = $this->getImportTypeValidation();
                $wholeNumberValidation = $this->getWholeNumberValidation();
                $decimalValidation = $this->getDecimalValidation();
                $taxValidation = $this->getTaxValidation();
                $brandValidation = $this->getBrandValidation();

                $productTypeValidation = $this->getProductTypeValidation();

                // clone validation to remaining rows
                for ($index = 2; $index <= $this->totalRow; $index++) {
                    $event->sheet->getCell($statusColumn . $index)->setDataValidation($statusValidation);
                    $event->sheet->getCell($stockColumn . $index)->setDataValidation($stockValidation);
                    $event->sheet->getCell($autoGenerateSKUColumn . $index)->setDataValidation($booleanValidation);
                    $event->sheet->getCell($isFeaturedColumn . $index)->setDataValidation($booleanValidation);
                    $event->sheet->getCell($brandColumn . $index)->setDataValidation($brandValidation);
                    $event->sheet->getCell($taxColumn . $index)->setDataValidation($taxValidation);

                    $event->sheet->getCell($importTypeColumn . $index)->setDataValidation($importTypeValidation);
                    $event->sheet->getCell($isVariationDefaultColumn . $index)->setDataValidation($booleanValidation);
                    $event->sheet->getCell($withStorehouseManagementColumn . $index)
                        ->setDataValidation($booleanValidation);
                    $event->sheet->getCell($allowCheckoutWhenOutOfStockColumn . $index)
                        ->setDataValidation($booleanValidation);

                    $event->sheet->getCell($quantityColumn . $index)->setDataValidation($wholeNumberValidation);

                    $event->sheet->getCell($weightColumn . $index)->setDataValidation($decimalValidation);
                    $event->sheet->getCell($lengthColumn . $index)->setDataValidation($decimalValidation);
                    $event->sheet->getCell($wideColumn . $index)->setDataValidation($decimalValidation);
                    $event->sheet->getCell($heightColumn . $index)->setDataValidation($decimalValidation);
                    $event->sheet->getCell($saleColumn . $index)->setDataValidation($decimalValidation);
                    $event->sheet->getCell($priceColumn . $index)->setDataValidation($decimalValidation);

                    if ($this->enabledDigital) {
                        $event->sheet->getCell($productTypeColumn . $index)->setDataValidation($productTypeValidation);
                    }
                }

                $delegate = $event->sheet->getDelegate();
                foreach ($this->columnFormats() as $column => $format) {
                    $delegate
                        ->getStyle($column)
                        ->getNumberFormat()
                        ->setFormatCode($format);
                }

                $delegate->getStyle('A1'); // Reset selected
            },
        ];
    }

    /**
     * @return DataValidation
     */
    protected function getStatusValidation()
    {
        return $this->getDropDownListValidation(BaseStatusEnum::values());
    }

    /**
     * @return DataValidation
     */
    protected function getProductTypeValidation()
    {
        return $this->getDropDownListValidation(ProductTypeEnum::values());
    }

    /**
     * @param array $options
     * @return DataValidation
     */
    protected function getDropDownListValidation($options)
    {
        // set dropdown list for first data row
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle(trans('plugins/ecommerce::bulk-import.export.template.input_error'));
        $validation->setError(trans('plugins/ecommerce::bulk-import.export.template.value_not_in_list'));
        $validation->setPromptTitle(trans('plugins/ecommerce::bulk-import.export.template.pick_from_list'));
        $validation->setPrompt(trans('plugins/ecommerce::bulk-import.export.template.prompt_list'));
        $validation->setFormula1(sprintf('"%s"', implode(',', $options)));

        return $validation;
    }

    /**
     * @return DataValidation
     */
    protected function getStockValidation()
    {
        return $this->getDropDownListValidation(StockStatusEnum::values());
    }

    /**
     * @return DataValidation
     */
    protected function getBooleanValidation()
    {
        return $this->getDropDownListValidation(['No', 'Yes']);
    }

    /**
     * @return DataValidation
     */
    protected function getImportTypeValidation()
    {
        return $this->getDropDownListValidation(['product', 'variation']);
    }

    /**
     * @param int $min
     * @return DataValidation
     */
    protected function getWholeNumberValidation($min = 0)
    {
        // set dropdown list for first data row
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle(trans('plugins/ecommerce::bulk-import.export.template.input_error'));
        $validation->setError(trans('plugins/ecommerce::bulk-import.export.template.number_not_allowed'));
        $validation->setPromptTitle(trans('plugins/ecommerce::bulk-import.export.template.allowed_input'));
        $validation->setPrompt(trans(
            'plugins/ecommerce::bulk-import.export.template.prompt_whole_number',
            compact('min')
        ));
        $validation->setFormula1($min);
        $validation->setOperator(DataValidation::OPERATOR_GREATERTHANOREQUAL);

        return $validation;
    }

    /**
     * @param int $min
     * @return DataValidation
     */
    protected function getDecimalValidation($min = 0)
    {
        // set dropdown list for first data row
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_DECIMAL);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle(trans('plugins/ecommerce::bulk-import.export.template.input_error'));
        $validation->setError(trans('plugins/ecommerce::bulk-import.export.template.number_not_allowed'));
        $validation->setPromptTitle(trans('plugins/ecommerce::bulk-import.export.template.allowed_input'));
        $validation->setPrompt(trans('plugins/ecommerce::bulk-import.export.template.prompt_decimal', compact('min')));
        $validation->setFormula1($min);
        $validation->setOperator(DataValidation::OPERATOR_GREATERTHANOREQUAL);

        return $validation;
    }

    /**
     * @return DataValidation
     */
    protected function getTaxValidation()
    {
        return $this->getDropDownListValidation(['-- None --'] + $this->taxes->toArray());
    }

    /**
     * @return DataValidation
     */
    protected function getBrandValidation()
    {
        return $this->getDropDownListValidation(['-- None --'] + $this->brands->toArray());
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        if ($this->exportType != Excel::XLSX) {
            return [];
        }

        return [
            'A2:A' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'B2:B' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'C2:C' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'D2:D' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'F2:F' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'I2:I' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'J2:J' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'K2:K' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'L2:L' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'M2:M' . $this->totalRow   => NumberFormat::FORMAT_TEXT,
            'N2:N' . $this->totalRow   => NumberFormat::FORMAT_NUMBER_00,
            'T2:T' . $this->totalRow   => NumberFormat::FORMAT_NUMBER,
            'V2:V' . $this->totalRow   => NumberFormat::FORMAT_NUMBER_00,
            'W2:W' . $this->totalRow   => 'yyyy-mm-dd hh:mm:ss',
            'X2:X' . $this->totalRow   => 'yyyy-mm-dd hh:mm:ss',
            'Y2:Y' . $this->totalRow   => NumberFormat::FORMAT_GENERAL,
            'Z2:Z' . $this->totalRow   => NumberFormat::FORMAT_GENERAL,
            'AA2:AA' . $this->totalRow => NumberFormat::FORMAT_GENERAL,
            'AB2:AB' . $this->totalRow => NumberFormat::FORMAT_GENERAL,
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 30,
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name'                             => 'required',
            'description'                      => 'nullable',
            'slug'                             => 'nullable',
            'sku'                              => 'nullable|multiple',
            'auto_generate_sku'                => 'nullable|string (Yes or No)|default: Yes',
            'categories'                       => 'nullable|multiple',
            'status'                           => 'required|enum:' . implode(',', BaseStatusEnum::values()) . '|default:' . BaseStatusEnum::PENDING,
            'is_featured'                      => 'nullable|string (Yes or No)|default: No',
            'brand'                            => 'nullable|[Brand name | Brand ID]',
            'product_collections'              => 'nullable|[Product collection name | Product collection ID]|multiple',
            'labels'                           => 'nullable|[Product label name | Product label ID]|multiple',
            'tax'                              => 'nullable|[Tax name | Tax ID]|default:0',
            'images'                           => 'nullable|string|multiple',
            'price'                            => 'nullable|number',
            'product_attributes'               => 'nullable|string',
            'import_type'                      => 'nullable|enum:product,variation|default:product',
            'is_variation_default'             => 'nullable|bool|default:false',
            'stock_status'                     => 'nullable|enum:' . implode(',', StockStatusEnum::values()) .'|default:' . StockStatusEnum::IN_STOCK,
            'with_storehouse_management'       => 'nullable|bool|default:0',
            'quantity'                         => 'nullable|number',
            'allow_checkout_when_out_of_stock' => 'nullable|bool|default:0',
            'sale_price'                       => 'nullable|number',
            'start_date_sale_price'            => 'nullable|datetime|date_format:Y-m-d H:i:s',
            'end_date_sale_price'              => 'nullable|datetime|date_format:Y-m-d H:i:s',
            'weight'                           => 'nullable|number',
            'length'                           => 'nullable|number',
            'wide'                             => 'nullable|number',
            'height'                           => 'nullable|number',
            'content'                          => 'nullable',
            'tags'                             => 'nullable|[Product tag name]|multiple',
        ];

        if ($this->enabledDigital) {
            $rules['product_type'] = 'nullable|enum:' . implode(',', ProductTypeEnum::values()) .'|default:' . ProductTypeEnum::PHYSICAL;
        }

        if ($this->isMarketplaceActive) {
            $rules['vendor'] = 'nullable|[Vendor name | Vendor ID]';
        }

        return $rules;
    }
}
