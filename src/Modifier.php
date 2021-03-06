<?php

namespace Whitecube\Price;

use Money\Money;

class Modifier implements PriceAmendable
{
    /**
     * The modifier types
     *
     * @var string
     */
    const TYPE_TAX = 'tax';
    const TYPE_DISCOUNT = 'discount';
    const TYPE_UNDEFINED = 'other';

    /**
     * The modifier's identifier
     *
     * @var null|string
     */
    protected $key;

    /**
     * The effective modifier type
     *
     * @var null|string
     */
    protected $type;

    /**
     * Whether this modifier should be executed
     * before or after the VAT value has been computed.
     *
     * @var null|bool
     */
    protected $pre;

    /**
     * The callable to apply on the price object
     *
     * @var mixed
     */
    protected $callback;

    /**
     * Create a new modifier instance
     *
     * @param mixed $callback
     * @param null|string $type
     * @param bool $pre
     * @return void
     */
    public function __construct($callback, $key = null, $type = null, $pre = false)
    {
        $this->callback = $callback;
        $this->key = $key;
        $this->type = $type;
        $this->pre = $pre;
    }

    /**
     * Return the modifier type (tax, discount, other, ...)
     *
     * @return string
     */
    public function type() : string
    {
        return $this->type ?: static::TYPE_UNDEFINED;
    }

    /**
     * Return the modifier's identification key
     *
     * @return null|string
     */
    public function key() : ?string
    {
        return $this->key;
    }

    /**
     * Whether the modifier should be applied before the
     * VAT value has been computed.
     *
     * @return bool
     */
    public function isBeforeVat() : bool
    {
        return $this->pre ? true : false;
    }

    /**
     * Apply the modifier on the given Money instance
     *
     * @param \Money\Money $value
     * @return \Money\Money
     */
    public function apply(Money $value) : ?Money
    {
        if(!is_callable($this->callback)) {
            return null;
        }

        return call_user_func($this->callback, $value);
    }
}