<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<style>
    .table th, td {
        padding: 0.62rem !important;
    }

    .table th {
        font-size: 10px !important;
        color: white !important;
    }

    #ClientsDatabaseTables thead th {
        background: #1b2e4b !important;
        color: white !important;
    }

    /* Enhanced Tile Styles */
    .stats-tile {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .stats-tile:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .stats-tile::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--tile-color), transparent);
    }

    .stats-tile.tile-primary::before {
        --tile-color: #4361ee;
    }

    .stats-tile.tile-success::before {
        --tile-color: #06d6a0;
    }

    .stats-tile.tile-warning::before {
        --tile-color: #ffd166;
    }

    .stats-tile.tile-info::before {
        --tile-color: #118ab2;
    }

    .stats-tile .card-body {
        padding: 1.5rem;
        position: relative;
    }

    .tile-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .tile-primary .tile-icon {
        background: rgba(67, 97, 238, 0.15);
        color: #4361ee;
    }

    .tile-success .tile-icon {
        background: rgba(6, 214, 160, 0.15);
        color: #06d6a0;
    }

    .tile-warning .tile-icon {
        background: rgba(255, 209, 102, 0.15);
        color: #ff9e00;
    }

    .tile-info .tile-icon {
        background: rgba(17, 138, 178, 0.15);
        color: #118ab2;
    }

    .tile-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .tile-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
        margin-bottom: 0.5rem;
    }

    .tile-subtext {
        font-size: 0.75rem;
        color: #718096;
        font-weight: 500;
    }

    .tile-comparison {
        display: flex;
        align-items: center;
        margin-top: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .tile-comparison.positive {
        color: #06d6a0;
    }

    .tile-comparison.negative {
        color: #ef476f;
    }

    .progress {
        height: 6px;
        border-radius: 3px;
        background: #e9ecef;
        margin-top: 0.75rem;
    }

    .progress-bar {
        border-radius: 3px;
    }
</style>
<?php

use App\Models\BuilderModel;

$session = session();
$SessionFilters = $session->get('BuilderDashboardClientsFilters');
$ClientType = $StartDate = $EndDate = $ClientLevel = '';
if (isset($SessionFilters['ClientType']) && $SessionFilters['ClientType'] != '') {
    $ClientType = $SessionFilters['ClientType'];
}
if (isset($SessionFilters['StartDate']) && $SessionFilters['StartDate'] != '') {
    $StartDate = $SessionFilters['StartDate'];
}
if (isset($SessionFilters['EndDate']) && $SessionFilters['EndDate'] != '') {
    $EndDate = $SessionFilters['EndDate'];
}
if (isset($SessionFilters['ClientLevel']) && $SessionFilters['ClientLevel'] != '') {
    $ClientLevel = $SessionFilters['ClientLevel'];
}

$BuilderModel = new BuilderModel();
$DashboardStats = $BuilderModel->GetDashboardStats();
?>
<div class="page-header mt-2">
    <div class="page-title">
        <h3>Builder Dashboard</h3>
        <div class="d-none">
            <div id="analytics-dashboard-daterangepicker" class="btn btn-outline-light">
                <i class="ti-calendar mr-2 text-muted"></i>
                <span></span>
            </div>
            <a href="#" class="btn btn-primary ml-2" data-toggle="dropdown">
                <i class="ti-download"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item">Download</a>
                <a href="#" class="dropdown-item">Print</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Total Clients Tile -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
        <div class="card card-body stats-tile tile-primary">
            <div class="tile-icon">
                <i class="ti-user" style="font-size: 1.5rem;"></i>
            </div>
            <div class="tile-label">Total Clients</div>
            <div class="tile-value"><?= number_format($DashboardStats['TotalClients']) ?></div>
            <div class="tile-subtext">Active & Verified Clients</div>
        </div>
    </div>

    <!-- Revenue Tile -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
        <div class="card card-body stats-tile tile-success">
            <div class="tile-icon">
                <i class="ti-money" style="font-size: 1.5rem;"></i>
            </div>
            <div class="tile-label">Revenue Overview</div>
            <div class="tile-value"><?= money($DashboardStats['MTDRevenue'], false) ?></div>
            <div class="tile-subtext">
                MTD Revenue / <span class="text-warning"><?= money($DashboardStats['OutstandingValue'], false) ?> Outstanding</span>
            </div>
        </div>
    </div>

    <!-- Expiry Tile -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
        <div class="card card-body stats-tile tile-warning">
            <div class="tile-icon">
                <i class="ti-calendar" style="font-size: 1.5rem;"></i>
            </div>
            <div class="tile-label">Subscription Expiry</div>
            <div class="tile-value"><?= $DashboardStats['CurrentMonthExpiry'] ?>
                / <?= $DashboardStats['QuarterExpiry'] ?></div>
            <div class="tile-subtext">Month / Quarter Expiry</div>
        </div>
    </div>

    <!-- Inactive Domains Tile -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
        <div class="card card-body stats-tile tile-info">
            <div class="tile-icon">
                <i class="ti-na" style="font-size: 1.5rem;"></i>
            </div>
            <div class="tile-label">Inactive Domains</div>
            <div class="tile-value"><?= $DashboardStats['CurrentMonthInactive'] ?>
                / <?= $DashboardStats['QuarterInactive'] ?></div>
            <div class="tile-subtext">Month / Quarter Inactive</div>
        </div>
    </div>
</div>

<div class="card mt-2">
    <div style="background: #1b2e4b; color: white;" class="card-header">
        Clients Database Tables
    </div>
    <div style="padding: 0.8rem !important;" class="card-body">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="accordion accordion-primary custom-accordion">
                    <div class="accordion-row <?= ((isset($SessionFilters) && $SessionFilters != '' && count($SessionFilters) > 0) ? 'open' : '') ?>">
                        <a href="#" class="accordion-header">
                            <span>Search Filters <small>( Click to Search Records From Filter )</small></span>
                            <i class="accordion-status-icon close fa fa-chevron-up"></i>
                            <i class="accordion-status-icon open fa fa-chevron-down"></i>
                        </a>
                        <div class="accordion-body">
                            <form method="post" name="ClientsFilterForm" id="ClientsFilterForm"
                                  onsubmit="SearchFilterFormSubmit('ClientsFilterForm');">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-control-label no-padding-right">Client Type</label>
                                        <select name="ClientType" id="ClientType" class="form-control">
                                            <option value="">Select Type</option>
                                            <option <?= (($ClientType == 'hospitals') ? 'selected' : '') ?>
                                                    value="hospitals">Hospitals
                                            </option>
                                            <option <?= (($ClientType == 'doctors') ? 'selected' : '') ?>
                                                    value="doctors">Doctors
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Start Date</label>
                                        <input value="<?= $StartDate ?>" type="date" name="StartDate" id="StartDate"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label>End Date</label>
                                        <input value="<?= $EndDate ?>" type="date" name="EndDate" id="EndDate"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-control-label no-padding-right">Client Level</label>
                                        <select name="ClientLevel" id="ClientLevel" class="form-control">
                                            <option value="">Select Level</option>
                                            <option <?= (($ClientLevel == 'Standard') ? 'selected' : '') ?>
                                                    value="Standard">Standard
                                            </option>
                                            <option <?= (($ClientLevel == 'VAS') ? 'selected' : '') ?> value="VAS">VAS
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2" style="margin-top: 33px;">
                                        <button style="border-radius: 0.2rem !important;"
                                                class="btn btn-outline-success btn-sm"
                                                onclick="SearchFilterFormSubmit('ClientsFilterForm');"
                                                type="button">Search!
                                        </button>
                                        <button style="border-radius: 0.2rem !important;"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="ClearAllFilter('BuilderDashboardClientsFilters');"
                                                type="button">Clear
                                        </button>
                                    </div>
                                    <div class="col-md-12 mt-3" id="FilterResponse"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="ClientsDatabaseTables" class="table table-bordered table-striped">
                <thead style="background: #1b2e4b;">
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Licensing Date</th>
                    <th rowspan="2">Type</th>
                    <th class="text-center" colspan="4">Subscription Invoices</th>
                    <th rowspan="2">Expiry Date</th>
                    <th rowspan="2">Level</th>
                    <th class="text-center" colspan="3">VAS MTD Payable</th>
                    <th rowspan="2">Domain / Sub Domain</th>
                </tr>
                <tr>
                    <th>Invoices</th>
                    <th>Price</th>
                    <th>Received</th>
                    <th>OutStanding</th>
                    <th>Invoices</th>
                    <th>Prescriptions</th>
                    <th>Total</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>
<script>
    $(document).ready(function () {
        ClientsDashboardDatabaseTables();
    });

    function ClientsDashboardDatabaseTables() {

        $('#ClientsDatabaseTables').DataTable({
            "scrollCollapse": true,
            "searching": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
            "pageLength": 25,
            "autoWidth": false,
            "ordering": false,
            "ajax": {
                "url": "<?= $path ?>builder/get_clients_dashboard_records",
                "type": "POST"
            }
        });
    }

    function SearchFilterFormSubmit(parent) {

        var form = $("form#" + parent);
        var data = form.serialize();

        var startDate = form.find('input[name="StartDate"]').val();
        var endDate = form.find('input[name="EndDate"]').val();

        var errorMessage = '';
        if ((startDate && !endDate) || (!startDate && endDate)) {
            errorMessage = 'Both Start Date and End Date must be selected together.';
        } else if (startDate && endDate) {
            var start = new Date(startDate);
            var end = new Date(endDate);

            if (start > end) {
                errorMessage = 'Start Date must be less than or equal to End Date.';
            }
        }

        if (errorMessage) {
            $("form#ClientsFilterForm div#FilterResponse").html('<div class="alert alert-danger mb-2" style="margin: 10px;" role="alert"> <strong>' + errorMessage + '</strong></div>');
            return false;
        }

        var rslt = AjaxResponse('builder/builder_clients_dashboard_search_filters', data);
        if (rslt.status == 'success') {
            $("form#ClientsFilterForm div#FilterResponse").html('<div class="alert alert-success mb-2" style="margin: 10px;" role="alert"> <strong>' + rslt.message + '</strong></div>');

            setTimeout(function () {
                location.reload();
            }, 1000);
        }
    }

    function ClearAllFilter(Session) {
        var rslt = AjaxResponse('home/clear_session', 'SessionName=' + Session);
        if (rslt.status == 'success') {
            $("#FilterResponse").html('<div class="alert alert-success mb-2" style="margin: 10px;" role="alert"> <strong>Clear Successfully!</strong>  </div>');

            setTimeout(function () {
                location.reload();
            }, 1000);
        }
    }

</script>