<!doctype html>
<html lang="en">

<head>
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Home</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center my-3">API TABLE</h1>

        <!-- Search Box -->
        <form action="{{ url()->current() }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
        @if (!empty($responseData))
            <table class="table table-bordered my-5">
                <!-- Table Headers -->
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">API</th>
                        <th scope="col">Description</th>
                        <th scope="col">Auth</th>
                        <th scope="col">HTTPS</th>
                        <th scope="col">Cors</th>
                        <th scope="col">Link</th>
                        <th scope="col">Category</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table Rows -->
                    @foreach($responseData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->API }}</td>
                            <td>{{ $data->Description }}</td>
                            <td>{{ $data->Auth }}</td>
                            <td>{{ $data->HTTPS }}</td>
                            <td>{{ $data->Cors }}</td>
                            <td>{{ $data->Link }}</td>
                            <td>{{ $data->Category }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available.</p>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>


        <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the search input and table rows
        const searchInput = document.querySelector('input[name="search"]');
        const tableRows = document.querySelectorAll('.table tbody tr');

        // Add an event listener to the search input
        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.trim().toLowerCase();

            // Loop through each table row
            tableRows.forEach(function (row) {
                const rowData = row.textContent.toLowerCase();

                // If the row contains the search term, display it; otherwise, hide it
                if (rowData.includes(searchTerm)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

</body>

</html>
