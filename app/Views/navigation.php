<?php

$checknav = new \App\Models\SystemUser();
$hospital = $checknav->checkAccessKey('hospital');
$doctor = $checknav->checkAccessKey('doctor');

?>

<style>
    .navigation .navigation-menu-tab ul li a {
        padding: 4px 0 !important;
    }
</style>
<div class="navigation">
    <!-- Logo -->
    <div class="navigation-header">
        <a class="navigation-logo" href='<?= $path ?>' style="padding: 0 20px;">
            <img class="logo" src="<?= $template ?>logo.png" style="height: 70px;" alt="logo">
        </a>
        <!-- <a href="#" class="small-navigation-toggler"></a> -->
    </div>
    <!-- ./ Logo -->

    <!-- Menu wrapper -->
    <div class="navigation-menu-wrapper">
        <!-- Menu tab -->
        <div class="navigation-menu-tab">
            <ul>
                <?php
                if ($checknav->checkAccessKey('dashboards')) {
                    ?>
                    <li>
                        <a href=""
                           data-menu-target="#dashboards" <?= ($segment_a == 'dashboards' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                            <span>Dashboards</span>
                        </a>
                    </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('builder_dashobard')) {
                ?>
                <li>
                    <a href="#" data-menu-target="#pages" <?= ($segment_a == 'builder' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i data-feather="copy"></i>
                        </span>
                        <span>Builder</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('health_care')) {
                ?>
                <li>
                    <a href="#"
                       data-menu-target="#forms" <?= ($segment_a == 'diet' || $segment_a == 'customers' || $segment_a == 'diseases' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i class="pe-is-w-thermometer-1-f"></i>
                        </span>
                        <span>Health Care</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('extended')) {
                ?>
                <li>
                    <a href="#" data-menu-target="#plugins" <?= ($segment_a == 'extended' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i data-feather="gift"></i>
                        </span>
                        <span>Extended</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('invoice')) {
                ?>
                <li>
                    <a href="#" data-menu-target="#invoice" <?= ($segment_a == 'invoice' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i data-feather="gift"></i>
                        </span>
                        <span>Invoice</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('investigation')) {
                ?>
                <li>
                    <a href="#"
                       data-menu-target="#investigation" <?= ($segment_a == 'investigation' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Investigation</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('pharmacy')) {
                ?>
                <li>
                    <a href="#" data-menu-target="#other" <?= ($segment_a == 'medicine' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i data-feather="arrow-up-right"></i>
                        </span>
                        <span>Pharmacy</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('support_ticket')) {
                ?>
                <li>
                    <a href="" data-menu-target="#apps" <?= ($segment_a == 'supportticket' ? 'class="active"' : '') ?>
                    >     <span class="menu-tab-icon">
                            <i data-feather="users"></i>
                    </span>
                        <span>Support Ticket</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('laboratories')) {
                ?>
                <li>
                    <a href=""
                       data-menu-target="#laboratories" <?= ($segment_a == 'laboratories' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i data-feather="users"></i>
                        </span>
                        <span>Laboratories</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('system')) {
                ?>

                <li>
                    <a href="#" data-menu-target="#users" <?= ($segment_a == 'users' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i data-feather="users"></i>
                        </span>
                        <span>System</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('documentations')) {
                ?>
                <li>
                    <a href=""
                       data-menu-target="#document" <?= ($segment_a == 'document' ? 'class="active"' : '') ?>>
                        <span class="menu-tab-icon">
                            <i data-feather="users"></i>
                        </span>
                        <span>Documentations</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
        <!-- ./ Menu tab -->

        <!-- Menu body -->
        <div class="navigation-menu-body">

            <ul id="apps">
                <li class="navigation-divider">Support Ticket</li>
                <li>
                    <a href="<?= $path ?>support-ticket/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <?php
                if ($checknav->checkAccessKey('extended')) {
                ?>
                <li class="d-none">
                    <a href="<?= $path ?>support-ticket/clinta_extended">
                        <span class="nav-link-icon" data-feather="file"></span>
                        <span>Clinta Extended</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('builder_support_ticket')) {
                ?>
                <li>
                    <a href="<?= $path ?>support-ticket/builder_support_ticket">
                        <span class="nav-link-icon" data-feather="file"></span>
                        <span>Builder Ticket</span>
                    </a>
                </li>
                <?php } ?>

            </ul>
            <ul id="invoice">
                <li class="navigation-divider">Invoice</li>

                <?php
                if ($checknav->checkAccessKey('invoice_invoice_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>invoice/index">
                        <span class="nav-link-icon" data-feather="file"></span>
                        <span>Invoice</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('invoice_items_list')) {
                    ?>
                    <li class="d-none">
                        <a href="<?= $path ?>invoice/customer">
                        <span class="nav-link-icon" data-feather="file"></span>
                        <span>Customer</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('invoice_items_list')) {
                    ?>
                <li>
                    <a href="<?= $path ?>support-ticket/items">
                        <span class="nav-link-icon" data-feather="file"></span>
                        <span>Packages</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
            <ul id="customers">
                <li class="navigation-divider">Customers</li>
                <li>
                    <a href="<?= $path ?>customers/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <li>
                    <a href="#">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Customers</span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?= $path ?>customers/">All</a>
                        </li>
                        <li>
                            <a href="<?= $path ?>customers/add">Add</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul id="dashboards">
                <li class="navigation-divider">Dashboards</li>
                <li>
                    <a href="<?= $path ?>">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>support-ticket/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>HealthCare Dashbaord</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $path ?>investigation/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Investigation Dashbaord</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $path ?>pharmacy/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Pharmacy Dashbaord</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $path ?>users/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Users Dashbaord</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $path ?>support-ticket/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>HealthCare Dashbaord</span>
                    </a>
                </li>
            </ul>
            <ul id="clinta_members">
                <li class="navigation-divider">Clinta Member</li>
                <li>
                    <a href="<?= $path ?>clinta_members/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>clinta_members/">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Clinta Member</span>
                    </a>

                </li>
            </ul>
            <ul id="diseases">
                <li class="navigation-divider">Diseases</li>
                <li>
                    <a href="<?= $path ?>customers/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>diseases/">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Diseases</span>
                    </a>

                </li>
            </ul>
            <ul id="document">
                <li class="navigation-divider">Documentations</li>
                <li>
                    <a href="<?= $path ?>document/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>document/diet-plan">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Diet Plan</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>document/workout-plan">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Workout</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>document/tips-guides">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Tips and Guide</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>document/faq">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Faqs</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>document/exercise">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Exercises</span>
                    </a>

                </li>
            </ul>
            <ul id="laboratories">
                <li class="navigation-divider">Laboratories</li>
                <li>
                    <a href="<?= $path ?>laboratories/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <?php
                if ($checknav->checkAccessKey('laboratories_laboratories_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>laboratories/">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Laboratories</span>
                    </a>

                </li>
                <?php } ?>
            </ul>

            <ul id="investigation">
                <li class="navigation-divider">investigation</li>

                <?php
                if ($checknav->checkAccessKey('investigation')) {
                ?>
                <li>
                    <a href="<?= $path ?>investigation/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('investigation_lab_report_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>investigation/all/reports">
                        <span class="nav-link-icon">
                            <i data-feather="aperture"></i>
                        </span>
                        <span>Lab Reports</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('investigation_radiology_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>investigation/all/radiology">
                        <span class="nav-link-icon">
                            <i data-feather="anchor"></i>
                        </span>
                        <span>Radiology</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
            <!--            health care-->
            <ul id="forms">
                <li class="navigation-divider">Health Care</li>
                <li>
                    <a href="<?= $path ?>diet/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <?php
                if ($checknav->checkAccessKey('healthcare_supportvideos_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>diet/support-videos">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Support Videos</span>
                    </a></li>
                <li>
                    <?php } ?>
                    <?php
                    if ($checknav->checkAccessKey('healthcare_diet_category_list')) {
                    ?>
                <li>
                    <a href="<?= $path ?>diet/diet-categories">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Diet Categories</span>
                    </a></li>
                <li>
                    <?php } ?>
                    <?php
                    if ($checknav->checkAccessKey('healthcare_diet_fact_list')) {
                    ?>
                    <a href="#">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Diet Facts</span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?= $path ?>diet/fruit">Fruit</a>
                        </li>
                        <li>
                            <a href="<?= $path ?>diet/vegetable">Vegetable</a>
                        </li>
                        <li>
                            <a href="<?= $path ?>diet/miscellaneous">Miscellaneous</a>
                        </li>
                        <li>
                        <li>
                            <a href="<?= $path ?>diet/pulses-grains">Pulses & Grains</a>
                        </li>
                        <li>
                        <li>
                            <a href="<?= $path ?>diet/dry-fruits">Dry Fruits</a>
                        </li>
                        <li>

                    </ul>
                </li>
            <?php } ?>
                <?php
                if ($checknav->checkAccessKey('healthcare_diseases_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>diseases/">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Diseases</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('healthcare_branches_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>frenchises/">
                        <span class="nav-link-icon">
                            <i data-feather="book"></i>
                        </span>
                        <span>Branches</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('healthcare_discount_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>discount/discount_center">
                        <span class="nav-link-icon">
                            <i data-feather="book"></i>
                        </span>
                        <span>Discounts</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('healthcare_clinta_member_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>clinta_members/">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Clinta Member</span>
                    </a>

                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('healthcare_customer_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>customers/">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Customers</span>
                    </a>

                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('healthcare_rcc_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>representative/">
                        <span class="nav-link-icon">
                            <i data-feather="disc"></i>
                        </span>
                        <span>RCC</span>
                    </a>

                </li>
                <?php } ?>

            </ul>
            <!--            Extended-->

            <ul id="plugins">
                <li class="navigation-divider">Extended</li>
                <li>
                    <a href="<?= $path ?>extended/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <?php
                if ($checknav->checkAccessKey('extended_profiles_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>extended">
                        <span class="nav-link-icon" data-feather="crop"></span>
                        <span>Profiles</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('extended_default_lookup_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>extended/extended_default_config">
                        <span class="nav-link-icon" data-feather="clipboard"></span>
                        <span>Default Configuration</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('extended_default_configration_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>extended/extended_default_lookup">
                        <span class="nav-link-icon" data-feather="sliders"></span>
                        <span>Default Lookups</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
            <!--            Builder-->
            <ul id="pages">
                <li class="navigation-divider">Builder</li>
                <li>
                    <a href="<?= $path ?>builder/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <?php
                if ($checknav->checkAccessKey('builder_doctor_profiles_list')) {
                    ?>
                    <li>
                        <a href="<?= $path ?>builder/">
                            <span class="nav-link-icon" data-feather="hash"></span>
                            <span>Doctors</span>
                        </a>
                    </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('builder_hospital_profiles_list')) {
                    ?>
                    <li>
                        <a href="<?= $path ?>builder/hospital">
                            <span class="nav-link-icon" data-feather="search"></span>
                            <span>Hospital</span>
                        </a>
                    </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('builder_images_list')) {
                ?>
                <li class="d-none">
                    <a href="<?= $path ?>builder/images">
                        <span class="nav-link-icon" data-feather="layout"></span>
                        <span>v1.0 Banners</span>

                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('builder_banners_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>builder/banners">
                        <span class="nav-link-icon" data-feather="frown"></span>
                        <span>Banners</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('builder_sponser_list')) {
                ?>
                <li class="d-none">
                    <a href="<?= $path ?>builder/sponser">
                        <span class="nav-link-icon" data-feather="frown"></span>
                        <span>Sponsor</span>
                    </a>
                </li>
                <?php } ?>
                <?php
                if ($checknav->checkAccessKey('builder_specialities_list')) {
                ?>
                <li>
                    <a href="<?= $path ?>builder/specialities">
                        <span class="nav-link-icon" data-feather="frown"></span>
                        <span>Specialities</span>
                    </a>
                </li>
                <?php } ?>
            </ul>
            <ul id="other">
                <li class="navigation-divider">Medicine</li>
                <li>
                    <a href="<?= $path ?>pharmacy/dashboard">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>pharmacy/">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Pharmacy Profile</span>
                    </a>

                </li>
                <li>
                    <a href="#">
                        <span class="nav-link-icon" data-feather="activity"></span>
                        <span>Medicine</span>
                    </a>
                    <ul>

                        <li>
                            <a href="<?= $path ?>medicine/">Medicine List</a>
                        </li>
                        <li>
                            <a href="<?= $path ?>medicine/take_type">Take Type</a>
                        </li>
                        <li>
                            <a href="<?= $path ?>medicine/medicine_forms">Forms</a>
                        </li>
                        <li>
                            <a href="<?= $path ?>medicine/timing">Timing</a>
                        </li>
                        <li>
                            <a href="<?= $path ?>medicine/company">Company</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul id="users">
                <li class="navigation-divider">Users</li>

                <li>
                    <a href="<?= $path ?>users">
                        <span class="nav-link-icon" data-feather="users"></span>
                        <span>User</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $path ?>lookups/">
                        <span class="nav-link-icon" data-feather="check-circle"></span>
                        <span>Lookups</span>
                    </a>
                </li>
                <!--                <li>-->
                <!--                    <a href="--><?php //= $path ?><!--users/access-level">-->
                <!--                        <span class="nav-link-icon">-->
                <!--                            <i data-feather="mail"></i>-->
                <!--                        </span>-->
                <!--                        <span>Access Level</span>-->
                <!--                    </a>-->
                <!---->
                <!--                </li>-->
                <li>
                    <a href="<?= $path ?>users/admin-activites">
                        <span class="nav-link-icon">
                            <i data-feather="mail"></i>
                        </span>
                        <span>Admin Activites</span>
                    </a>

                </li>
                <li>
                    <a href="<?= $path ?>users/admin-approval">
                        <span class="nav-link-icon">
                            <i data-feather="mail"></i>
                        </span>
                        <span>Admin Approvals</span>
                    </a>

                </li>

            </ul>
        </div>
        <!-- ./ Menu body -->
    </div>
    <!-- ./ Menu wrapper -->
</div>
<!-- ./ Navigation -->

<script type="text/javascript">
    $(document).ready(function () {
        $(".navigation-menu-tab ul li a").hover(
            function () {
                $(this).click();
            }
        );
    });
</script>