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


  @php($j = 5)
  <div class="card m-4 col-sm-2">	
    <div class="card-header">{{$j}} Multiplication Table</div>
    <div class="card-body">
      <table>
        @foreach (range(1, 10) as $i)
        <tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>    
        @endforeach
      </table>
    </div>
  </div>


</body>
</html>