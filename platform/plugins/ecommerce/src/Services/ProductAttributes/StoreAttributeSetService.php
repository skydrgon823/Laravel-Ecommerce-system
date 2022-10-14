<?php

namespace Botble\Ecommerce\Services\ProductAttributes;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Botble\Ecommerce\Repositories\Eloquent\ProductAttributeRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductAttributeSetRepository;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StoreAttributeSetService
{
    /**
     * @var ProductAttributeSetRepository
     */
    protected $productAttributeSetRepository;

    /**
     * @var ProductAttributeRepository
     */
    protected $productAttributeRepository;

    /**
     * StoreAttributeSetService constructor.
     * @param ProductAttributeSetInterface $productAttributeSet
     * @param ProductAttributeInterface $productAttribute
     */
    public function __construct(
        ProductAttributeSetInterface $productAttributeSet,
        ProductAttributeInterface $productAttribute
    ) {
        $this->productAttributeSetRepository = $productAttributeSet;
        $this->productAttributeRepository = $productAttribute;
    }

    /**
     * @param Request $request
     * @param ProductAttributeSet $productAttributeSet
     * @return false|\Illuminate\Database\Eloquent\Builder|Model|\Illuminate\Database\Query\Builder|object|null
     * @throws Exception
     */
    public function execute(Request $request, ProductAttributeSet $productAttributeSet)
    {
        $data = $request->input();

        if (!$productAttributeSet->id) {
            $isUpdated = false;
        } else {
            $isUpdated = true;
        }

        $productAttributeSet->fill($data);

        $productAttributeSet = $this->productAttributeSetRepository->createOrUpdate($productAttributeSet);

        if (!$isUpdated) {
            event(new CreatedContentEvent(PRODUCT_ATTRIBUTE_SETS_MODULE_SCREEN_NAME, $request, $productAttributeSet));
        } else {
            event(new UpdatedContentEvent(PRODUCT_ATTRIBUTE_SETS_MODULE_SCREEN_NAME, $request, $productAttributeSet));
        }

        $attributes = json_decode($request->input('attributes', '[]'), true) ?: [];
        $deletedAttributes = json_decode($request->input('deleted_attributes', '[]'), true) ?: [];

        $this->deleteAttributes($productAttributeSet->id, $deletedAttributes);
        $this->storeAttributes($productAttributeSet->id, $attributes);

        return $productAttributeSet;
    }

    /**
     * @param int $productAttributeSetId
     * @param array $attributeIds
     * @throws Exception
     */
    protected function deleteAttributes(int $productAttributeSetId, array $attributeIds)
    {
        foreach ($attributeIds as $id) {
            $attribute = $this->productAttributeRepository
                ->getFirstBy([
                    'id'               => $id,
                    'attribute_set_id' => $productAttributeSetId,
                ]);

            if ($attribute) {
                $attribute->delete();
                event(new DeletedContentEvent(PRODUCT_ATTRIBUTES_MODULE_SCREEN_NAME, request(), $attribute));
            }
        }
    }

    /**
     * @param int $productAttributeSetId
     * @param array $attributes
     */
    protected function storeAttributes(int $productAttributeSetId, array $attributes)
    {
        foreach ($attributes as $item) {
            if (isset($item['id'])) {
                $attribute = $this->productAttributeRepository->findById($item['id']);
                if (!$attribute) {
                    $item['attribute_set_id'] = $productAttributeSetId;
                    $attribute = $this->productAttributeRepository->create($item);

                    event(new CreatedContentEvent(PRODUCT_ATTRIBUTES_MODULE_SCREEN_NAME, request(), $attribute));
                } else {
                    $attribute->fill($item);
                    $attribute = $this->productAttributeRepository->createOrUpdate($attribute);

                    event(new UpdatedContentEvent(PRODUCT_ATTRIBUTES_MODULE_SCREEN_NAME, request(), $attribute));
                }
            }
        }
    }
}
