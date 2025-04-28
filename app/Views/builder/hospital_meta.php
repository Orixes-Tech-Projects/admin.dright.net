<?php

use App\Models\DiscountModel;
$config = array();
$config['web_builder_themes'] = array("basic" => "Color dominant ", "deep-mind" => "Image dominant (2024)", "mist" => "Image dominant (2025)");
//print_r($config['web_builder_themes']);exit();
$ThemeCss = array();
$ThemeCss['basic'] = array('dore.light.blue.css', 'dore.light.red.css', 'dore.light.purple.css', 'dore.light.green.css');
$ThemeCss['deep-mind'] = array('blue.css', 'black.css', 'gold.css', 'red.css');
$config['web_builder_themes_css'] = $ThemeCss;


$content = new \App\Models\BuilderModel();

$OptionExtra = $content->OptionExtra($UID);



?>
<br>

<div class="card">
    <div class="card-body">
        <h6 class="card-title">Add Theme</h6>
        <form class="form-horizontal validate" role="form" enctype="multipart/form-data" id="AddthemeDoctors" name="AddthemeDoctors" method="post">
            <input type="hidden" name="ProfileUID" id="ProfileUID" value="<?=$UID?>"/>
            <input type="hidden" name="id" id="id" value="0"/>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="inputState">Select Banner Style </label>
                    <select id="banner_style" name="option[banner_style]"
                            class="form-control validate[required]">
                        <option value=""<?= (isset($OptionExtra['banner_style']) && $OptionExtra['banner_style'] == '') ? 'selected' : '' ?>>Please Select</option>
                        <option value="basic"<?= (isset($OptionExtra['banner_style']) && $OptionExtra['banner_style'] == 'basic') ? 'selected' : '' ?>>Zoom In/Out</option>
                        <option value="animation"<?= (isset($OptionExtra['banner_style']) && $OptionExtra['banner_style'] == 'animation') ? 'selected' : '' ?>>Segmented Reveal</option>
                        <option value="version3"<?= (isset($OptionExtra['banner_style']) && $OptionExtra['banner_style'] == 'version3') ? 'selected' : '' ?>>Dual Display</option>
                        <option value="version4"<?= (isset($OptionExtra['banner_style']) && $OptionExtra['banner_style'] == 'version4') ? 'selected' : '' ?>>Overlay Fade-In</option>
                        <option value="random"<?= (isset($OptionExtra['banner_style']) && $OptionExtra['banner_style'] == 'random') ? 'selected' : '' ?>>Random</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputState">Ceo Message Number</label>
                    <select id="home_ceo_message" name="option[home_ceo_message]"
                            class="form-control validate[required]">
                        <option value=""<?= (isset($OptionExtra['home_ceo_message']) && $OptionExtra['home_ceo_message'] == '') ? 'selected' : '' ?>>Please Select</option>
                        <option value="1"<?= (isset($OptionExtra['home_ceo_message']) && $OptionExtra['home_ceo_message'] == '1') ? 'selected' : '' ?>>1</option>
                        <option value="2"<?= (isset($OptionExtra['home_ceo_message']) && $OptionExtra['home_ceo_message'] == '2') ? 'selected' : '' ?>>2</option>
                        <option value="3"<?= (isset($OptionExtra['home_ceo_message']) && $OptionExtra['home_ceo_message'] == '3') ? 'selected' : '' ?>>3</option>
                        <option value="4"<?= (isset($OptionExtra['home_ceo_message']) && $OptionExtra['home_ceo_message'] == '4') ? 'selected' : '' ?>>4</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputState">Our Facilities</label>
                    <select id="home_facilities" name="option[home_facilities]"
                            class="form-control validate[required]">
                        <option value=""<?= (isset($OptionExtra['home_facilities']) && $OptionExtra['home_facilities'] == '') ? 'selected' : '' ?>>Please Select</option>
                        <option value="1"<?= (isset($OptionExtra['home_facilities']) && $OptionExtra['home_facilities'] == '1') ? 'selected' : '' ?>>1</option>
                        <option value="2"<?= (isset($OptionExtra['home_facilities']) && $OptionExtra['home_facilities'] == '2') ? 'selected' : '' ?>>2</option>
                        <option value="3"<?= (isset($OptionExtra['home_facilities']) && $OptionExtra['home_facilities'] == '3') ? 'selected' : '' ?>>3</option>
                        <option value="4"<?= (isset($OptionExtra['home_facilities']) && $OptionExtra['home_facilities'] == '4') ? 'selected' : '' ?>>4</option>

                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputState">Our Latest News</label>
                    <select id="home_news" name="option[home_news]"
                            class="form-control validate[required]">
                        <option value=""<?= (isset($OptionExtra['home_news']) && $OptionExtra['home_news'] == '') ? 'selected' : '' ?>>Please Select</option>
                        <option value="1"<?= (isset($OptionExtra['home_news']) && $OptionExtra['home_news'] == '1') ? 'selected' : '' ?>>1</option>
                        <option value="2"<?= (isset($OptionExtra['home_news']) && $OptionExtra['home_news'] == '2') ? 'selected' : '' ?>>2</option>
                        <option value="3"<?= (isset($OptionExtra['home_news']) && $OptionExtra['home_news'] == '3') ? 'selected' : '' ?>>3</option>
                        <option value="4"<?= (isset($OptionExtra['home_news']) && $OptionExtra['home_news'] == '4') ? 'selected' : '' ?>>4</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputState">Patient's Reviews</label>
                    <select id="home_reviews" name="option[home_reviews]"
                            class="form-control validate[required]">
                        <option value=""<?= (isset($OptionExtra['home_reviews']) && $OptionExtra['home_reviews'] == '') ? 'selected' : '' ?>>Please Select</option>
                        <option value="1"<?= (isset($OptionExtra['home_reviews']) && $OptionExtra['home_reviews'] == '1') ? 'selected' : '' ?>>1</option>
                        <option value="2"<?= (isset($OptionExtra['home_reviews']) && $OptionExtra['home_reviews'] == '2') ? 'selected' : '' ?>>2</option>
                        <option value="3"<?= (isset($OptionExtra['home_reviews']) && $OptionExtra['home_reviews'] == '3') ? 'selected' : '' ?>>3</option>
                        <option value="4"<?= (isset($OptionExtra['home_reviews']) && $OptionExtra['home_reviews'] == '4') ? 'selected' : '' ?>>4</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputState">Select Layout <br> </label>
                    <select id="theme" name="option[theme]" class="form-control validate[required]"
                            onchange="ThemeFunction(this.value);">
                        <option value="">Please Select</option>
                        <?php
                        $host = $_SERVER['HTTP_HOST'];
                        $CheckHost = BackAdminHost($host);

                        $Val = array();
                        if (isset($CheckHost) && $CheckHost != '') {
                            $Val[] = 'basic';
                        } else {
                            $Val[] = '';
                        }

                        $Theme = $config['web_builder_themes'];
                        foreach ($Theme as $key => $value) {
                            if (!in_array($key, $Val)) {
                                echo '<option value="' . $key . '"';
                                echo (isset($OptionExtra['theme']) && $OptionExtra['theme'] == $key) ? 'selected' : '';
                                echo '>' . $value . '</option>';
                            }
                        }
                        ?>

                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">

                    <?php $Theme =$config['web_builder_themes_css'];?>
                    <div class="form-group col-md-12">
                        <!--							--><?php //print_r($_SESSION);?>
                        <div id="themecss">
                            <label for="inputState">Select Color</label>
                            <select id="theme_css" name="option[theme_css]"
                                    class="form-control validate[required] colorsselect basic-colorr">
                                <option value="">Please Select</option>
                                <?php
                                foreach ($Theme['basic'] as $value) {
                                    echo '<option value="' . $value . '"' .
                                        ((isset($OptionExtra['theme_css']) && $OptionExtra['theme_css'] == $value) ? 'selected' : '') .
                                        '>' . $value . '</option>';
                                } ?>
                            </select>
                            <select id="theme_deep-mind_css" name="option[theme_deep_mind_css]"
                                    class="form-control validate[required] colorsselect deep-mind-colorr">
                                <option value="">Please Select</option>
                                <?php
                                foreach ($Theme['deep-mind'] as $value) {
                                    echo '<option value="' . $value . '"' .
                                        ((isset($OptionExtra['theme_deep_mind_css']) && $OptionExtra['theme_deep_mind_css'] == $value) ? 'selected' : '') .
                                        '>' . $value . '</option>';
                                } ?>

                            </select>
                        </div>
                        <div id="h-theme" class="row" >
                            <div class="form-group col-md-6" id="primary">
                                <label for="inputState" id="primary">Select Primary Color </label><br>
                                <input type="color" name="option[theme_primary_color]" id="theme_primary_color"  value="<?= ((isset($OptionExtra['theme_primary_color'])) ? $OptionExtra['theme_primary_color'] : '') ?>">
                            </div>
                            <div class="form-group col-md-6" id="secondary">
                                <label for="inputState" id="primary">Select Secondary Color </label><br>
                                <input type="color" name="option[theme_secondary_color]" id="theme_secondary_color" value="<?= ((isset($OptionExtra['theme_secondary_color'])) ? $OptionExtra['theme_secondary_color'] : '') ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputState">Select Header</label>
                                <select id="theme_header" name="option[theme_header]"
                                        class="form-control validate[required]">
                                    <option value=""<?= (isset($OptionExtra['theme_header']) && $OptionExtra['theme_header'] == '') ? 'selected' : '' ?>>Please Select</option>
                                    <option value="simple_header"<?= (isset($OptionExtra['theme_header']) && $OptionExtra['theme_header'] == 'simple_header') ? 'selected' : '' ?>>Simple Header</option>
                                    <option value="center_header"<?= (isset($OptionExtra['theme_header']) && $OptionExtra['theme_header'] == 'center_header') ? 'selected' : '' ?>>Center Header</option>
                                    <option value="dark_header"<?= (isset($OptionExtra['theme_header']) && $OptionExtra['theme_header'] == 'dark_header') ? 'selected' : '' ?>>Dark Header</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputState">Select Footer</label>
                                <select id="theme_footer" name="option[theme_footer]"
                                        class="form-control validate[required]">
                                    <option value=""<?= (isset($OptionExtra['theme_footer']) && $OptionExtra['theme_footer'] == '') ? 'selected' : '' ?>>Please Select</option>
                                    <option value="smart_footer"<?= (isset($OptionExtra['theme_footer']) && $OptionExtra['theme_footer'] == 'smart_footer') ? 'selected' : '' ?>>Smart Footer</option>
                                    <option value="versatile_footer"<?= (isset($OptionExtra['theme_footer']) && $OptionExtra['theme_footer'] == 'versatile_footer') ? 'selected' : '' ?>>Versatile Footer</option>
                                    <option value="sticky_footer"<?= (isset($OptionExtra['theme_footer']) && $OptionExtra['theme_footer'] == 'sticky_footer') ? 'selected' : '' ?>>Sticky footer</option>
                                    <option value="display_footer"<?= (isset($OptionExtra['theme_footer']) && $OptionExtra['theme_footer'] == 'display_footer') ? 'selected' : '' ?>>Display footer</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputState">Featured Services </label>
                                <select id="theme_service" name="option[theme_service]"
                                        class="form-control validate[required]">
                                    <option value=""<?= (isset($OptionExtra['theme_service']) && $OptionExtra['theme_service'] == '') ? 'selected' : '' ?>>Please Select</option>
                                    <option value="version_1"<?= (isset($OptionExtra['theme_service']) && $OptionExtra['theme_service'] == 'version_1') ? 'selected' : '' ?>>1</option>
                                    <option value="version_2"<?= (isset($OptionExtra['theme_service']) && $OptionExtra['theme_service'] == 'version_2') ? 'selected' : '' ?>>2</option>


                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputState">Home Facilities </label>
                                <select id="theme_facilities" name="option[theme_facilities]"
                                        class="form-control validate[required]">
                                    <option value=""<?= (isset($OptionExtra['theme_facilities']) && $OptionExtra['theme_facilities'] == '') ? 'selected' : '' ?>>Please Select</option>
                                    <option value="version_1"<?= (isset($OptionExtra['theme_facilities']) && $OptionExtra['theme_facilities'] == 'version_1') ? 'selected' : '' ?>>1</option>
                                    <option value="version_2"<?= (isset($OptionExtra['theme_facilities']) && $OptionExtra['theme_facilities'] == 'version_2') ? 'selected' : '' ?>>2</option>


                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div id="AjaxResults"></div>
                </div>
            </div>
        </form>

    </div>
    <div class="mb-2">
    <span style="float: right">
                <button class="btn btn-primary" type="button" onclick="AddThemeSettingFunction()">Submit form</button>

</span>
    </div>

</div>
<style>
    .row.form-group {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $("#h-theme").hide();
        $("#themecss").hide();
        $("select.colorsselect").hide();

        // Check the selected value of the theme dropdown on page load
        const selectedTheme = document.getElementById("theme").value;

        // Show the relevant fields based on the selected theme
        if (selectedTheme === 'mist') {
            $("#label").hide();
            $("#h-theme").show();
            $("#themecss").hide();
            $("select#theme_css").hide();
            $("select#theme_" + selectedTheme + "_css").show();
        } else if (selectedTheme === 'basic') {
            $("#label").show();
            $("#h-theme").hide();
            $("#themecss").show();
            $("select#theme_css").show();
            $("select#theme_" + selectedTheme + "_css").show();
        } else if (selectedTheme === 'deep-mind') {
            $("#label").show();
            $("#h-theme").hide();
            $("#themecss").show();
            $("select#theme_css").hide();
            $("select#theme_" + selectedTheme + "_css").show();
        }

        // Add the ThemeFunction to dynamically update the fields when the dropdown changes
        document.getElementById("theme").addEventListener("change", ThemeFunction);
    });
    function ThemeFunction(opt) {
        // Get the selected value
        var opt = document.getElementById("theme").value;

        // Hide or show fields based on the selected layout
        if (opt === 'mist') {
            $("#label").hide();
            $("#h-theme").show();
            $("#themecss").hide();
            $("select#theme_css").hide();
            $("select#theme_" + opt + "_css").show();
        } else if (opt === 'basic') {
            $("#label").show();
            $("#h-theme").hide();
            $("#themecss").show();
            $("select#theme_css").show();
            $("select#theme_" + opt + "_css").show();
        } else if (opt === 'deep-mind') {
            $("#label").show();
            $("#h-theme").hide();
            $("#themecss").show();
            $("select#theme_css").hide();
            $("select#theme_" + opt + "_css").show();
        } else {
            // Default case for other layouts
            $("#label").show();
            $("#h-theme").hide();
            $("#themecss").hide();
            $("select#theme_css").hide();
            $("select#theme_" + opt + "_css").hide();
        }
    }

    function AddThemeSettingFunction() {
        var formdata = new window.FormData($("form#AddthemeDoctors")[0]);

        response = AjaxUploadResponse("builder/theme_form_submit", formdata);
        if (response.status === 'success') {
            $("#AjaxResults").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload(); // Reload the current page
            }, 500);
        } else {
            $("#AjaxResults").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>
<script>
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

<script src="<?= $template ?>assets/js/examples/form-validation.js"></script>

