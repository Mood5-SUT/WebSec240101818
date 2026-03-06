<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>

    <nav class="navbar navbar-expand-sm bg-light">
        <div class="container-fluid">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link" href="./">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./even">Even Numbers</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./prime">Prime Numbers</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="./multable">Multiplication Table</a>
                </li>

            </ul>
        </div>
    </nav>


    <div class="card">
        <div class="card-header">Even Numbers</div>
        <div class="card-body">
            @foreach (range(1, 100) as $i)
            @if($i%2==0)
                <span class="badge bg-primary">{{$i}}</span>  
            @else
                <span class="badge bg-secondary">{{$i}}</span>  
            @endif
            @endforeach
        </div>
    </div>

</body>
</html>