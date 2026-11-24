<div class="modal fade" id="SupportGuideModal" tabindex="-1" role="dialog" aria-labelledby="SupportGuideModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered cs-support-modal" role="document">
        <div class="modal-content">
            <form method="post" action="" name="SupportGuideForm" id="SupportGuideForm" class="needs-validation" novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div class="modal-header">
                    <div>
                        <div class="cs-modal-kicker">ClinTa Support</div>
                        <h5 class="modal-title mb-0" id="SupportGuideModalTitle">Add guide</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close dialog">
                        <i class="ti-close" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="cs-modal-tabs" role="tablist">
                        <a class="cs-modal-tab active" id="tab-audience-link" data-toggle="tab" href="#support-tab-audience" role="tab"
                           aria-controls="support-tab-audience" aria-selected="true">
                            <span class="cs-modal-tab-num">1</span>
                            <span class="cs-modal-tab-copy">
                                <strong>Audience &amp; page</strong>
                                <small>URL, role, video</small>
                            </span>
                        </a>
                        <a class="cs-modal-tab" id="tab-qa-link" data-toggle="tab" href="#support-tab-qa" role="tab"
                           aria-controls="support-tab-qa" aria-selected="false">
                            <span class="cs-modal-tab-num">2</span>
                            <span class="cs-modal-tab-copy">
                                <strong>Question &amp; answer</strong>
                                <small>FAQ content</small>
                            </span>
                        </a>
                    </div>

                    <div class="tab-content cs-modal-tab-content">
                        <div class="tab-pane fade show active" id="support-tab-audience" role="tabpanel" aria-labelledby="tab-audience-link">
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="GuideURL">Relative page URL <span class="cs-required">*</span></label>
                                    <input type="text" class="form-control" id="GuideURL" name="Guide[URL]"
                                           placeholder="/module/opd/patient" required=""
                                           aria-describedby="GuideURLHelp">
                                    <small id="GuideURLHelp" class="cs-field-hint">Path only — hierarchy fallback applies to child pages.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="GuideAccessLevel">Access level <span class="cs-required">*</span></label>
                                    <select class="form-control" id="GuideAccessLevel" name="Guide[AccessLevel]">
                                        <?php foreach (($access_levels ?? []) as $key => $label) { ?>
                                            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="GuideVideoLink">External video URL</label>
                                    <input type="url" class="form-control" id="GuideVideoLink" name="Guide[VideoLink]"
                                           placeholder="https://youtube.com/..."
                                           aria-describedby="GuideVideoLinkHelp">
                                    <small id="GuideVideoLinkHelp" class="cs-field-hint">Optional YouTube, Vimeo, or direct link.</small>
                                </div>
                                <div class="col-md-12 mb-1">
                                    <label for="GuideVideoUpload">Upload video</label>
                                    <div class="cs-upload-box">
                                        <input type="file" class="form-control" id="GuideVideoUpload" name="VideoUpload"
                                               accept=".mp4,.webm,.ogg,.mov,.avi,.mkv,video/*"
                                               aria-describedby="GuideVideoUploadHelp">
                                        <small id="GuideVideoUploadHelp" class="cs-field-hint mb-0">
                                            Optional. mp4, webm, ogg, mov, avi, mkv — max ~50MB.
                                        </small>
                                    </div>
                                    <div id="CurrentVideoWrap" class="mt-2 d-none">
                                        <div id="CurrentVideoLabel" class="mb-2"></div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="ClearVideoBtn" onclick="ClearSupportVideo()">
                                            <i class="ti-trash mr-1" aria-hidden="true"></i> Clear uploaded video
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="support-tab-qa" role="tabpanel" aria-labelledby="tab-qa-link">
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="GuideQuestion">Question <span class="cs-required">*</span></label>
                                    <input type="text" class="form-control" id="GuideQuestion" name="Guide[Question]"
                                           placeholder="How do I register a new OPD patient?" required="">
                                </div>
                                <div class="col-md-12 mb-1">
                                    <label for="GuideAnswer">Answer</label>
                                    <textarea id="GuideAnswer" name="Answer" aria-label="Guide answer"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="SupportAjaxResponse" class="cs-modal-alert" role="status" aria-live="polite"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cs-modal-close" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-save-guide" onclick="SaveSupportGuide()">
                        <i class="ti-check mr-1" aria-hidden="true"></i> Save guide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
