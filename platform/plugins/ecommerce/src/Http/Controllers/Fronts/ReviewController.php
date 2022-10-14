<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Http\Requests\ReviewRequest;
use Botble\Ecommerce\Repositories\Interfaces\ReviewInterface;
use EcommerceHelper;
use Exception;
use Illuminate\Routing\Controller;
use RvMedia;

class ReviewController extends Controller
{
    /**
     * @var ReviewInterface
     */
    protected $reviewRepository;

    /**
     * @param ReviewInterface $reviewRepository
     */
    public function __construct(ReviewInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * @param ReviewRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(ReviewRequest $request, BaseHttpResponse $response)
    {
        if (!EcommerceHelper::isReviewEnabled()) {
            abort(404);
        }

        $customerId = auth('customer')->id();
        $exists = $this->reviewRepository->count([
            'customer_id' => $customerId,
            'product_id'  => $request->input('product_id'),
        ]);

        if ($exists > 0) {
            return $response
                ->setError()
                ->setMessage(__('You have reviewed this product already!'));
        }

        $results = [];
        if ($request->hasFile('images')) {
            $images = (array)$request->file('images', []);
            foreach ($images as $image) {
                $result = RvMedia::handleUpload($image, 0, 'reviews');
                if ($result['error']) {
                    return $response->setError()->setMessage($result['message']);
                }

                $results[] = $result;
            }
        }

        $request->merge([
            'customer_id' => $customerId,
            'images'      => $results ? collect($results)->pluck('data.url')->values()->toArray() : null,
        ]);

        $this->reviewRepository->createOrUpdate($request->input());

        return $response->setMessage(__('Added review successfully!'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function destroy($id, BaseHttpResponse $response)
    {
        if (!EcommerceHelper::isReviewEnabled()) {
            abort(404);
        }

        $review = $this->reviewRepository->findOrFail($id);

        if (auth()->check() || (auth('customer')->check() && auth('customer')->id() == $review->customer_id)) {
            $this->reviewRepository->delete($review);

            return $response->setMessage(__('Deleted review successfully!'));
        }

        abort(401);
    }
}
