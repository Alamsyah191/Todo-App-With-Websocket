@extends('layouts.app')

@section('content')
    @push('table')
        <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>
    @endpush
    <div class="container">
        <div class="card">
            <div id="myGrid" class="ag-theme-quartz" style="height: 500px;"></div>
        </div>
    </div>

    @push('script')
        <script>
            // Definisikan data dan kolom untuk AG Grid
            const rowData = [
                { make: "Tesla", model: "Model Y", price: 64950, electric: true },
                { make: "Ford", model: "F-Series", price: 33850, electric: false },
                { make: "Toyota", model: "Corolla", price: 29600, electric: false }
            ];

            const columnDefs = [
                { field: "project", headerName: "Project" },
                { field: "desc", headerName: "Desc" },
                { field: "user tequest", headerName: "User Request" },
                { field: "status", headerName: "Status" },
                { field: "requested at", headerName: "Requested At" },
                { field: "deadline", headerName: "Deadline" },
            ];

            // Definisikan opsi untuk AG Grid
            const gridOptions = {
                columnDefs: columnDefs,
                rowData: rowData
            };

            // Inisialisasi AG Grid setelah DOM siap
            document.addEventListener('DOMContentLoaded', function () {
                const myGridElement = document.querySelector('#myGrid');
                agGrid.createGrid(myGridElement, gridOptions);
            });
        </script>
    @endpush
@endsection
