<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Inter';
            src: url('{{ public_path('fonts/Inter/Inter-Regular.ttf') }}') format('truetype');
            font-weight: 400;
        }

        @font-face {
            font-family: 'Inter';
            src: url('{{ public_path('fonts/Inter/Inter-Bold.ttf') }}') format('truetype');
            font-weight: 700;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse; /* remove double borders */
            width: 100%;
        }

        th, td {
            border: 1px solid #ccc;       /* table cell borders */
            padding: 8px 12px;            /* space inside cells */
            text-align: left;              /* left-align text */
            vertical-align: top;           /* align text to top */
        }

        th {
            background-color: #f5f5f5;    /* light gray header */
            font-weight: 700;              /* bold headers */
        }

        tr:nth-child(even) td {
            background-color: #fafafa;    /* striped rows */
        }
    </style>

</head>
    <body>
        <div class="flex items-center mt-3" style="margin: 0 0 0.75rem 0">
            <span class="text-xs font-bold leading-none uppercase">{{ $title }}</span>
        </div>

        @if($type == 'list')
        @php
            $columns = $items[0] ?? [];
            $rows = array_slice($items, 1);
        @endphp
            <table>
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th style="text-align: left;">{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            @foreach ($columns as $key)
                                <td>
                                    @if(is_array($row[$key]))
                                        @foreach($row[$key] as $subItem)
                                            <div>{{ $subItem ?? '' }}</div>
                                        @endforeach
                                    @else
                                        {{ $row[$key] ?? '' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif($type == 'detail')
            @php
                $transposed = $items;
            @endphp

            <table>
                <tbody>
                    @foreach ($transposed as $row)
                        <tr>
                            <th style="padding-right: 15px; text-align: left;">{{ $row[0] }}</th>
                            <td>{{ is_array($row[1]) ? json_encode($row[1]) : $row[1] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </body>

</html>
