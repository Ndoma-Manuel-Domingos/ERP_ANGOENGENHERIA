<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
    @php
        return redirect()->route('pronto-venda');
    @endphp 

    @if ($dados->configuracao_empressao->metodo_empressao == "0")
        <script>
            print();
        </script>
        
    @endif
</body>
</html>