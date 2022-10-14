<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Arr;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Http\Requests\AddressRequest;
use Botble\Ecommerce\Http\Requests\AvatarRequest;
use Botble\Ecommerce\Http\Requests\EditAccountRequest;
use Botble\Ecommerce\Http\Requests\OrderReturnRequest;
use Botble\Ecommerce\Http\Requests\UpdatePasswordRequest;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Media\Services\ThumbnailService;
use Botble\Media\Supports\Zipper;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\Carbon;
use EcommerceHelper;
use Exception;
use File;
use Hash;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use OrderHelper;
use OrderReturnHelper;
use Response;
use RvMedia;
use SeoHelper;
use Theme;
use Throwable;

class PublicController extends Controller
{
    /**
     * @var CustomerInterface
     */
    protected $customerRepository;

    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var AddressInterface
     */
    protected $addressRepository;

    /**
     * @var OrderInterface
     */
    protected $orderRepository;

    /**
     * @var OrderHistoryInterface
     */
    protected $orderHistoryRepository;

    /**
     * @var OrderReturnInterface
     */
    protected $orderReturnRepository;

    /**
     * @var OrderProductInterface
     */
    protected $orderProductRepository;

    /**
     * PublicController constructor.
     *
     * @param CustomerInterface $customerRepository
     * @param ProductInterface $productRepository
     * @param AddressInterface $addressRepository
     * @param OrderInterface $orderRepository
     * @param OrderHistoryInterface $orderHistoryRepository
     * @param OrderReturnInterface $orderReturnRepository
     * @param OrderProductInterface $orderProductRepository
     */
    public function __construct(
        CustomerInterface     $customerRepository,
        ProductInterface      $productRepository,
        AddressInterface      $addressRepository,
        OrderInterface        $orderRepository,
        OrderHistoryInterface $orderHistoryRepository,
        OrderReturnInterface  $orderReturnRepository,
        OrderProductInterface $orderProductRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->addressRepository = $addressRepository;
        $this->orderRepository = $orderRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
        $this->orderReturnRepository = $orderReturnRepository;
        $this->orderProductRepository = $orderProductRepository;

        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/ecommerce/css/customer.css');

        Theme::asset()
            ->container('footer')
            ->add('ecommerce-utilities-js', 'vendor/core/plugins/ecommerce/js/utilities.js', ['jquery'])
            ->add('cropper-js', 'vendor/core/plugins/ecommerce/libraries/cropper.js', ['jquery'])
            ->add('avatar-js', 'vendor/core/plugins/ecommerce/js/avatar.js', ['jquery']);

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Theme::asset()
                ->container('footer')
                ->add('location-js', 'vendor/core/plugins/location/js/location.js', ['jquery']);
        }
    }

    /**
     * @return Response
     */
    public function getOverview()
    {
        SeoHelper::setTitle(__('Account information'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Account information'), route('customer.overview'));

        return Theme::scope('ecommerce.customers.overview', [], 'plugins/ecommerce::themes.customers.overview')
            ->render();
    }

    /**
     * @return Response
     */
    public function getEditAccount()
    {
        SeoHelper::setTitle(__('Profile'));

        Theme::asset()
            ->add(
                'datepicker-style',
                'vendor/core/core/base/libraries/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                ['bootstrap']
            );
        Theme::asset()
            ->container('footer')
            ->add(
                'datepicker-js',
                'vendor/core/core/base/libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                ['jquery']
            );

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Profile'), route('customer.edit-account'));

        return Theme::scope('ecommerce.customers.edit-account', [], 'plugins/ecommerce::themes.customers.edit-account')
            ->render();
    }

    /**
     * @param EditAccountRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postEditAccount(EditAccountRequest $request, BaseHttpResponse $response)
    {
        $customer = $this->customerRepository->createOrUpdate(
            $request->except('email'),
            [
                'id' => auth('customer')->id(),
            ]
        );

        do_action(HANDLE_CUSTOMER_UPDATED_ECOMMERCE, $customer, $request);

        return $response
            ->setNextUrl(route('customer.edit-account'))
            ->setMessage(__('Update profile successfully!'));
    }

    /**
     * @return Response
     */
    public function getChangePassword()
    {
        SeoHelper::setTitle(__('Change Password'));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(__('Change Password'), route('customer.change-password'));

        return Theme::scope(
            'ecommerce.customers.change-password',
            [],
            'plugins/ecommerce::themes.customers.change-password'
        )->render();
    }

    /**
     * @param UpdatePasswordRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postChangePassword(UpdatePasswordRequest $request, BaseHttpResponse $response)
    {
        $currentUser = auth('customer')->user();

        if (!Hash::check($request->input('old_password'), $currentUser->getAuthPassword())) {
            return $response
                ->setError()
                ->setMessage(trans('acl::users.current_password_not_valid'));
        }

        $this->customerRepository->update(['id' => auth('customer')->id()], [
            'password' => bcrypt($request->input('password')),
        ]);

        return $response->setMessage(trans('acl::users.password_update_success'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getListOrders(Request $request)
    {
        SeoHelper::setTitle(__('Orders'));

        $orders = $this->orderRepository->advancedGet([
            'condition' => [
                'user_id'     => auth('customer')->id(),
                'is_finished' => 1,
            ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page'),
            ],
            'withCount' => ['products'],
            'order_by'  => ['created_at' => 'DESC'],
        ]);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Orders'), route('customer.orders'));

        return Theme::scope(
            'ecommerce.customers.orders.list',
            compact('orders'),
            'plugins/ecommerce::themes.customers.orders.list'
        )->render();
    }

    /**
     * @param int $id
     * @return Response
     */
    public function getViewOrder($id)
    {
        SeoHelper::setTitle(__('Order detail :id', ['id' => get_order_code($id)]));

        $order = $this->orderRepository->getFirstBy(
            [
                'id'      => $id,
                'user_id' => auth('customer')->id(),
            ],
            ['ec_orders.*'],
            ['address', 'products']
        );

        if (!$order) {
            abort(404);
        }

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(
                __('Order detail :id', ['id' => get_order_code($id)]),
                route('customer.orders.view', $id)
            );

        return Theme::scope(
            'ecommerce.customers.orders.view',
            compact('order'),
            'plugins/ecommerce::themes.customers.orders.view'
        )->render();
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     * @throws Throwable
     */
    public function getCancelOrder($id, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->getFirstBy([
            'id'      => $id,
            'user_id' => auth('customer')->id(),
        ], ['*']);

        if (!$order) {
            abort(404);
        }

        if (!$order->canBeCanceled()) {
            return $response->setError()
                ->setMessage(trans('plugins/ecommerce::order.cancel_error'));
        }

        OrderHelper::cancelOrder($order);

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'cancel_order',
            'description' => __('Order was cancelled by custom :customer', ['customer' => $order->address->name]),
            'order_id'    => $order->id,
        ]);

        return $response->setMessage(trans('plugins/ecommerce::order.cancel_success'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getListAddresses(Request $request)
    {
        SeoHelper::setTitle(__('Address books'));

        $addresses = $this->addressRepository->advancedGet([
            'condition' => [
                'customer_id' => auth('customer')->id(),
            ],
            'order_by'  => [
                'is_default' => 'DESC',
                'created_at' => 'DESC',
            ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page', 1),
            ],
        ]);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Address books'), route('customer.address'));

        return Theme::scope(
            'ecommerce.customers.address.list',
            compact('addresses'),
            'plugins/ecommerce::themes.customers.address.list'
        )->render();
    }

    /**
     * @return Response
     */
    public function getCreateAddress()
    {
        SeoHelper::setTitle(__('Create Address'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Address books'), route('customer.address'))
            ->add(__('Create Address'), route('customer.address.create'));

        return Theme::scope(
            'ecommerce.customers.address.create',
            [],
            'plugins/ecommerce::themes.customers.address.create'
        )->render();
    }

    /**
     * @param AddressRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postCreateAddress(AddressRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_default') == 1) {
            $this->addressRepository->update([
                'is_default'  => 1,
                'customer_id' => auth('customer')->id(),
            ], ['is_default' => 0]);
        }

        $request->merge([
            'customer_id' => auth('customer')->id(),
            'is_default'  => $request->input('is_default', 0),
        ]);

        $address = $this->addressRepository->createOrUpdate($request->input());

        return $response
            ->setData([
                'id'   => $address->id,
                'html' => view(
                    'plugins/ecommerce::orders.partials.address-item',
                    compact('address')
                )->render(),
            ])
            ->setNextUrl(route('customer.address'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @return Response
     */
    public function getEditAddress($id)
    {
        SeoHelper::setTitle(__('Edit Address #:id', ['id' => $id]));

        $address = $this->addressRepository->getFirstBy([
            'id'          => $id,
            'customer_id' => auth('customer')->id(),
        ]);

        if (!$address) {
            abort(404);
        }

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(__('Edit Address #:id', ['id' => $id]), route('customer.address.edit', $id));

        return Theme::scope(
            'ecommerce.customers.address.edit',
            compact('address'),
            'plugins/ecommerce::themes.customers.address.edit'
        )->render();
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function getDeleteAddress($id, BaseHttpResponse $response)
    {
        $this->addressRepository->deleteBy([
            'id'          => $id,
            'customer_id' => auth('customer')->id(),
        ]);
        return $response->setNextUrl(route('customer.address'))
            ->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param int $id
     * @param AddressRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postEditAddress($id, AddressRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_default')) {
            $this->addressRepository->update([
                'is_default'  => 1,
                'customer_id' => auth('customer')->id(),
            ], ['is_default' => 0]);
        }

        $address = $this->addressRepository->createOrUpdate($request->input(), [
            'id'          => $id,
            'customer_id' => auth('customer')->id(),
        ]);

        return $response
            ->setData([
                'id'   => $address->id,
                'html' => view('plugins/ecommerce::orders.partials.address-item', compact('address'))
                    ->render(),
            ])
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function getPrintOrder($id, Request $request)
    {
        $order = $this->orderRepository->getFirstBy([
            'id'      => $id,
            'user_id' => auth('customer')->id(),
        ]);

        if (!$order || !$order->isInvoiceAvailable()) {
            abort(404);
        }

        if ($request->input('type') == 'print') {
            return OrderHelper::streamInvoice($order);
        }

        return OrderHelper::downloadInvoice($order);
    }

    /**
     * @param AvatarRequest $request
     * @param ThumbnailService $thumbnailService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postAvatar(AvatarRequest $request, ThumbnailService $thumbnailService, BaseHttpResponse $response)
    {
        try {
            $account = auth('customer')->user();

            $result = RvMedia::handleUpload($request->file('avatar_file'), 0, 'customers');

            if ($result['error']) {
                return $response->setError()->setMessage($result['message']);
            }

            $avatarData = json_decode($request->input('avatar_data'));

            $file = $result['data'];

            $thumbnailService
                ->setImage(RvMedia::getRealPath($file->url))
                ->setSize((int)$avatarData->width, (int)$avatarData->height)
                ->setCoordinates((int)$avatarData->x, (int)$avatarData->y)
                ->setDestinationPath(File::dirname($file->url))
                ->setFileName(File::name($file->url) . '.' . File::extension($file->url))
                ->save('crop');

            $account->avatar = $file->url;

            $this->customerRepository->createOrUpdate($account);

            return $response
                ->setMessage(trans('plugins/customer::dashboard.update_avatar_success'))
                ->setData(['url' => RvMedia::url($file->url)]);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }


    /**
     * @param int $orderId
     * @return Response
     */
    public function getReturnOrder($orderId)
    {
        SeoHelper::setTitle(__('Request Return Product(s) In Order :id', ['id' => get_order_code($orderId)]));

        $order = $this->orderRepository->getFirstBy(
            [
                'id'      => $orderId,
                'user_id' => auth('customer')->id(),
                'status'  => OrderStatusEnum::COMPLETED,
            ],
            ['ec_orders.*'],
            ['products']
        );

        if (!$order || !$order->canBeReturned()) {
            abort(404);
        }

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(
                __('Request Return Product(s) In Order :id', ['id' => get_order_code($orderId)]),
                route('customer.order_returns.request_view', $orderId)
            );

        return Theme::scope(
            'ecommerce.customers.order-returns.view',
            compact('order'),
            'plugins/ecommerce::themes.customers.order-returns.view'
        )->render();
    }

    /**
     * @param OrderReturnRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postReturnOrder(OrderReturnRequest $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->getFirstBy([
            'id'      => $request->input('order_id'),
            'user_id' => auth('customer')->id(),
        ]);

        if (!$order) {
            abort(404);
        }

        if (!$order->canBeReturned()) {
            return $response
                ->setError()
                ->withInput()
                ->setMessage(trans('plugins/ecommerce::order.return_error'));
        }

        $orderReturnData['reason'] = $request->input('reason');

        $orderReturnData['items'] = Arr::where($request->input(['return_items']), function ($value) {
            return isset($value['is_return']);
        });

        [$status, $data, $message] = OrderReturnHelper::returnOrder($order, $orderReturnData);

        if (!$status) {
            return $response
                ->setError()
                ->withInput()
                ->setMessage($message ?: trans('plugins/ecommerce::order.return_error'));
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'return_order',
            'description' => __(':customer has requested return product(s)', ['customer' => $order->address->name]),
            'order_id'    => $order->id,
        ]);

        return $response
            ->setMessage(trans('plugins/ecommerce::order.return_success'))
            ->setNextUrl(route('customer.order_returns.detail', ['id' => $data->id]));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getListReturnOrders(Request $request)
    {
        SeoHelper::setTitle(__('Order Return Requests'));

        $requests = $this->orderReturnRepository->advancedGet([
            'condition' => [
                'user_id' => auth('customer')->id(),
            ],
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => (int)$request->input('page'),
            ],
            'withCount' => ['items'],
            'order_by'  => ['created_at' => 'DESC'],
        ]);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Order Return Requests'), route('customer.order_returns'));

        return Theme::scope(
            'ecommerce.customers.order-returns.list',
            compact('requests'),
            'plugins/ecommerce::themes.customers.orders.returns.list'
        )->render();
    }

    /**
     * @param $id
     * @return Response
     */
    public function getDetailReturnOrder($id)
    {
        SeoHelper::setTitle(__('Order Return Requests'));

        $orderReturn = $this->orderReturnRepository->getFirstBy([
            'id'      => $id,
            'user_id' => auth('customer')->id(),
        ]);

        if (!$orderReturn) {
            abort(404);
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Order Return Requests'), route('customer.order_returns'))
            ->add(__('Order Return Requests :id', ['id' => $orderReturn->id]), route('customer.order_returns.detail', $orderReturn->id));

        return Theme::scope(
            'ecommerce.customers.order-returns.detail',
            compact('orderReturn'),
            'plugins/ecommerce::themes.customers.order-returns.detail'
        )->render();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getDownloads(Request $request)
    {
        if (!EcommerceHelper::isEnabledSupportDigitalProducts()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Downloads'));

        $orderProducts = $this->orderProductRepository->getModel()
            ->whereHas('order', function ($query) {
                $query->where([
                    'user_id'     => auth('customer')->id(),
                    'is_finished' => 1,
                ]);
            })
            ->whereHas('order.payment', function ($query) {
                $query->where(['status' => PaymentStatusEnum::COMPLETED]);
            })
            ->where('product_type', ProductTypeEnum::DIGITAL)
            ->orderBy('created_at', 'desc')
            ->with(['order', 'product'])
            ->paginate(10);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Downloads'), route('customer.downloads'));

        return Theme::scope(
            'ecommerce.customers.orders.downloads',
            compact('orderProducts'),
            'plugins/ecommerce::themes.customers.orders.downloads'
        )->render();
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return Response|BaseHttpResponse
     */
    public function getDownload($id, BaseHttpResponse $response)
    {
        if (!EcommerceHelper::isEnabledSupportDigitalProducts()) {
            abort(404);
        }

        $orderProduct = $this->orderProductRepository->getModel()
            ->where([
                'id'           => $id,
                'product_type' => ProductTypeEnum::DIGITAL
            ])
            ->whereHas('order', function ($query) {
                $query->where([
                    'user_id'     => auth('customer')->id(),
                    'is_finished' => 1,
                ]);
            })
            ->whereHas('order.payment', function ($query) {
                $query->where(['status' => PaymentStatusEnum::COMPLETED]);
            })
            ->with(['order', 'product'])
            ->first();

        if (!$orderProduct) {
            abort(404);
        }

        $zipName = 'digital-product-' . Str::slug($orderProduct->product_name) . Str::random(5) . '-' . Carbon::now()->format('Y-m-d-h-i-s') . '.zip';
        $fileName = RvMedia::getRealPath($zipName);
        $zip = new Zipper();
        $zip->make($fileName);
        $product = $orderProduct->product;
        $productFiles = $product->id ? $product->productFiles : $orderProduct->productFiles;

        if (!$productFiles->count()) {
            return $response->setError()->setMessage(__('Cannot found files'));
        }
        foreach ($productFiles as $file) {
            $filePath = RvMedia::getRealPath($file->url);
            if (!RvMedia::isUsingCloud()) {
                if (File::exists($filePath)) {
                    $zip->add($filePath);
                }
            } else {
                $zip->addString(
                    $file->file_name,
                    file_get_contents(str_replace('https://', 'http://', $filePath))
                );
            }
        }

        if (version_compare(phpversion(), '8.0') >= 0) {
            $zip = null;
        } else {
            $zip->close();
        }

        if (File::exists($fileName)) {
            $orderProduct->increment('times_downloaded');
            return response()->download($fileName)->deleteFileAfterSend();
        }

        return $response->setError()->setMessage(__('Cannot download files'));
    }
}
