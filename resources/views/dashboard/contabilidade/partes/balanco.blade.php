<tr>
    <td>{{ $descricao }}</td>
    <td class="text-center">{{ $codigo }}</td>
    <td class="text-right">
        @if ($saldos['credito'] > $saldos['debito'])
            @if (($saldos['credito'] - $saldos['debito']) == 0)
            -
            @else    
            {{ number_format($saldos['credito'] - $saldos['debito'], 2, ',', '.') }}
            @endif
        @else
            @if ($saldos['debito'] > $saldos['credito'])
                @if (($saldos['debito'] - $saldos['credito']) == 0)
                -
                @else
                {{ number_format($saldos['debito'] - $saldos['credito'], 2, ',', '.') }}
                @endif
            @else
            {{ number_format(0, 2, ',', '.') }}
            @endif
        @endif
    </td>
    <td class="text-right">0</td>
</tr>
