<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Beers</title>
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/simple-sidebar.css" rel="stylesheet">
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">Beers</div>
    </div>
    <!-- /#sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="col-md-12 mt-3">
                <form>
                    <div class="form-row align-items-center search-form">
                        <div class="col-sm-3 my-1">
                            <label for="inlineFormInputName">Name</label>
                            <input type="text" class="form-control search_name" id="inlineFormInputName"
                                   placeholder="Bud">
                        </div>
                        <div class="col-sm-3 my-1">
                            <label for="inlineFormInputABV">ABV</label>
                            <input type="text" class="form-control search_abv" id="inlineFormInputABV" placeholder="6">
                        </div>
                    </div>
                </form>
            </div>
            <hr/>
            <div class="col-md-12 mt-5">
                <span class="loader text-primary" style="display: none">
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      Loading...
                </span>
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>tagline</th>
                        <th>abv</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->
</div>
<!-- /#wrapper -->
<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


<script type="text/javascript">


    $(document).ready(function () {
        getData($('.search_name').val(), $('.search_abv').val());
        $('#inlineFormInputName').keyup(function () {
            getData($('.search_name').val(), $('.search_abv').val());
        })
        $('#inlineFormInputABV').keyup(function () {
            getData($('.search_name').val(), $('.search_abv').val());
        })
    });

    function getData(searchName, searchAbv) {

        var Url = '/api';

        if (searchName.length > 0 && searchAbv.length == 0) {
            searchName = searchName.replace(" ", "_");
            Url = '/api/' + searchName;
        } else {
            searchName = searchName.replace(" ", "_");
            Url = '/api/' + searchName + '/' + searchAbv;
        }

        if (searchName.length > 0) {
            $.ajax({
                url: Url,
                data: "{ 'name': ''}",
                dataType: "json",
                type: "GET",
                contentType: "application/json; charset=utf-8",
                success: function (response) {
                    BindDataTable(response.Suggestions);
                },
                beforeSend: function () {
                    $('.loader').toggle();
                    $('#example_wrapper').hide();
                },
                complete: function () {
                    $('.loader').toggle();
                    $('#example_wrapper').show();
                }

            })
        } else {
            BindDataTable([]);
        }
    }

    function BindDataTable(response) {

        $('#example').DataTable().destroy();

        $("#example").DataTable({
            "order": [[4, "asc"]],
            "aaData": response,
            "aoColumns": [
                {"mData": "id"},
                {"mData": "image_url_html"},
                {"mData": "name"},
                {"mData": "tagline"},
                {"mData": "abv"},
            ]
        });
    }

</script>
<!-- Menu Toggle Script -->
<script>
</script>
</body>
</html>
