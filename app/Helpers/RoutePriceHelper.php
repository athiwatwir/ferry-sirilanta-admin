<?php

namespace App\Helpers;

use App\Models\AgentSubRoute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RoutePriceHelper
{
    public static function agentSubRouteTicketPrice($agentSubRouteId)
    {
        $item = AgentSubRoute::whereId($agentSubRouteId)->with(['subRoute'])->first();
        $regular = 0;
        $child = 0;
        $infant = 0;
        $cost = 0;

        if (!empty($item)) {
            $costRegular = RoutePriceHelper::calculateCostPrice($item->subRoute->price, $item->discount_type, $item->discount_regular_price);
            $costChild = RoutePriceHelper::calculateCostPrice($item->subRoute->child_price, $item->discount_type, $item->discount_child_price);
            $costInfant = RoutePriceHelper::calculateCostPrice($item->subRoute->infant_price, $item->discount_type, $item->discount_infant_price);

            $regular = $costRegular + $item->price;
            $child = $costRegular + $item->child_price;
            $infant = $costRegular + $item->infant_price;
        }

        return [
            'regular_subtotal' => $item->subRoute->price,
            'child_subtotal' => $item->subRoute->child_price,
            'infant_subtotal' => $item->subRoute->infant_price,

            'regular_discount' => $item->subRoute->price - $costRegular,
            'child_discount' => $item->subRoute->child_price - $costChild,
            'infant_discount' => $item->subRoute->infant_price - $costInfant,
            'regular' => $regular,
            'child' => $child,
            'infant' => $infant
        ];
    }


    public static function calculateCostPrice($price = 0, $discountType = 'percent', $discountValue = 0)
    {
        if (empty($discountValue)) {
            return $price;
        }

        if ($discountType == 'percent') {
            $price = $price - ($price * ($discountValue / 100));
        } else {
            $price = $price - $discountValue;
        }

        return $price;
    }
}
