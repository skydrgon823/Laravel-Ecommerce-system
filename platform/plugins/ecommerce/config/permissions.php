<?php

return [
    [
        'name' => 'E-commerce',
        'flag' => 'plugins.ecommerce',
    ],

    [
        'name'        => 'Settings',
        'flag'        => 'ecommerce.settings',
        'parent_flag' => 'plugins.ecommerce',
    ],

    [
        'name'        => 'Reports',
        'flag'        => 'ecommerce.report.index',
        'parent_flag' => 'plugins.ecommerce',
    ],

    /**
     * Products
     */
    [
        'name'        => 'Products',
        'flag'        => 'products.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'products.create',
        'parent_flag' => 'products.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'products.edit',
        'parent_flag' => 'products.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'products.destroy',
        'parent_flag' => 'products.index',
    ],
    /**
     * Categories
     */
    [
        'name'        => 'Product categories',
        'flag'        => 'product-categories.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'product-categories.create',
        'parent_flag' => 'product-categories.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'product-categories.edit',
        'parent_flag' => 'product-categories.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'product-categories.destroy',
        'parent_flag' => 'product-categories.index',
    ],

    [
        'name'        => 'Product tags',
        'flag'        => 'product-tag.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'product-tag.create',
        'parent_flag' => 'product-tag.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'product-tag.edit',
        'parent_flag' => 'product-tag.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'product-tag.destroy',
        'parent_flag' => 'product-tag.index',
    ],


    /**
     * Brands
     */
    [
        'name'        => 'Brands',
        'flag'        => 'brands.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'brands.create',
        'parent_flag' => 'brands.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'brands.edit',
        'parent_flag' => 'brands.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'brands.destroy',
        'parent_flag' => 'brands.index',
    ],
    /**
     * Product collections
     */
    [
        'name'        => 'Product collections',
        'flag'        => 'product-collections.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'product-collections.create',
        'parent_flag' => 'product-collections.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'product-collections.edit',
        'parent_flag' => 'product-collections.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'product-collections.destroy',
        'parent_flag' => 'product-collections.index',
    ],

    /**
     * Product attribute sets
     */
    [
        'name'        => 'Product Attributes Sets',
        'flag'        => 'product-attribute-sets.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'product-attribute-sets.create',
        'parent_flag' => 'product-attribute-sets.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'product-attribute-sets.edit',
        'parent_flag' => 'product-attribute-sets.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'product-attribute-sets.destroy',
        'parent_flag' => 'product-attribute-sets.index',
    ],
    /**
     * Product attributes
     */
    [
        'name'        => 'Product Attributes',
        'flag'        => 'product-attributes.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'product-attributes.create',
        'parent_flag' => 'product-attributes.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'product-attributes.edit',
        'parent_flag' => 'product-attributes.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'product-attributes.destroy',
        'parent_flag' => 'product-attributes.index',
    ],
    [
        'name'        => 'Taxes',
        'flag'        => 'tax.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'tax.create',
        'parent_flag' => 'tax.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'tax.edit',
        'parent_flag' => 'tax.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'tax.destroy',
        'parent_flag' => 'tax.index',
    ],
    [
        'name'        => 'Reviews',
        'flag'        => 'reviews.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'reviews.destroy',
        'parent_flag' => 'reviews.index',
    ],

    [
        'name'        => 'Shipping',
        'flag'        => 'shipping_methods.index',
        'parent_flag' => 'plugins.ecommerce',
    ],

    [
        'name'        => 'Shipments',
        'flag'        => 'ecommerce.shipments.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'ecommerce.shipments.create',
        'parent_flag' => 'ecommerce.shipments.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'ecommerce.shipments.edit',
        'parent_flag' => 'ecommerce.shipments.index',
    ],

    [
        'name'        => 'Orders',
        'flag'        => 'orders.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'orders.create',
        'parent_flag' => 'orders.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'orders.edit',
        'parent_flag' => 'orders.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'orders.destroy',
        'parent_flag' => 'orders.index',
    ],
    [
        'name'        => 'Discounts',
        'flag'        => 'discounts.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'discounts.create',
        'parent_flag' => 'discounts.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'discounts.edit',
        'parent_flag' => 'discounts.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'discounts.destroy',
        'parent_flag' => 'discounts.index',
    ],
    [
        'name'        => 'Customers',
        'flag'        => 'customers.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'customers.create',
        'parent_flag' => 'customers.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'customers.edit',
        'parent_flag' => 'customers.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'customers.destroy',
        'parent_flag' => 'customers.index',
    ],

    [
        'name'        => 'Flash sales',
        'flag'        => 'flash-sale.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'flash-sale.create',
        'parent_flag' => 'flash-sale.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'flash-sale.edit',
        'parent_flag' => 'flash-sale.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'flash-sale.destroy',
        'parent_flag' => 'flash-sale.index',
    ],

    [
        'name'        => 'Product labels',
        'flag'        => 'product-label.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'product-label.create',
        'parent_flag' => 'product-label.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'product-label.edit',
        'parent_flag' => 'product-label.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'product-label.destroy',
        'parent_flag' => 'product-label.index',
    ],

    [
        'name'        => 'Bulk Import Products',
        'flag'        => 'ecommerce.bulk-import.index',
        'parent_flag' => 'plugins.ecommerce',
    ],

    [
        'name'        => 'Export Products',
        'flag'        => 'ecommerce.export.products.index',
        'parent_flag' => 'plugins.ecommerce',
    ],

    [
        'name'        => 'Order Returns',
        'flag'        => 'order_returns.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'order_returns.edit',
        'parent_flag' => 'order_returns.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'order_returns.destroy',
        'parent_flag' => 'order_returns.index',
    ],
];
