<style>
    .author-description {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #6c757d;
        margin-bottom: 0;
        display: block;
        line-height: 1.5;
        width: 100%;
    }

    .author-description.expanded {
        white-space: normal;
        overflow: visible;
        text-overflow: unset;
    }

    .read-more {
        color: white;
        cursor: pointer;
        font-size: 0.85rem;
        margin-top: 5px;
        display: inline-block;
        background: crimson;
        padding: 5px;
    }
    * {
        min-width: 0;
    }
</style>

<table class="table table-bordered table-sm">
    <thead style="background: #1b2e4b; color: white !important;">
    <tr>
        <th style="width: 30px;">#</th>
        <th style="width: 200px;">Title</th>
        <th>Script</th>
        <th style="width: 150px;">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $cnt = 1;
    foreach ($ScriptRecords as $SR) {

        $fullScript = htmlspecialchars($SR['Script']);
        $truncatedScript = htmlspecialchars(mb_strimwidth($SR['Script'], 0, 100, '...'));
        $uid = $SR['UID'];
        $scriptId = "script-desc-" . $uid;
        $readMoreId = "read-more-" . $uid;

        echo '<tr>
            <td>' . $cnt . '</td>
            <td>' . htmlspecialchars($SR['Title']) . '</td>
            <td>
               <div id="' . $scriptId . '" class="author-description" data-full="' . htmlspecialchars($fullScript) . '" data-truncated="' . $truncatedScript . '">' . $truncatedScript . '</div>';

        // Show "Read more" only if content exceeds 100 characters
        if (mb_strlen(strip_tags($SR['Script'])) > 100) {
            echo '<span id="' . $readMoreId . '" class="read-more" onclick="toggleDescription(\'' . $scriptId . '\', \'' . $readMoreId . '\')">Read more</span>';
        }

        echo '</td>
            <td>
                <button type="button" onclick="UpdateProfileScriptRecord(' . $uid . ');" class="btn btn-primary btn-sm" style="border-radius: 4px;">Update</button>
                <button type="button" onclick="DeleteProfileScriptRecord(' . $uid . ');" class="btn btn-danger btn-sm" style="border-radius: 4px;">Delete</button>
            </td>
         </tr>';
        $cnt++;
    }
    ?>
    </tbody>
</table>

<script>
    function toggleDescription(descId, btnId) {
        const description = document.getElementById(descId);
        const button = document.getElementById(btnId);

        const full = description.getAttribute('data-full');
        const truncated = description.getAttribute('data-truncated');

        if (description.classList.contains('expanded')) {
            description.classList.remove('expanded');
            description.textContent = truncated;
            button.textContent = 'Read more';
        } else {
            description.classList.add('expanded');
            description.textContent = full;
            button.textContent = 'Read less';
        }
    }

</script>
