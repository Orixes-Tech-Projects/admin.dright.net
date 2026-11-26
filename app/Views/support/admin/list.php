<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

<style>
    /* ClinTa Support — Medical Clinic + Soft UI Evolution (ui-ux-pro-max) */
    .clinta-support-page {
        --cs-primary: #0891B2;
        --cs-primary-hover: #0E7490;
        --cs-primary-soft: #ECFEFF;
        --cs-primary-ink: #164E63;
        --cs-cta: #16A34A;
        --cs-cta-hover: #15803D;
        --cs-bg: #F0FDFA;
        --cs-surface: #FFFFFF;
        --cs-ink: #134E4A;
        --cs-text: #1E293B;
        --cs-muted: #475569;
        --cs-subtle: #64748B;
        --cs-line: #CCFBF1;
        --cs-line-strong: #99F6E4;
        --cs-border: #E2E8F0;
        --cs-amber: #D97706;
        --cs-amber-soft: #FEF3C7;
        --cs-blue: #0284C7;
        --cs-blue-soft: #E0F2FE;
        --cs-danger: #DC2626;
        --cs-radius: 12px;
        --cs-radius-sm: 8px;
        --cs-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 8px 24px rgba(8, 145, 178, 0.08);
        --cs-shadow-hover: 0 4px 12px rgba(15, 23, 42, 0.06), 0 12px 28px rgba(8, 145, 178, 0.12);
        --cs-space-1: 8px;
        --cs-space-2: 16px;
        --cs-space-3: 24px;
        --cs-space-4: 32px;
        --cs-ease: 220ms ease;
        --cs-font-heading: 'Lexend', 'Source Sans 3', system-ui, sans-serif;
        --cs-font-body: 'Source Sans 3', system-ui, sans-serif;

        font-family: var(--cs-font-body);
        color: var(--cs-text);
        font-size: 16px;
        line-height: 1.5;
        padding-bottom: var(--cs-space-3);
    }

    .clinta-support-page h1,
    .clinta-support-page h2,
    .clinta-support-page h3,
    .clinta-support-page h4,
    .clinta-support-page .tile-value {
        font-family: var(--cs-font-heading);
    }

    .clinta-support-page *:focus-visible {
        outline: 2px solid var(--cs-primary);
        outline-offset: 2px;
    }

    .clinta-support-page .cs-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--cs-space-2);
        flex-wrap: nowrap;
        margin: var(--cs-space-2) 0 var(--cs-space-3);
        padding: 16px 20px;
        border-radius: 16px;
        background:
            linear-gradient(135deg, rgba(8, 145, 178, 0.10) 0%, rgba(240, 253, 250, 0.95) 42%, #fff 100%),
            var(--cs-surface);
        border: 1px solid var(--cs-line);
        box-shadow: var(--cs-shadow);
    }

    .clinta-support-page .cs-hero-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        flex-wrap: nowrap;
    }

    .clinta-support-page .cs-kicker {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 4px 10px;
        border-radius: 999px;
        background: var(--cs-primary-soft);
        color: var(--cs-primary-ink);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .clinta-support-page .cs-kicker i {
        font-size: 12px;
    }

    .clinta-support-page .cs-hero h1 {
        margin: 0;
        font-size: clamp(1.35rem, 2vw, 1.7rem);
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--cs-ink);
        line-height: 1.25;
        white-space: nowrap;
    }

    @media (max-width: 767px) {
        .clinta-support-page .cs-hero {
            flex-wrap: wrap;
        }

        .clinta-support-page .cs-hero-left {
            flex-wrap: wrap;
        }
    }

    .clinta-support-page .btn-cs-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 0 16px;
        border: none;
        border-radius: var(--cs-radius-sm);
        background: var(--cs-cta);
        color: #fff;
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 0 6px 16px rgba(22, 163, 74, 0.28);
        transition: background var(--cs-ease), transform var(--cs-ease), box-shadow var(--cs-ease);
        flex-shrink: 0;
        white-space: nowrap;
    }

    .clinta-support-page .btn-cs-primary:hover {
        background: var(--cs-cta-hover);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(22, 163, 74, 0.32);
    }

    .clinta-support-page .btn-cs-primary:active {
        transform: translateY(0);
    }

    .clinta-support-page .cs-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: var(--cs-space-2);
        margin-bottom: var(--cs-space-3);
    }

    @media (max-width: 991px) {
        .clinta-support-page .cs-stats {
            grid-template-columns: 1fr;
        }
    }

    .clinta-support-page .stats-tile {
        --tile-accent: #0891B2;
        --tile-soft: #ECFEFF;
        --tile-mid: #A5F3FC;
        min-height: 96px;
        height: 100%;
        border: 1px solid transparent;
        border-radius: 18px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        transition: transform var(--cs-ease), box-shadow var(--cs-ease);
        overflow: hidden;
        position: relative;
        isolation: isolate;
    }

    .clinta-support-page .stats-tile.tile-guides {
        --tile-accent: #0891B2;
        --tile-soft: #ECFEFF;
        --tile-mid: #67E8F9;
        background:
            radial-gradient(120px 80px at 100% 0%, rgba(103, 232, 249, 0.55), transparent 70%),
            linear-gradient(135deg, #F0FDFF 0%, #CFFAFE 55%, #A5F3FC 100%);
        border-color: rgba(8, 145, 178, 0.22);
    }

    .clinta-support-page .stats-tile.tile-video {
        --tile-accent: #2563EB;
        --tile-soft: #EFF6FF;
        --tile-mid: #93C5FD;
        background:
            radial-gradient(120px 80px at 100% 0%, rgba(147, 197, 253, 0.55), transparent 70%),
            linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 55%, #BFDBFE 100%);
        border-color: rgba(37, 99, 235, 0.22);
    }

    .clinta-support-page .stats-tile.tile-urls {
        --tile-accent: #D97706;
        --tile-soft: #FFFBEB;
        --tile-mid: #FCD34D;
        background:
            radial-gradient(120px 80px at 100% 0%, rgba(252, 211, 77, 0.55), transparent 70%),
            linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 55%, #FDE68A 100%);
        border-color: rgba(217, 119, 6, 0.22);
    }

    .clinta-support-page .stats-tile::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: linear-gradient(180deg, var(--tile-accent), var(--tile-mid));
        border-radius: 18px 0 0 18px;
    }

    .clinta-support-page .stats-tile::after {
        content: '';
        position: absolute;
        right: -24px;
        bottom: -28px;
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: radial-gradient(circle, var(--tile-mid) 0%, transparent 70%);
        opacity: 0.45;
        z-index: 0;
        pointer-events: none;
    }

    .clinta-support-page .stats-tile:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.12);
    }

    .clinta-support-page .stats-tile .card-body {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 96px;
        padding: 18px 18px 18px 22px;
        background: transparent;
    }

    .clinta-support-page .tile-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        width: 100%;
        min-width: 0;
    }

    .clinta-support-page .tile-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.25rem;
        color: #fff;
        background: linear-gradient(145deg, var(--tile-accent) 0%, #0f172a 160%);
        box-shadow:
            0 8px 18px rgba(15, 23, 42, 0.18),
            inset 0 1px 0 rgba(255, 255, 255, 0.35);
    }

    .clinta-support-page .tile-guides .tile-icon {
        background: linear-gradient(145deg, #22D3EE 0%, #0891B2 100%);
        box-shadow: 0 8px 18px rgba(8, 145, 178, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.35);
    }

    .clinta-support-page .tile-video .tile-icon {
        background: linear-gradient(145deg, #60A5FA 0%, #2563EB 100%);
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.35);
    }

    .clinta-support-page .tile-urls .tile-icon {
        background: linear-gradient(145deg, #FBBF24 0%, #D97706 100%);
        box-shadow: 0 8px 18px rgba(217, 119, 6, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.35);
    }

    .clinta-support-page .tile-label {
        margin: 0;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #475569;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .clinta-support-page .tile-value {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
        color: var(--tile-accent);
        line-height: 1;
        flex-shrink: 0;
        letter-spacing: -0.03em;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.65);
    }

    .clinta-support-page .cs-panel {
        background: var(--cs-surface);
        border: 1px solid var(--cs-border);
        border-radius: 16px;
        box-shadow: var(--cs-shadow);
        overflow: hidden;
    }

    .clinta-support-page .cs-panel-head {
        padding: 0;
        border-bottom: 1px solid var(--cs-border);
        background: linear-gradient(180deg, #FFFFFF 0%, var(--cs-bg) 100%);
    }

    .clinta-support-page .cs-accordion-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        width: 100%;
        margin: 0;
        padding: 16px 20px;
        border: none;
        background: transparent;
        text-align: left;
        cursor: pointer;
        color: inherit;
        transition: background var(--cs-ease);
    }

    .clinta-support-page .cs-accordion-toggle:hover {
        background: rgba(8, 145, 178, 0.04);
    }

    .clinta-support-page .cs-accordion-toggle:focus-visible {
        outline: 2px solid var(--cs-primary);
        outline-offset: -2px;
    }

    .clinta-support-page .cs-panel-head h2 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--cs-ink);
    }

    .clinta-support-page .cs-accordion-chevron {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: var(--cs-primary-soft);
        color: var(--cs-primary-ink);
        transition: transform var(--cs-ease);
        flex-shrink: 0;
    }

    .clinta-support-page .cs-accordion-toggle[aria-expanded="true"] .cs-accordion-chevron {
        transform: rotate(180deg);
    }

    .clinta-support-page .cs-workflow {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 0 20px 16px;
    }

    .clinta-support-page .cs-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 32px;
        padding: 4px 12px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid var(--cs-line-strong);
        color: var(--cs-muted);
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .clinta-support-page .cs-chip span {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--cs-primary);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .clinta-support-page .cs-filters {
        padding: 16px 20px;
        border-bottom: 1px solid var(--cs-border);
        background: #fff;
    }

    .clinta-support-page .cs-filters label {
        display: block;
        margin-bottom: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--cs-muted);
    }

    .clinta-support-page .cs-filters .form-control {
        min-height: 44px;
        border-radius: var(--cs-radius-sm);
        border-color: #CBD5E1;
        font-size: 0.95rem;
        transition: border-color var(--cs-ease), box-shadow var(--cs-ease);
    }

    .clinta-support-page .cs-filters .form-control:focus {
        border-color: var(--cs-primary);
        box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.18);
    }

    .clinta-support-page .cs-filter-actions {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    .clinta-support-page .btn-cs-apply,
    .clinta-support-page .btn-cs-clear {
        min-height: 44px;
        flex: 1;
        border-radius: var(--cs-radius-sm);
        font-weight: 600;
        transition: background var(--cs-ease), border-color var(--cs-ease), color var(--cs-ease);
    }

    .clinta-support-page .btn-cs-apply {
        border: 1px solid var(--cs-primary);
        background: var(--cs-primary);
        color: #fff;
    }

    .clinta-support-page .btn-cs-apply:hover {
        background: var(--cs-primary-hover);
        border-color: var(--cs-primary-hover);
        color: #fff;
    }

    .clinta-support-page .btn-cs-clear {
        border: 1px solid #CBD5E1;
        background: #fff;
        color: var(--cs-text);
    }

    .clinta-support-page .btn-cs-clear:hover {
        background: #FEF2F2;
        border-color: #FECACA;
        color: var(--cs-danger);
    }

    .clinta-support-page .cs-table-wrap {
        padding: 16px 20px 20px;
        background: #fff;
    }

    .clinta-support-page #record {
        width: 100% !important;
        margin-bottom: 0;
        border: 1px solid #dee2e6;
        background: #fff;
    }

    .clinta-support-page #record thead th {
        background: #fff !important;
        color: #6c757d !important;
        border: 1px solid #dee2e6 !important;
        border-bottom-width: 2px !important;
        font-size: 0.72rem !important;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        padding: 12px 14px !important;
        white-space: nowrap;
        vertical-align: middle;
    }

    .clinta-support-page #record tbody td {
        vertical-align: middle !important;
        padding: 12px 14px !important;
        border: 1px solid #dee2e6 !important;
        color: #212529;
        font-size: 0.9rem;
        background: #fff;
    }

    .clinta-support-page #record.table-striped tbody tr:nth-of-type(odd) td {
        background-color: #f8f9fa;
    }

    .clinta-support-page #record tbody tr:hover td {
        background-color: #eef2f7 !important;
    }

    .clinta-support-page #record tfoot { display: none; }

    .clinta-support-page .cs-url {
        color: #0f766e;
        font-family: Consolas, Monaco, 'Courier New', monospace;
        font-size: 0.85rem;
        font-weight: 600;
        word-break: break-all;
    }

    .clinta-support-page .cs-badge {
        display: inline-block;
        min-width: 64px;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        text-align: center;
        color: #fff;
        white-space: nowrap;
    }

    .clinta-support-page .cs-badge-all {
        background: #16a34a;
        color: #fff;
    }

    .clinta-support-page .cs-badge-role {
        background: #0f766e;
        color: #fff;
    }

    .clinta-support-page a.cs-badge-video,
    .clinta-support-page .cs-badge-video {
        background: #ea580c !important;
        color: #fff !important;
        text-decoration: none !important;
    }

    .clinta-support-page a.cs-badge-video:hover,
    .clinta-support-page .cs-badge-video:hover {
        background: #c2410c !important;
        color: #fff !important;
        text-decoration: none !important;
    }

    .clinta-support-page a.cs-badge-link,
    .clinta-support-page .cs-badge-link {
        background: #0e7490 !important;
        color: #fff !important;
        text-decoration: none !important;
    }

    .clinta-support-page a.cs-badge-link:hover,
    .clinta-support-page .cs-badge-link:hover {
        background: #155e75 !important;
        color: #fff !important;
        text-decoration: none !important;
    }

    .clinta-support-page .cs-badge-none {
        background: #94a3b8;
        color: #fff;
    }

    .clinta-support-page .cs-badge-status-active {
        background: #16a34a;
        color: #fff;
    }

    .clinta-support-page .cs-badge-status-lock {
        background: #dc2626;
        color: #fff;
    }

    .clinta-support-page .cs-status-select {
        min-width: 100px;
        max-width: 120px;
        height: 34px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid var(--cs-border);
        cursor: pointer;
    }

    .clinta-support-page .cs-status-select.cs-status-active {
        background: #ecfdf5;
        color: #15803d;
        border-color: #86efac;
    }

    .clinta-support-page .cs-status-select.cs-status-lock {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fca5a5;
    }

    .clinta-support-page .cs-question {
        font-weight: 700;
        color: #212529;
        max-width: 320px;
        line-height: 1.4;
    }

    .clinta-support-page .btn-cs-action {
        background: #1b2e4b;
        border-color: #1b2e4b;
        color: #fff;
        font-weight: 600;
        border-radius: 4px;
        padding: 0.35rem 0.75rem;
    }

    .clinta-support-page .btn-cs-action:hover,
    .clinta-support-page .btn-cs-action:focus {
        background: #15253d;
        border-color: #15253d;
        color: #fff;
    }

    .clinta-support-page .dataTables_wrapper .dataTables_length,
    .clinta-support-page .dataTables_wrapper .dataTables_info {
        color: #6c757d;
        font-size: 0.875rem;
        padding-top: 0.75rem;
    }

    .clinta-support-page .dataTables_wrapper .dataTables_paginate {
        padding-top: 0.75rem;
    }

    .clinta-support-page .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 4px !important;
        margin: 0 2px;
    }

    .clinta-support-page .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .clinta-support-page .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #1b2e4b !important;
        border-color: #1b2e4b !important;
        color: #fff !important;
    }

    .clinta-support-page .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef !important;
        border-color: #dee2e6 !important;
        color: #212529 !important;
    }

    #SupportGuideModal .cs-support-modal {
        width: 50%;
        max-width: 50%;
        margin: 1.75rem auto;
    }

    @media (max-width: 991px) {
        #SupportGuideModal .cs-support-modal {
            width: 92%;
            max-width: 92%;
        }
    }

    #SupportGuideModal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.28);
        font-family: var(--cs-font-body);
        background: #fff;
    }

    #SupportGuideModal .modal-header {
        background: linear-gradient(135deg, #0E7490 0%, #0891B2 55%, #06B6D4 100%);
        color: #fff;
        border: none;
        padding: 18px 22px;
        align-items: center;
    }

    #SupportGuideModal .cs-modal-kicker {
        font-size: 0.7rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        opacity: 0.9;
        margin-bottom: 0.2rem;
        font-weight: 700;
    }

    #SupportGuideModal .modal-header .modal-title {
        font-family: var(--cs-font-heading);
        font-weight: 700;
        font-size: 1.25rem;
    }

    #SupportGuideModal .modal-header .close {
        color: #fff;
        opacity: 0.95;
        text-shadow: none;
        min-width: 40px;
        min-height: 40px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.14);
        margin: 0;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    #SupportGuideModal .modal-header .close:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.24);
    }

    #SupportGuideModal .modal-body {
        padding: 18px 22px 10px;
        background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 28%);
    }

    #SupportGuideModal .cs-modal-tabs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 18px;
        padding: 6px;
        border-radius: 14px;
        background: #F1F5F9;
        border: 1px solid #E2E8F0;
    }

    #SupportGuideModal .cs-modal-tab {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 62px;
        padding: 10px 12px;
        border-radius: 10px;
        text-decoration: none !important;
        color: #64748B;
        border: 1px solid transparent;
        transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    #SupportGuideModal .cs-modal-tab:hover {
        color: #0F766E;
        background: rgba(255, 255, 255, 0.7);
    }

    #SupportGuideModal .cs-modal-tab.active {
        background: #fff;
        color: #0F766E;
        border-color: #99F6E4;
        box-shadow: 0 8px 18px rgba(8, 145, 178, 0.12);
    }

    #SupportGuideModal .cs-modal-tab-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.78rem;
        font-weight: 700;
        background: #E2E8F0;
        color: #475569;
    }

    #SupportGuideModal .cs-modal-tab.active .cs-modal-tab-num {
        background: linear-gradient(145deg, #22D3EE 0%, #0891B2 100%);
        color: #fff;
        box-shadow: 0 4px 10px rgba(8, 145, 178, 0.35);
    }

    #SupportGuideModal .cs-modal-tab-copy {
        display: flex;
        flex-direction: column;
        min-width: 0;
        line-height: 1.2;
    }

    #SupportGuideModal .cs-modal-tab-copy strong {
        font-size: 0.9rem;
        font-weight: 700;
    }

    #SupportGuideModal .cs-modal-tab-copy small {
        margin-top: 2px;
        font-size: 0.72rem;
        color: #94A3B8;
    }

    #SupportGuideModal .cs-modal-tab.active .cs-modal-tab-copy small {
        color: #64748B;
    }

    #SupportGuideModal .cs-modal-tab-content {
        padding-top: 2px;
    }

    #SupportGuideModal .form-control {
        min-height: 44px;
        border-radius: 10px;
        border: 1px solid #D0D7E2;
        background: #fff;
        box-shadow: none;
    }

    #SupportGuideModal .form-control:focus {
        border-color: #0891B2;
        box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.16);
        background: #fff;
    }

    #SupportGuideModal label {
        font-weight: 700;
        color: #334155;
        font-size: 0.84rem;
        margin-bottom: 6px;
    }

    #SupportGuideModal .cs-required {
        color: #DC2626;
        font-weight: 800;
        margin-left: 2px;
    }

    #SupportGuideModal .cs-field-hint {
        display: block;
        margin-top: 6px;
        color: #94A3B8;
        font-size: 0.78rem;
        line-height: 1.4;
    }

    #SupportGuideModal .cs-upload-box {
        padding: 12px;
        border-radius: 12px;
        background: #F8FAFC;
        border: 1px dashed #CBD5E1;
    }

    #SupportGuideModal .cs-modal-alert:empty {
        display: none;
    }

    #SupportGuideModal .cs-modal-alert .alert {
        margin: 14px 0 4px;
        border-radius: 10px;
    }

    #SupportGuideModal .modal-footer {
        border-top: 1px solid #E2E8F0;
        background: #F8FAFC;
        gap: 10px;
        padding: 14px 22px;
    }

    #SupportGuideModal .btn-cs-modal-close {
        min-height: 42px;
        border-radius: 10px;
        border: 1px solid #CBD5E1;
        background: #fff;
        color: #475569;
        font-weight: 600;
        padding: 0 16px;
    }

    #SupportGuideModal .btn-cs-modal-close:hover {
        background: #F1F5F9;
        color: #0F172A;
    }

    #SupportGuideModal .btn-save-guide {
        min-height: 42px;
        background: linear-gradient(135deg, #16A34A 0%, #15803D 100%);
        border: none;
        color: #fff !important;
        font-weight: 700;
        border-radius: 10px;
        padding: 0 18px;
        box-shadow: 0 8px 18px rgba(22, 163, 74, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    #SupportGuideModal .btn-save-guide:hover,
    #SupportGuideModal .btn-save-guide:focus,
    #SupportGuideModal .btn-save-guide:active {
        color: #fff !important;
        filter: brightness(1.03);
        box-shadow: 0 10px 22px rgba(22, 163, 74, 0.34);
    }

    #CurrentVideoWrap {
        padding: 12px 14px;
        border-radius: 10px;
        background: #ECFEFF;
        border: 1px dashed #99F6E4;
    }

    @media (prefers-reduced-motion: reduce) {
        .clinta-support-page *,
        .clinta-support-page *::before,
        .clinta-support-page *::after,
        #SupportGuideModal *,
        #SupportGuideModal *::before,
        #SupportGuideModal *::after {
            animation: none !important;
            transition: none !important;
            scroll-behavior: auto !important;
        }
    }
</style>

<div class="clinta-support-page">
    <section class="cs-hero" aria-labelledby="clinta-support-title">
        <div class="cs-hero-left">
            <div class="cs-kicker">
                <i class="ti-help-alt" aria-hidden="true"></i>
                Extended knowledge base
            </div>
            <h1 id="clinta-support-title">ClinTa Support Bot</h1>
        </div>
        <?php if (!empty($can_add)) { ?>
            <button type="button" onclick="AddSupportGuide()" class="btn btn-cs-primary" aria-label="Add new support guide">
                <i class="ti-plus" aria-hidden="true"></i>
                Add guide
            </button>
        <?php } ?>
    </section>

    <section class="cs-stats" aria-label="Support guide statistics">
        <article class="stats-tile tile-guides">
            <div class="card-body">
                <div class="tile-icon" aria-hidden="true"><i class="ti-book"></i></div>
                <div class="tile-meta">
                    <p class="tile-label">Active guides</p>
                    <p class="tile-value"><?= (int) ($stats['active'] ?? 0) ?></p>
                </div>
            </div>
        </article>
        <article class="stats-tile tile-video">
            <div class="card-body">
                <div class="tile-icon" aria-hidden="true"><i class="ti-video-camera"></i></div>
                <div class="tile-meta">
                    <p class="tile-label">With video</p>
                    <p class="tile-value"><?= (int) ($stats['with_video'] ?? 0) ?></p>
                </div>
            </div>
        </article>
        <article class="stats-tile tile-urls">
            <div class="card-body">
                <div class="tile-icon" aria-hidden="true"><i class="ti-link"></i></div>
                <div class="tile-meta">
                    <p class="tile-label">Unique page URLs</p>
                    <p class="tile-value"><?= (int) ($stats['unique_urls'] ?? 0) ?></p>
                </div>
            </div>
        </article>
    </section>

    <section class="cs-panel" aria-labelledby="guides-library-title">
        <div class="cs-panel-head">
            <button type="button"
                    class="cs-accordion-toggle"
                    data-toggle="collapse"
                    data-target="#guidesLibraryPanel"
                    aria-expanded="false"
                    aria-controls="guidesLibraryPanel"
                    id="guides-library-title">
                <h2>Filter</h2>
                <span class="cs-accordion-chevron" aria-hidden="true">
                    <i class="ti-angle-down"></i>
                </span>
            </button>
            <div id="guidesLibraryPanel" class="collapse">
                <div class="cs-filters">
                    <form id="SupportFilterForm" onsubmit="ReloadSupportTable(); return false;" aria-label="Filter guides">
                        <div class="form-row align-items-end">
                            <div class="col-lg-3 col-md-6 mb-2">
                                <label for="FilterKeyword">Search</label>
                                <input type="search" class="form-control" id="FilterKeyword" name="keyword"
                                       placeholder="Question, answer, or URL" autocomplete="off">
                            </div>
                            <div class="col-lg-3 col-md-6 mb-2">
                                <label for="FilterAccess">Access level</label>
                                <select class="form-control" id="FilterAccess" name="access">
                                    <option value="">All access levels</option>
                                    <?php foreach (($access_levels ?? []) as $key => $label) { ?>
                                        <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-2">
                                <label for="FilterURL">URL path</label>
                                <input type="text" class="form-control" id="FilterURL" name="url"
                                       placeholder="/module/opd/patient" autocomplete="off">
                            </div>
                            <div class="col-lg-2 col-md-6 mb-2">
                                <label for="FilterStatus">Status</label>
                                <select class="form-control" id="FilterStatus" name="status">
                                    <option value="">All statuses</option>
                                    <option value="active">Published</option>
                                    <option value="lock">Pending</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-2">
                                <label class="d-none d-lg-block">&nbsp;</label>
                                <div class="cs-filter-actions">
                                    <button type="submit" class="btn btn-cs-apply">
                                        <i class="ti-filter mr-1" aria-hidden="true"></i> Apply
                                    </button>
                                    <button type="button" class="btn btn-cs-clear" onclick="ClearSupportFilters()">
                                        <i class="ti-close mr-1" aria-hidden="true"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="cs-table-wrap">
            <div class="table-responsive">
                <table id="record" class="table table-striped table-bordered" aria-describedby="guides-library-title">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">URL</th>
                        <th scope="col">Access</th>
                        <th scope="col">Question</th>
                        <th scope="col">Video</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>URL</th>
                        <th>Access</th>
                        <th>Question</th>
                        <th>Video</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-2" id="Response" role="status" aria-live="polite"></div>
        </div>
    </section>
</div>

<?php echo view('support/admin/modal/form', ['access_levels' => $access_levels ?? []]); ?>

<script>
    var supportTable = null;

    $(document).ready(function () {
        $('#GuideAnswer').summernote({
            height: 240,
            placeholder: 'Write clear step-by-step help for this screen...'
        });

        supportTable = $('#record').DataTable({
            "searching": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
            "pageLength": 25,
            "autoWidth": true,
            "order": [],
            "columnDefs": [
                { "orderable": false, "targets": [0, 5, 6] }
            ],
            "language": {
                "emptyTable": "No guides yet. Click Add guide to create the first one.",
                "zeroRecords": "No guides match your filters.",
                "processing": "Loading guides..."
            },
            "ajax": {
                "url": "<?= $path ?>support/datatable",
                "type": "POST",
                "data": function (d) {
                    d.keyword = $('#FilterKeyword').val();
                    d.access = $('#FilterAccess').val();
                    d.url = $('#FilterURL').val();
                    d.status = $('#FilterStatus').val();
                }
            }
        });

        $('#FilterAccess, #FilterStatus').on('change', function () {
            ReloadSupportTable();
        });
    });

    function ReloadSupportTable() {
        if (supportTable) {
            supportTable.ajax.reload(null, false);
        }
    }

    function ClearSupportFilters() {
        $('#FilterKeyword').val('');
        $('#FilterAccess').val('');
        $('#FilterURL').val('');
        $('#FilterStatus').val('');
        ReloadSupportTable();
    }

    function ChangeSupportStatus(id, selectEl) {
        var $select = $(selectEl);
        var status = $select.val();
        var previous = $select.attr('data-prev') || 'active';
        var response = AjaxResponse("support/update_status", "id=" + id + "&status=" + encodeURIComponent(status));
        if (response && response.status === 'success') {
            $select.removeClass('cs-status-active cs-status-lock').addClass('cs-status-' + status);
            $select.attr('data-prev', status);
            $("#Response").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"><strong>Success!</strong> ' + response.message + '</div>');
        } else {
            $select.val(previous);
            $("#Response").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"><strong>Error!</strong> ' + ((response && response.message) ? response.message : 'Could not update status') + '</div>');
        }
    }

    function resetSupportGuideForm() {
        $('#SupportGuideForm')[0].reset();
        $('#SupportGuideForm #UID').val(0);
        $('#SupportGuideModalTitle').text('Add guide');
        $('#GuideAccessLevel').val('ALL');
        $('#GuideAnswer').summernote('code', '');
        $('#CurrentVideoWrap').addClass('d-none');
        $('#CurrentVideoLabel').html('');
        $('#SupportAjaxResponse').html('');
        $('#support-tab-audience').addClass('show active');
        $('#support-tab-qa').removeClass('show active');
        $('#SupportGuideModal .cs-modal-tab').removeClass('active').attr('aria-selected', 'false');
        $('#tab-audience-link').addClass('active').attr('aria-selected', 'true');
        $('#tab-audience-link').tab('show');
    }

    $(document).on('shown.bs.tab', '#SupportGuideModal .cs-modal-tab', function (e) {
        $('#SupportGuideModal .cs-modal-tab').removeClass('active').attr('aria-selected', 'false');
        $(e.target).addClass('active').attr('aria-selected', 'true');
    });

    function AddSupportGuide() {
        resetSupportGuideForm();
        $('#SupportGuideModal').modal('show');
    }

    function EditSupportGuide(id) {
        resetSupportGuideForm();
        var Items = AjaxResponse("support/get_by_uid", "id=" + id);
        if (!Items || Items.status !== 'success') {
            $("#Response").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"><strong>Error!</strong> ' + (Items && Items.message ? Items.message : 'Could not load guide') + '</div>');
            return;
        }

        $('#SupportGuideModalTitle').text('Edit guide');
        $('#SupportGuideForm #UID').val(Items.record.UID);
        $('#GuideURL').val(Items.record.URL);
        $('#GuideAccessLevel').val(Items.record.AccessLevel);
        $('#GuideVideoLink').val(Items.record.VideoLink || '');
        $('#GuideQuestion').val(Items.record.Question);
        $('#GuideAnswer').summernote('code', Items.record.Answer || '');

        if (Items.record.VideoUpload) {
            var label = '<strong>Current upload:</strong> ' + Items.record.VideoUpload;
            if (Items.record.VideoUrl) {
                label += ' — <a href="' + Items.record.VideoUrl + '" target="_blank" rel="noopener">Open video</a>';
            }
            $('#CurrentVideoLabel').html(label);
            $('#CurrentVideoWrap').removeClass('d-none');
        }

        $('#SupportGuideModal').modal('show');
    }

    function SaveSupportGuide() {
        var formdata = new window.FormData($("form#SupportGuideForm")[0]);
        formdata.append('Answer', $('#GuideAnswer').summernote('code'));
        var response = AjaxUploadResponse("support/form_submit", formdata);
        if (response.status === 'success') {
            $("#SupportAjaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"><strong>Success!</strong> ' + response.message + '</div>');
            setTimeout(function () {
                location.reload();
            }, 500);
        } else {
            $("#SupportAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"><strong>Error!</strong> ' + (response.message || 'Save failed') + '</div>');
        }
    }

    function DeleteSupportGuide(id) {
        if (!confirm("Are you sure you want to delete this guide?")) {
            return;
        }
        var response = AjaxResponse("support/delete", "id=" + id);
        if (response.status == 'success') {
            $("#Response").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"><strong>Deleted Successfully!</strong></div>');
            setTimeout(function () {
                location.reload();
            }, 800);
        } else {
            $("#Response").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"><strong>Error!</strong> ' + (response.message || 'Not deleted') + '</div>');
        }
    }

    function ClearSupportVideo() {
        var id = parseInt($('#SupportGuideForm #UID').val(), 10) || 0;
        if (!id) {
            return;
        }
        if (!confirm("Clear the uploaded video for this guide?")) {
            return;
        }
        var response = AjaxResponse("support/clear_video", "id=" + id);
        if (response.status === 'success') {
            $('#CurrentVideoWrap').addClass('d-none');
            $('#CurrentVideoLabel').html('');
            $("#SupportAjaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"><strong>Success!</strong> ' + response.message + '</div>');
        } else {
            $("#SupportAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"><strong>Error!</strong> ' + (response.message || 'Could not clear video') + '</div>');
        }
    }
</script>

<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
