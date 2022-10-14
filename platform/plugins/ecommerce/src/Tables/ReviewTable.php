<?php

namespace Botble\Ecommerce\Tables;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use RvMedia;
use Yajra\DataTables\DataTables;

class ReviewTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * ReviewTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ReviewInterface $reviewRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ReviewInterface $reviewRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $reviewRepository;

        if (!Auth::user()->hasAnyPermission(['review.edit', 'review.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('product_id', function ($item) {
                if (!empty($item->product)) {
                    return Html::link(
                        $item->product->url,
                        BaseHelper::clean($item->product_name),
                        ['target' => '_blank']
                    );
                }
                return null;
            })
            ->editColumn('customer_id', function ($item) {
                return Html::link(route('customers.edit', $item->user->id), BaseHelper::clean($item->user->name))->toHtml();
            })
            ->editColumn('star', function ($item) {
                return view('plugins/ecommerce::reviews.partials.rating', ['star' => $item->star])->render();
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('status', function ($item) {
                return BaseHelper::clean($item->status->toHtml());
            })
            ->editColumn('images', function ($item) {
                if (!is_array($item->images)) {
                    return '&mdash;';
                }

                $count = count($item->images);

                if ($count == 0) {
                    return '&mdash;';
                }

                $galleryID = 'images-group-' . $item->id;

                $html = Html::image(
                    RvMedia::getImageUrl($item->images[0], 'thumb'),
                    RvMedia::getImageUrl($item->images[0]),
                    [
                        'width'         => 60,
                        'class'         => 'fancybox m-1 rounded-top rounded-end rounded-bottom rounded-start border d-inline-block',
                        'href'          => RvMedia::getImageUrl($item->images[0]),
                        'data-fancybox' => $galleryID,
                    ]
                );

                if (isset($item->images[1])) {
                    if ($count == 2) {
                        $html .= Html::image(
                            RvMedia::getImageUrl($item->images[1], 'thumb'),
                            RvMedia::getImageUrl($item->images[1]),
                            [
                                'width'         => 60,
                                'class'         => 'fancybox m-1 rounded-top rounded-end rounded-bottom rounded-start border d-inline-block',
                                'href'          => RvMedia::getImageUrl($item->images[1]),
                                'data-fancybox' => $galleryID,
                            ]
                        );
                    } elseif ($count > 2) {
                        $html .= Html::tag('a', Html::image(
                            RvMedia::getImageUrl($item->images[1], 'thumb'),
                            RvMedia::getImageUrl($item->images[1]),
                            [
                                    'width' => 60,
                                    'class' => 'm-1 rounded-top rounded-end rounded-bottom rounded-start border',
                                    'src'   => RvMedia::getImageUrl($item->images[1]),
                                ]
                        )->toHtml() . Html::tag('span', '+' . ($count - 2))->toHtml(), [
                            'class'         => 'fancybox more-review-images',
                            'href'          => RvMedia::getImageUrl($item->images[1]),
                            'data-fancybox' => $galleryID,
                        ]);
                    }
                }

                if ($count > 2) {
                    foreach ($item->images as $index => $image) {
                        if ($index > 1) {
                            $html .= Html::image(
                                RvMedia::getImageUrl($item->images[$index], 'thumb'),
                                RvMedia::getImageUrl($item->images[$index]),
                                [
                                    'width'         => 60,
                                    'class'         => 'fancybox d-none',
                                    'href'          => RvMedia::getImageUrl($item->images[$index]),
                                    'data-fancybox' => $galleryID,
                                ]
                            );
                        }
                    }
                }

                return $html;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return view('plugins/ecommerce::reviews.partials.actions', compact('item'))->render();
            })
            ->filter(function ($query) {
                $keyword = $this->request->input('search.value');
                if ($keyword) {
                    return $query->where(function ($query) use ($keyword) {
                        return $query
                            ->whereHas('product', function ($subQuery) use ($keyword) {
                                return $subQuery->where('ec_products.name', 'LIKE', '%' . $keyword . '%');
                            })
                            ->orWhereHas('user', function ($subQuery) use ($keyword) {
                                return $subQuery->where('ec_customers.name', 'LIKE', '%' . $keyword . '%');
                            });
                    });
                }

                return $query;
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'star',
                'comment',
                'product_id',
                'customer_id',
                'status',
                'created_at',
                'images',
            ])
            ->with(['user', 'product']);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'          => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-start',
            ],
            'product_id'  => [
                'title' => trans('plugins/ecommerce::review.product'),
                'class' => 'text-start',
            ],
            'customer_id' => [
                'title' => trans('plugins/ecommerce::review.user'),
                'class' => 'text-start',
            ],
            'star'        => [
                'title' => trans('plugins/ecommerce::review.star'),
                'class' => 'text-center',
            ],
            'comment'     => [
                'title' => trans('plugins/ecommerce::review.comment'),
                'class' => 'text-start',
            ],
            'images'      => [
                'title'      => trans('plugins/ecommerce::review.images'),
                'width'      => '150px',
                'class'      => 'text-start',
                'searchable' => false,
                'orderable'  => false,
            ],
            'status'      => [
                'title' => trans('plugins/ecommerce::review.status'),
                'class' => 'text-center',
            ],
            'created_at'  => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '70px',
                'class' => 'text-start',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getOperationsHeading()
    {
        return [
            'operations' => [
                'title'      => trans('core/base::tables.operations'),
                'width'      => '50px',
                'class'      => 'text-end',
                'orderable'  => false,
                'searchable' => false,
                'exportable' => false,
                'printable'  => false,
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('reviews.deletes'), 'review.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function htmlDrawCallbackFunction(): ?string
    {
        return parent::htmlDrawCallbackFunction() . 'if (jQuery().fancybox) {
            $(".dataTables_wrapper .fancybox").fancybox({
                openEffect: "none",
                closeEffect: "none",
                overlayShow: true,
                overlayOpacity: 0.7,
                helpers: {
                    media: {}
                },
            });
        }';
    }

    /**
     * {@inheritDoc}
     */
    public function renderTable($data = [], $mergeData = [])
    {
        if ($this->query()->count() === 0 &&
            !$this->request()->wantsJson() &&
            $this->request()->input('filter_table_id') !== $this->getOption('id') && !$this->request()->ajax()
        ) {
            return view('plugins/ecommerce::reviews.intro');
        }

        return parent::renderTable($data, $mergeData);
    }
}
