<?php
$html = '';
if (count($Records) > 0) {
    $html .= '<label><b style="color: crimson;">' . ucwords($Product) . '</b> Profile</label>';
    $html .= '<select name="Profile" id="Profile" class="form-control" required onchange="storeSelectedName(this)">
                <option value="">Select Profile</option>';

    foreach ($Records as $R) {
        if ($ProductType == 'builder') {
            $String = Code($R['UID'], '' . substr(ucwords($Product), 0, 1) . '-') . ' - ' . $R['Name'] . ' ' . (($R['SubDomain'] != '') ? '( ' . $R['SubDomain'] . ' )' : '');
            $dataName = $R['Name'];
        } else {
            $String = Code($R['UID'], '' . substr(ucwords($Product), 0, 1) . '-') . ' - ' . $R['FullName'];
            $dataName = $R['FullName'];
        }

        $html .= '<option value="' . $R['UID'] . '" data-name="' . htmlspecialchars($dataName) . '">' . $String . '</option>';
    }

    $html .= '</select>';
    $html .= '<input type="hidden" id="SelectedProfileName" name="ProfileName">';
    $html .= '<script>
    function storeSelectedName(select) {
        const selectedOption = select.options[select.selectedIndex];
        document.getElementById("SelectedProfileName").value = selectedOption.getAttribute("data-name");
    }
    </script>';
}

echo $html;
?>