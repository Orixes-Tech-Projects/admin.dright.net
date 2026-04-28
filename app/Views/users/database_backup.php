<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Database Backup</h4>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php } ?>
                <?php if (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php } ?>

                <p class="mb-2">
                    Generate a portable ZIP backup package for another system.
                </p>
                <ul>
                    <li>Includes MySQL and PostgreSQL exports</li>
                    <li>Contains JSON data and SQL import files</li>
                    <li>Ready for download and transfer</li>
                </ul>

                <a href="<?= PATH ?>users/download-database-backup" class="btn btn-primary">
                    <i data-feather="download"></i> Download Database Backup ZIP
                </a>
            </div>
        </div>
    </div>
</div>
