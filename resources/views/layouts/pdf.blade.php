<!DOCTYPE html>
<html lang="pdf">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }} | {{ $descricao }}</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body{
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }
        h1{
            font-size: 13pt;
            margin-bottom: 10px;
        }
        table{
            width: 100%;
            border-spacing: 0;
            margin-top: 20px;
            border: 1px solid #dadada;
        }

        thead{
            /* background-color: #dadada; */
            text-align: left;
            border-bottom: 1px dashed #919191;
            border-right: 1px dashed #919191;
            border-left: 1px dashed #919191;
        }

        thead > tr > th{
            padding: 5px;
            text-align: left;
        }
        
        thead > tr > th{
            border-bottom: 1px dashed #919191;
            border-right: 1px dashed #919191;
            border-left: 1px dashed #919191;
        }

        tbody > tr > td{
            padding: 5px;
            border-bottom: 1px dashed #919191;
            border-right: 1px dashed #919191;
            border-left: 1px dashed #919191;
            /* border 1px tailwind */
        }
        footer{
            position: fixed;
            bottom: 0;
        }

        .text-center{
            text-align: center;
        }

        .text-start{
            text-align: left;
        }

        .text-end{
            text-align: right;
        }
    </style>
</head>
<body>
    @yield('pdf-content')
</body>
</html>