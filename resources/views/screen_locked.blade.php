<!-- resources/views/screen_locked.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Bloqueada</title>
    <style>
        /* Estilos simples para centralizar a tela de bloqueio */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .lock-screen {
            text-align: center;
            background-color: #fff;
            padding: 150px 50px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .lock-screen input {
            padding: 15px 15px;
            margin-top: 10px;
            font-size: 20px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 50px;
        }
        .lock-screen button {
            padding: 10px;
            margin-top: 40px;
            font-size: 30px;
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .lock-screen button:hover {
            background-color: #0056b3;
        }
        h2{
            font-size: 30px;
        }
        p{
            color: green;
            font-size: 30px;
        }
    </style>
</head>
<body>
    <div class="lock-screen">
        <h2>Tela Bloqueada</h2>
        <p style="">Tempo restante: <span id="timer"></span></p>
        <form action="{{ route('screen.unlock') }}" method="POST">
            @csrf
            <input type="text" name="pin" placeholder="Insira o PIN" required>
            @if($errors->has('pin'))
                <div style="color: red;margin-top: 15px;">{{ $errors->first('pin') }}</div>
            @endif
            <button type="submit">Desbloquear</button>
        </form>
    </div>

    <script>
        // Contagem regressiva de 24 horas
        let remainingTime = {{ $remainingTime }};
        const timerElement = document.getElementById('timer');

        function updateTimer() {
            const hours = Math.floor(remainingTime / 3600);
            const minutes = Math.floor((remainingTime % 3600) / 60);
            const seconds = remainingTime % 60;

            timerElement.textContent = `${hours}h ${minutes}m ${seconds}s`;

            if (remainingTime <= 0) {
                window.location.href = "{{ route('logout') }}"; // Redireciona para logout quando o tempo acabar
            } else {
                remainingTime--;
            }
        }

        setInterval(updateTimer, 1000);
        
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            // alert('PIN enviado, verificando...');
            this.submit();
        });
        
    </script>
</body>
</html>
