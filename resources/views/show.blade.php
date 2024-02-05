<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- xlsx library for Excel file generation -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.16.9/dist/xlsx.full.min.js"></script>

    <title>Home</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center my-3">API TABLE</h1>

        <!-- Search Box -->
        <form action="{{route('admin.search.api')}}" method="GET" class="mb-4">
            @csrf
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        @if (!empty($responseData))
            <button id="exportButton" class="btn btn-secondary btn-sm"
                style="background-color:#8a1e61;">Export Current Page (Excel)</button>
            <button id="exportAllButton" class="btn btn-secondary btn-sm">Export All Data (Excel)</button>
            <table id="apiTable" class="table table-bordered my-5">
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
                            <td>{{ ($responseData->currentPage() - 1) * $responseData->perPage() + $index + 1 }}</td>
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
            <div class="d-flex justify-content-center my-4">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item {{ $responseData->currentPage() == 1 ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $responseData->url(1) }}" tabindex="-1"
                                aria-disabled="true">Previous</a>
                        </li>

                        @php
                            $totalPages = $responseData->lastPage();
                            $currentPage = $responseData->currentPage();
                            $maxPagesToShow = 10; // Adjust this value to control the number of pages shown
                            $halfMax = floor($maxPagesToShow / 2);
                            $startPage = max(1, $currentPage - $halfMax);
                            $endPage = min($totalPages, $currentPage + $halfMax);

                            if ($startPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="#">...</a></li>';
                            }
                        @endphp

                        @for ($i = $startPage; $i <= $endPage; $i++)
                            <li class="page-item {{ $responseData->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $responseData->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($endPage < $totalPages)
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                        @endif

                        <li class="page-item {{ $responseData->currentPage() == $totalPages ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $responseData->url($totalPages) }}">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <script>
    document.addEventListener('DOMContentLoaded', function () {
        const exportButton = document.getElementById('exportButton');
        const exportAllButton = document.getElementById('exportAllButton');
        const apiTable = document.getElementById('apiTable');

        exportButton.addEventListener('click', function () {
            // Convert current page's table data to Excel file
            const ws = XLSX.utils.table_to_sheet(apiTable);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

            // Save the Excel file
            XLSX.writeFile(wb, 'api_data.xlsx');
        });

        exportAllButton.addEventListener('click', function () {
            // Fetch all data from the server using a simple GET request
            fetch('/admin/all-api')
                .then(response => response.json())
                .then(data => {
                    // Convert all data to Excel file
                    const ws = XLSX.utils.json_to_sheet(data);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

                    // Save the Excel file
                    XLSX.writeFile(wb, 'all_api_data.xlsx');
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>



        @else
            <p>No data available.</p>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>
