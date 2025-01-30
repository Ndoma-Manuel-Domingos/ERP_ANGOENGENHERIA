
@foreach ($dados as $conta)
    @foreach ($conta->subcontas as $item)
        <tr>
            <td style="padding-left: 80px">{{ $item->numero }} - {{ $item->nome }}</td>
            <td class="text-right">
                @foreach ($item->movimentos as $mov)
                    @if ($mov->credito > $mov->debito)
                        @if (($mov->credito - $mov->debito) == 0)
                        -
                        @else    
                        {{ number_format($mov->credito - $mov->debito, 2, ',', '.') }}
                        @endif
                    @else
                        @if ($mov->debito > $mov->credito)
                            @if (($mov->debito - $mov->credito) == 0)
                            -
                            @else
                            {{ number_format($mov->debito - $mov->credito, 2, ',', '.') }}
                            @endif
                        @else
                        {{ number_format(0, 2, ',', '.') }}
                        @endif
                    @endif
                @endforeach
            </td>
            <td class="text-right">0</td>
        </tr>
    @endforeach
@endforeach
