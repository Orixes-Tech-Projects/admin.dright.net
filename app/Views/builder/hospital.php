<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/buttons.dataTables.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('HospitalFilters');
$Name = '';
$City = '';
if (isset($SessionFilters['Name']) && $SessionFilters['Name'] != '') {
    $Name = $SessionFilters['Name'];
}
if (isset($SessionFilters['City']) && $SessionFilters['City'] != '') {
    $City = $SessionFilters['City'];
}
?>
<style>
    .form-control {
        border-radius: 0.2rem !important;
    }

    .dt-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-left: 10px;
    }

    .dt-buttons .btn,
    .dt-buttons .dt-button {
        border-radius: 0.2rem !important;
        padding: 0.35rem 0.75rem !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center;
        gap: 6px;
        border: 1px solid transparent !important;
        box-shadow: none !important;
        background-image: none !important;
        transition: background-color 0.12s ease, color 0.12s ease, border-color 0.12s ease;
    }

    .dt-buttons .btn-export-excel,
    .dt-buttons .buttons-excel {
        background: #1b9c5a !important;
        border-color: #198754 !important;
        color: #fff !important;
    }

    .dt-buttons .btn-export-excel:hover,
    .dt-buttons .buttons-excel:hover {
        background: #17804a !important;
        border-color: #146c43 !important;
        color: #fff !important;
    }

    .dt-buttons .btn-export-pdf,
    .dt-buttons .buttons-pdf {
        background: #d64545 !important;
        border-color: #c03434 !important;
        color: #fff !important;
    }

    .dt-buttons .btn-export-pdf:hover,
    .dt-buttons .buttons-pdf:hover {
        background: #b43737 !important;
        border-color: #9f2f2f !important;
        color: #fff !important;
    }

    .dt-buttons .btn-export-print,
    .dt-buttons .buttons-print {
        background: #3b82f6 !important;
        border-color: #2f6fe3 !important;
        color: #fff !important;
    }

    .dt-buttons .btn-export-print:hover,
    .dt-buttons .buttons-print:hover {
        background: #326fd1 !important;
        border-color: #2b63bb !important;
        color: #fff !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="accordion accordion-primary custom-accordion">
            <div class="accordion-row <?= ((isset($SessionFilters) && $SessionFilters != '' && count($SessionFilters) > 0) ? 'open' : '') ?>">
                <a href="#" class="accordion-header">
                    <span>Search Filters <small>( Click to Search Record From Filters )</small></span>
                    <i class="accordion-status-icon close fa fa-chevron-up"></i>
                    <i class="accordion-status-icon open fa fa-chevron-down"></i>
                </a>
                <div class="accordion-body">
                    <form method="post" name="AllHospitalFilterForm" id="AllHospitalFilterForm"
                          onsubmit="SearchFilterFormSubmit('AllHospitalFilterForm');">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-control-label no-padding-right">Name:</label>
                                    <input type="text" id="Name" name="Name" placeholder="Name"
                                           class="form-control " value="<?= $Name; ?>"
                                           data-validation-engine="validate[required]"
                                           data-errormessage="MAC Address is required"/>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <label>City:</label>
                                        <select id="City" name="City" class="form-control"
                                                data-validation-engine="validate[required]">
                                            <option value="">Please Select</option>
                                            <?php foreach ($Cities as $record) { ?>
                                                <option value="<?= $record['UID'] ?>" <?= (isset($City) && $City == $record['UID']) ? 'selected' : '' ?>
                                                ><?= ucwords($record['FullName']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-top: 33px;">
                                    <button style="border-radius: 5px;" class="btn btn-outline-success btn-sm"
                                            onclick="SearchFilterFormSubmit('AllHospitalFilterForm');"
                                            type="button">Search!
                                    </button>
                                    <button style="border-radius: 5px;" class="btn btn-outline-danger btn-sm"
                                            onclick="ClearAllFilter('HospitalFilters');"
                                            type="button">Clear
                                    </button>
                                </div>
                                <div class="mt-4 col-md-12" id="FilterResponse"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-2">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h5 class="mb-0" id="hospital-heading">List Of All Hospitals</h5>
        <div class="d-flex flex-wrap align-items-center gap-2" id="hospital-actions">
            <button style="border-radius:5px;" type="button" onclick="AddHospital()"
                    class="btn btn-primary btn-sm">
                Add Hospital
            </button>
        </div>
    </div>
    <div style="padding: 0.8rem !important;" class="card-body">
        <div id="AddSmsCreditsResponse" class="mt-2"></div>
        <div id="Response" class="mt-2"></div>
        <div class="table-responsive">
            <table id="hospital" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th data-priority="1">#</th>
                    <th data-priority="2">Name</th>
                    <th data-priority="3">SubDomain</th>
                    <th data-priority="5">City</th>
                    <th data-priority="6">Status</th>
                    <th data-priority="7">Expire Date</th>
                    <th data-priority="8">Email</th>
                    <th data-priority="9">Last Visit</th>
                    <th data-priority="10">SMS Credits</th>
                    <th data-priority="4">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php echo view('builder/modal/add_individual_banner'); ?>
<script>
    $(document).ready(function () {
        if ($.fn.dataTable.isDataTable('#hospital')) {
            $('#hospital').DataTable().clear().destroy();
        }

        var hospitalTable = $('#hospital').DataTable({
            "scrollCollapse": true,
            "searching": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ordering": true,
            "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
            "pageLength": 25,
            "autoWidth": true,
            "orderMulti": false,
            "order": [[1, 'asc']],
            "columns": [
                {data: 0, orderable: false, searchable: false},
                {data: 1, name: 'name'},
                {data: 2, name: 'subdomain'},
                {data: 3, name: 'city'},
                {data: 4, name: 'status'},
                {data: 5, name: 'expire_date'},
                {data: 6, name: 'email'},
                {data: 7, name: 'last_visit'},
                {data: 8, orderable: false},
                {data: 9, orderable: false, searchable: false}
            ],
            "dom": '<"d-flex flex-wrap justify-content-between align-items-center mb-3"lfB>rtip',
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o me-1"></i> Excel',
                    className: 'btn btn-sm btn-export-excel',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7],
                        modifier: {page: 'all'}
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o me-1"></i> PDF',
                    className: 'btn btn-sm btn-export-pdf',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 7],
                        modifier: {page: 'all'}
                    },
                    customize: function (doc) {
                        doc.pageOrientation = 'landscape';
                        doc.pageSize = {width: 842, height: 595};
                        doc.pageMargins = [20, 20, 20, 20];
                        doc.defaultStyle.fontSize = 8;
                        doc.defaultStyle.alignment = 'center';

                        doc.styles.tableHeader.fillColor = '#0d47a1';
                        doc.styles.tableHeader.color = '#ffffff';
                        doc.styles.tableHeader.alignment = 'center';
                        doc.styles.tableHeader.fontSize = 8;

                        doc.styles.title = {
                            fontSize: 14,
                            bold: true,
                            alignment: 'center',
                            margin: [0, 0, 0, 12]
                        };

                        if (doc.content && doc.content.length > 0 && doc.content[0].text) {
                            doc.content[0].text = 'D-Right aPanel - Hospitals List';
                            doc.content[0].alignment = 'center';
                            doc.content[0].margin = [0, 0, 0, 12];
                        }

                        if (doc.content[1] && doc.content[1].table && doc.content[1].table.body) {
                            doc.content[1].margin = [0, 0, 0, 0];
                            doc.content[1].table.widths = [
                                '4%', '15%', '20%', '10%', '9%', '16%', '26%'
                            ];
                            doc.content[1].layout = {
                                hLineWidth: function () {
                                    return 0.5;
                                },
                                vLineWidth: function () {
                                    return 0.5;
                                },
                                hLineColor: function () {
                                    return '#b0bec5';
                                },
                                vLineColor: function () {
                                    return '#b0bec5';
                                },
                                paddingLeft: function () {
                                    return 4;
                                },
                                paddingRight: function () {
                                    return 4;
                                },
                                paddingTop: function () {
                                    return 3;
                                },
                                paddingBottom: function () {
                                    return 3;
                                }
                            };
                        }
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print me-1"></i> Print',
                    className: 'btn btn-sm btn-export-print',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7],
                        modifier: {page: 'all'}
                    },
                    customize: function (win) {
                        var title = $('#hospital-heading').text().trim() || 'All Hospitals';
                        $(win.document.body).css('font-family', '"Helvetica Neue", Helvetica, Arial, sans-serif');
                        $(win.document.body).find('h1').text(title).css({
                            'text-align': 'center',
                            'font-size': '16pt',
                            'margin-bottom': '12px'
                        });

                        var style = '<style>' +
                            'table{width:100%; border-collapse:collapse;}' +
                            'th,td{border:1px solid #b0bec5; padding:6px 8px; font-size:10pt; text-align:center;}' +
                            'th{background:#0d47a1; color:#fff; font-size:11pt;}' +
                            'tr:nth-child(even){background:#f5f5f5;}' +
                            '</style>';
                        $(win.document.head).append(style);
                    }
                }
            ],
            "columnDefs": [
                {"searchable": false, "targets": -1}
            ],
            "ajax": {
                "url": "<?= $path ?>builder/get-hospital",
                "type": "POST"
            }
        });
        hospitalTable.buttons().container().appendTo('#hospital-actions');
    });

</script>
<script>
    function AddHospital() {
        location.href = "<?=$path?>builder/add-hospital";
    }

    function Updatehospital(id) {
        location.href = "<?=$path?>builder/update-hospital/" + id;
    }

    function AddSmsCredits(id, newcredits) {
        if (confirm("Are You Want To Add " + newcredits + " SMS Credits")) {
            response = AjaxResponse('builder/add_sms_credits', "id=" + id + "&newcredits=" + newcredits);
            if (response.status == 'success') {
                $("#AddSmsCreditsResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Added Successfully!</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                $("#AddSmsCreditsResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error! Not Added</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    }

    function AddTheme(id) {
        location.href = "<?=$path?>builder/add_theme/" + id;
    }

    function DeleteHospital(id) {
        if (confirm("Are you Sure U want to Delete this?")) {
            response = AjaxResponse("builder/delete-hospital", "id=" + id);
            if (response.status == 'success') {
                $("#Response").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Deleted Successfully!</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                $("#Response").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error! Not Deleted</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    }

    function SearchFilterFormSubmit(parent) {
        var data = $("form#" + parent).serialize();
        var rslt = AjaxResponse('builder/hospital_search_filter', data);
        if (rslt.status == 'success') {
            $("#AllHospitalFilterForm form #FilterResponse").html(rslt.message);
            location.reload();
        }
    }

    function ClearAllFilter(Session) {
        var rslt = AjaxResponse('home/clear_session', 'SessionName=' + Session);
        if (rslt.status == 'success') {
            $("#AllHospitalFilterForm form #FilterResponse").html(rslt.message);
            location.reload();
        }
    }
</script>

<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>vendors/dataTable/dataTables.buttons.min.js"></script>
<script src="<?= $template ?>vendors/dataTable/jszip.min.js"></script>
<script src="<?= $template ?>vendors/dataTable/pdfmake.min.js"></script>
<script src="<?= $template ?>vendors/dataTable/vfs_fonts.js"></script>
<script src="<?= $template ?>vendors/dataTable/buttons.html5.min.js"></script>
<script src="<?= $template ?>vendors/dataTable/buttons.print.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>
