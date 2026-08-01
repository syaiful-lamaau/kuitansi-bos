<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Cetak Kukuitansi')</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background-color: #fff;
            line-height: 1.4;
        }

        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
        }

        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }

            .a4-wrapper {
                width: 100%;
                box-shadow: none;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        @media screen {
            body {
                background-color: #e5e7eb;
                display: flex;
                justify-content: center;
                padding: 2rem;
            }

            .a4-wrapper {
                background-color: white;
                width: 210mm;
                min-height: 297mm;
                padding: 15mm 20mm;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            }
        }
    </style>
</head>

<body>
    <div class="a4-wrapper">
        @yield('content')
    </div>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 500);
        });
    </script>
</body>

</html>