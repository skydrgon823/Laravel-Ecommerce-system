<?php

namespace Botble\Ecommerce\Imports;

use Botble\Ecommerce\Models\ProductVariation;
use Maatwebsite\Excel\Validators\Failure;

class ValidateProductImport extends ProductImport
{
    /**
     * @param array $row
     *
     * @return null
     */
    public function model(array $row)
    {
        $importType = $this->getImportType();

        $name = $this->request->input('name');

        if ($importType == 'products' && $row['import_type'] == 'product') {
            return $this->storeProduction();
        }

        if ($importType == 'variations' && $row['import_type'] == 'variation') {
            $product = $this->getProductByName($name);

            return $this->storeVariant($product);
        }

        if ($row['import_type'] == 'variation') {
            $collection = $this->successes()
                ->where('import_type', 'product')
                ->where('name', $name)
                ->first();

            if ($collection) {
                $product = $collection['model'];
            } else {
                $product = $this->getProductByName($name);
            }

            return $this->storeVariant($product);
        }

        return $this->storeProduction();
    }

    /**
     * @return null
     */
    public function storeProduction()
    {
        $product = collect($this->request->all());
        $collect = collect([
            'name'        => $product['name'],
            'import_type' => 'product',
            'model'       => $product,
        ]);
        $this->onSuccess($collect);

        return null;
    }
    /**
     * @return null
     */
    public function storeVariant($product): ?ProductVariation
    {
        if (!$product) {
            if (method_exists($this, 'onFailure')) {
                $failures[] = new Failure(
                    $this->rowCurrent,
                    'Product Name',
                    [__('Product name ":name" does not exists', ['name' => $this->request->input('name')])],
                    []
                );
                $this->onFailure(...$failures);
            }

            return null;
        }

        return null;
    }
}
