<?php

namespace VendorPatches202502\Illuminate\Contracts\Database\Eloquent;

use VendorPatches202502\Illuminate\Contracts\Database\Query\Builder as BaseContract;
/**
 * This interface is intentionally empty and exists to improve IDE support.
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
interface Builder extends BaseContract
{
}
