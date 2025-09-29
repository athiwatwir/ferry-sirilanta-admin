@props([
'priceStrategy',
'subRoute' => null,
'isshowtool' => true,
])

<table class="table">
    <thead>
        <tr>
            <th>Condition</th>
            <th>{{ $calculateTypes[$priceStrategy->calculate_type] }}</th>
            <th>Regular +{{ $calculateMethods[$priceStrategy->method] }}</th>
            <th>Child +{{ $calculateMethods[$priceStrategy->method] }}</th>
            <th>Infant +{{ $calculateMethods[$priceStrategy->method] }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($priceStrategy->lines as $line)
        @php
        $isPercent = $priceStrategy->method === 'percent';
        $price = $line->price;
        $childPrice = $line->child_price;
        $infantPrice = $line->infant_price;
        @endphp
        <tr>
            <td>{{ $line->condition }}</td>
            <td>{{ $line->unit }}</td>

            {{-- Regular --}}
            <td>
                {{ $price }}{{ $isPercent ? '%' : '' }}
                @if ($subRoute)
                <small class="text-secondary">/
                    <x-label-price :price="$subRoute->price + ($isPercent ? ($subRoute->price * $price / 100) : $price)" />
                </small>
                @endif
            </td>

            {{-- Child --}}
            <td>
                {{ $childPrice }}{{ $isPercent ? '%' : '' }}
                @if ($subRoute)
                <small class="text-secondary">/
                    <x-label-price :price="$subRoute->child_price + ($isPercent ? ($subRoute->child_price * $childPrice / 100) : $childPrice)" />
                </small>
                @endif
            </td>

            {{-- Infant --}}
            <td>
                {{ $infantPrice }}{{ $isPercent ? '%' : '' }}
                @if ($subRoute)
                <small class="text-secondary">/
                    <x-label-price :price="$subRoute->infant_price + ($isPercent ? ($subRoute->infant_price * $infantPrice / 100) : $infantPrice)" />
                </small>
                @endif
            </td>

            @if ($isshowtool)
            <td class="text-end">
                <div class="d-inline-block text-nowrap">
                    <x-button.edit /> |
                    <x-button.delete url="{{ route('priceStrategyLine.destroy', ['priceStrategyLine' => $line->id]) }}" />
                </div>
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
