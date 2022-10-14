<?php

namespace Botble\Ecommerce\Cart;

use Botble\Ecommerce\Cart\Contracts\Buyable;
use Carbon\Carbon;
use EcommerceHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class CartItem implements Arrayable, Jsonable
{
    /**
     * The rowID of the cart item.
     *
     * @var string
     */
    public $rowId;

    /**
     * The ID of the cart item.
     *
     * @var int|string
     */
    public $id;

    /**
     * The quantity for this cart item.
     *
     * @var int|float
     */
    public $qty;

    /**
     * The name of the cart item.
     *
     * @var string
     */
    public $name;

    /**
     * The price without TAX of the cart item.
     *
     * @var float
     */
    public $price;

    /**
     * The options for this cart item.
     *
     * @var array
     */
    public $options;

    /**
     * The FQN of the associated model.
     *
     * @var string|null
     */
    protected $associatedModel = null;

    /**
     * The tax rate for the cart item.
     *
     * @var int|float
     */
    protected $taxRate = 0;

    /**
     * CartItem constructor.
     *
     * @param int|string $id
     * @param string $name
     * @param float $price
     * @param array $options
     */
    public function __construct($id, $name, $price, array $options = [])
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Please supply a valid identifier.');
        }
        if (empty($name)) {
            throw new InvalidArgumentException('Please supply a valid name.');
        }
        if (strlen($price) < 0 || !is_numeric($price)) {
            throw new InvalidArgumentException('Please supply a valid price.');
        }

        $this->id = $id;
        $this->name = $name;
        $this->price = floatval($price);
        $this->options = new CartItemOptions($options);
        $this->rowId = $this->generateRowId($id, $options);
        $this->created_at = Carbon::now();
        $this->updated_at = Carbon::now();
    }

    /**
     * Returns the formatted price without TAX.
     *
     * @return string
     */
    public function price()
    {
        return format_price($this->price);
    }

    /**
     * Returns the formatted price with TAX.
     *
     * @return string
     */
    public function priceTax()
    {
        return format_price($this->priceTax);
    }

    /**
     * Returns the formatted subtotal.
     * Subtotal is price for whole CartItem without TAX
     *
     * @return string
     */
    public function subtotal()
    {
        return format_price($this->subtotal);
    }

    /**
     * Returns the formatted total.
     * Total is price for whole CartItem with TAX
     *
     * @return string
     */
    public function total()
    {
        return format_price($this->total);
    }

    /**
     * Returns the formatted tax.
     *
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return string
     */
    public function tax()
    {
        return format_price($this->tax);
    }

    /**
     * Returns the formatted tax.
     *
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return string
     */
    public function taxTotal()
    {
        return format_price($this->taxTotal);
    }

    /**
     * Set the quantity for this cart item.
     *
     * @param int|float $qty
     */
    public function setQuantity($qty)
    {
        if (empty($qty) || !is_numeric($qty)) {
            throw new InvalidArgumentException('Please supply a valid quantity.');
        }

        $this->qty = $qty;
    }

    /**
     * Update the cart item from a Buyable.
     *
     * @param Buyable $item
     * @return void
     */
    public function updateFromBuyable(Buyable $item)
    {
        $this->id = $item->getBuyableIdentifier($this->options);
        $this->name = $item->getBuyableDescription($this->options);
        $this->price = $item->getBuyablePrice($this->options);
        $this->priceTax = $this->price + $this->tax;
    }

    /**
     * Update the cart item from an array.
     *
     * @param array $attributes
     * @return void
     */
    public function updateFromArray(array $attributes)
    {
        $this->id = Arr::get($attributes, 'id', $this->id);
        $this->qty = Arr::get($attributes, 'qty', $this->qty);
        $this->name = Arr::get($attributes, 'name', $this->name);
        $this->price = Arr::get($attributes, 'price', $this->price);
        $this->priceTax = $this->price + $this->tax;
        $this->options = new CartItemOptions(Arr::get($attributes, 'options', $this->options));

        $this->rowId = $this->generateRowId($this->id, $this->options->all());
    }

    /**
     * Associate the cart item with the given model.
     *
     * @param mixed $model
     * @return CartItem
     */
    public function associate($model)
    {
        $this->associatedModel = is_string($model) ? $model : get_class($model);

        return $this;
    }

    /**
     * Set the tax rate.
     *
     * @param int|float $taxRate
     * @return CartItem
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Get an attribute from the cart item or get the associated model.
     *
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        if (property_exists($this, $attribute)) {
            return $this->{$attribute};
        }

        if ($attribute === 'priceTax') {
            if (!EcommerceHelper::isTaxEnabled()) {
                return 0;
            }

            return $this->price + $this->tax;
        }

        if ($attribute === 'subtotal') {
            return $this->qty * $this->price;
        }

        if ($attribute === 'total') {
            return $this->qty * ($this->priceTax);
        }

        if ($attribute === 'tax') {
            if (!EcommerceHelper::isTaxEnabled()) {
                return 0;
            }

            return $this->price * ($this->taxRate / 100);
        }

        if ($attribute === 'taxTotal') {
            if (!EcommerceHelper::isTaxEnabled()) {
                return 0;
            }

            return $this->tax * $this->qty;
        }

        if ($attribute === 'model') {
            return with(new $this->associatedModel())->find($this->id);
        }

        return null;
    }

    /**
     * Create a new instance from a Buyable.
     *
     * @param Buyable $item
     * @param array $options
     * @return CartItem
     */
    public static function fromBuyable(Buyable $item, array $options = [])
    {
        return new self(
            $item->getBuyableIdentifier($options),
            $item->getBuyableDescription($options),
            $item->getBuyablePrice($options),
            $options
        );
    }

    /**
     * Create a new instance from the given array.
     *
     * @param array $attributes
     * @return CartItem
     */
    public static function fromArray(array $attributes)
    {
        $options = Arr::get($attributes, 'options', []);

        return new self($attributes['id'], $attributes['name'], $attributes['price'], $options);
    }

    /**
     * Create a new instance from the given attributes.
     *
     * @param int|string $id
     * @param string $name
     * @param float $price
     * @param array $options
     * @return CartItem
     */
    public static function fromAttributes($id, $name, $price, array $options = [])
    {
        return new self($id, $name, $price, $options);
    }

    /**
     * Generate a unique id for the cart item.
     *
     * @param string $id
     * @param array $options
     * @return string
     */
    protected function generateRowId($id, array $options)
    {
        ksort($options);

        return md5($id . serialize($options));
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'rowId'      => $this->rowId,
            'id'         => $this->id,
            'name'       => $this->name,
            'qty'        => $this->qty,
            'price'      => $this->price,
            'options'    => $this->options->toArray(),
            'tax'        => $this->tax,
            'subtotal'   => $this->subtotal,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
