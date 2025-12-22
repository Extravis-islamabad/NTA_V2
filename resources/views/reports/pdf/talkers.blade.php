<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Top Talkers Report</title>
    <style>
        @page {
            margin: 0.75in 0.75in 0.75in 1in;
        }
        @page :first {
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #374151;
            background: #ffffff;
        }

        /* ==================== COVER PAGE ==================== */
        .cover-page {
            width: 100%;
            height: 100%;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #1a1a2e 100%);
            position: relative;
            page-break-after: always;
        }
        .cover-accent-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #22d3ee, #8b5cf6, #22d3ee);
        }
        .cover-content {
            padding: 80px 60px;
        }
        .cover-logo-section {
            text-align: center;
            margin-bottom: 60px;
        }
        .cover-logo-img {
            height: 60px;
            width: auto;
        }
        .cover-logo-text {
            font-size: 48px;
            font-weight: bold;
            color: #22d3ee;
            letter-spacing: 6px;
        }
        .cover-tagline {
            font-size: 12px;
            color: #94a3b8;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .cover-divider {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #22d3ee, #8b5cf6);
            margin: 50px auto;
        }
        .cover-title-section {
            text-align: center;
            margin-bottom: 50px;
        }
        .cover-report-type {
            font-size: 11px;
            color: #22d3ee;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 15px;
        }
        .cover-title {
            font-size: 36px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }
        .cover-subtitle {
            font-size: 14px;
            color: #94a3b8;
            font-weight: 300;
        }
        .cover-meta-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 25px 35px;
            margin: 50px auto;
            max-width: 420px;
        }
        .cover-meta-row {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .cover-meta-row:last-child {
            border-bottom: none;
        }
        .cover-meta-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }
        .cover-meta-value {
            font-size: 13px;
            color: #f1f5f9;
            font-weight: 600;
        }
        .cover-footer {
            position: absolute;
            bottom: 50px;
            left: 0;
            right: 0;
            text-align: center;
        }
        .cover-confidential {
            font-size: 9px;
            color: #ef4444;
            text-transform: uppercase;
            letter-spacing: 3px;
            padding: 10px 25px;
            border: 1px solid rgba(239,68,68,0.5);
            display: inline-block;
            border-radius: 4px;
        }

        /* ==================== PAGE HEADER ==================== */
        .page-header {
            background: #1a1a2e;
            padding: 12px 20px;
            margin: -54px -54px 20px -72px;
            width: calc(100% + 126px);
            border-bottom: 2px solid #22d3ee;
        }
        .header-table {
            width: 100%;
        }
        .header-logo {
            font-size: 16px;
            font-weight: bold;
            color: #22d3ee;
            letter-spacing: 2px;
        }
        .header-logo-img {
            height: 24px;
            width: auto;
        }
        .header-title {
            font-size: 11px;
            color: #ffffff;
            font-weight: 600;
            text-align: center;
        }
        .header-date {
            font-size: 9px;
            color: #94a3b8;
            text-align: right;
        }

        /* ==================== CONTENT AREA ==================== */
        .content {
            padding: 0;
        }
        .section {
            margin-bottom: 22px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 8px;
            margin-bottom: 15px;
            border-bottom: 2px solid #22d3ee;
        }

        /* ==================== EXECUTIVE SUMMARY CARDS ==================== */
        .summary-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 18px 12px;
            text-align: center;
        }
        .summary-card-cyan { border-top: 4px solid #22d3ee; }
        .summary-card-emerald { border-top: 4px solid #10b981; }
        .summary-card-purple { border-top: 4px solid #8b5cf6; }
        .summary-card-amber { border-top: 4px solid #f59e0b; }
        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
        }
        .summary-value-cyan { color: #0891b2; }
        .summary-value-emerald { color: #059669; }
        .summary-value-purple { color: #7c3aed; }
        .summary-value-amber { color: #d97706; }
        .summary-label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ==================== KEY INSIGHTS ==================== */
        .insights-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #bae6fd;
            border-left: 4px solid #0891b2;
            border-radius: 6px;
            padding: 18px 20px;
            margin-bottom: 20px;
        }
        .insights-title {
            font-size: 11px;
            font-weight: bold;
            color: #0c4a6e;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .insight-item {
            font-size: 10px;
            color: #334155;
            padding: 5px 0;
            padding-left: 18px;
            position: relative;
            line-height: 1.5;
        }
        .insight-item:before {
            content: "•";
            color: #0891b2;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .insight-highlight {
            font-weight: bold;
            color: #0891b2;
        }

        /* ==================== BAR CHARTS ==================== */
        .chart-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .chart-heading {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .chart-table {
            width: 100%;
            border-collapse: collapse;
        }
        .chart-table td {
            padding: 5px 0;
            vertical-align: middle;
        }
        .chart-rank {
            width: 22px;
            height: 22px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            line-height: 22px;
            border-radius: 50%;
            color: white;
        }
        .chart-rank-1 { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #78350f; }
        .chart-rank-2 { background: linear-gradient(135deg, #d1d5db, #9ca3af); color: #374151; }
        .chart-rank-3 { background: linear-gradient(135deg, #fb923c, #ea580c); color: #ffffff; }
        .chart-rank-default { background: #6366f1; color: white; }
        .chart-rank-gray { background: #9ca3af; color: white; }
        .chart-label-cell {
            width: 100px;
            font-size: 8px;
            color: #374151;
            padding: 0 10px;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .chart-bar-cell {
            width: 130px;
        }
        .chart-bar-bg {
            background: #e2e8f0;
            height: 16px;
            border-radius: 3px;
            overflow: hidden;
        }
        .chart-bar-fill {
            background: linear-gradient(90deg, #22d3ee, #0891b2);
            height: 16px;
        }
        .chart-bar-fill-green {
            background: linear-gradient(90deg, #34d399, #10b981);
        }
        .chart-value-cell {
            width: 60px;
            font-size: 9px;
            color: #111827;
            font-weight: bold;
            text-align: right;
            padding-left: 10px;
        }

        /* ==================== DATA TABLES ==================== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            border-radius: 6px;
            overflow: hidden;
        }
        .data-table th {
            background: #1a1a2e;
            color: white;
            padding: 11px 10px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table th.text-right { text-align: right; }
        .data-table th.text-center { text-align: center; }
        .data-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .data-table tr:nth-child(odd) td {
            background: #ffffff;
        }
        .data-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* ==================== RANK BADGES (Gold/Silver/Bronze) ==================== */
        .rank-badge {
            display: inline-block;
            width: 22px;
            height: 22px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            line-height: 22px;
            border-radius: 50%;
        }
        .rank-gold {
            background: linear-gradient(135deg, #fcd34d, #fbbf24);
            color: #78350f;
            box-shadow: 0 1px 2px rgba(251,191,36,0.4);
        }
        .rank-silver {
            background: linear-gradient(135deg, #e5e7eb, #d1d5db);
            color: #374151;
            box-shadow: 0 1px 2px rgba(156,163,175,0.4);
        }
        .rank-bronze {
            background: linear-gradient(135deg, #fdba74, #fb923c);
            color: #ffffff;
            box-shadow: 0 1px 2px rgba(251,146,60,0.4);
        }
        .rank-purple {
            background: linear-gradient(135deg, #a78bfa, #8b5cf6);
            color: #ffffff;
        }
        .rank-gray {
            background: #9ca3af;
            color: #ffffff;
        }

        /* ==================== PROGRESS BAR IN TABLES ==================== */
        .progress-bg {
            background: #e2e8f0;
            height: 8px;
            width: 55px;
            display: inline-block;
            vertical-align: middle;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            background: linear-gradient(90deg, #22d3ee, #0891b2);
            height: 8px;
        }
        .progress-fill-green {
            background: linear-gradient(90deg, #34d399, #10b981);
        }
        .progress-text {
            font-size: 9px;
            font-weight: bold;
            color: #111827;
            margin-left: 6px;
        }

        /* ==================== IP ADDRESS STYLING ==================== */
        .ip-code {
            font-family: 'DejaVu Sans Mono', Consolas, monospace;
            background: #f1f5f9;
            padding: 3px 6px;
            font-size: 8px;
            color: #0891b2;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
        }

        /* ==================== PAGE FOOTER ==================== */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #1a1a2e;
            padding: 10px 25px;
            border-top: 1px solid #22d3ee;
        }
        .footer-table {
            width: 100%;
        }
        .footer-brand {
            font-size: 8px;
            color: #94a3b8;
        }
        .footer-brand strong {
            color: #22d3ee;
        }
        .footer-center {
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }
        .footer-right {
            font-size: 7px;
            color: #64748b;
            text-align: right;
        }
        .footer-confidential {
            font-size: 7px;
            color: #64748b;
            text-align: center;
            margin-top: 3px;
        }

        .page-break {
            page-break-before: always;
        }

        /* Improved table formatting for better PDF rendering */
        table {
            width: 100%;
            table-layout: fixed;
        }

        /* Prevent tables from breaking across pages awkwardly */
        .data-table tr {
            page-break-inside: avoid;
        }

        .data-table th,
        .data-table td {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Ensure content doesn't overflow */
        .section {
            page-break-inside: avoid;
        }

        /* Better spacing for readability */
        .content {
            padding: 0 5px;
        }

        /* Fix chart container overflow */
        .chart-container {
            overflow: hidden;
        }

        /* Improved IP address display */
        .ip-code {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 140px;
            display: inline-block;
        }

        /* Ensure summary cards don't break */
        .summary-grid tr {
            page-break-inside: avoid;
        }

        /* Fix insights box margin */
        .insights-box {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    @php
        // Logo handling
        $logoPath = public_path('images/logo.png');
        $logoExists = file_exists($logoPath);
        $logoBase64 = $logoExists ? base64_encode(file_get_contents($logoPath)) : '';

        // Format total bytes
        if ($totalBytes >= 1099511627776) {
            $totalBytesFormatted = round($totalBytes / 1099511627776, 2) . ' TB';
        } elseif ($totalBytes >= 1073741824) {
            $totalBytesFormatted = round($totalBytes / 1073741824, 2) . ' GB';
        } elseif ($totalBytes >= 1048576) {
            $totalBytesFormatted = round($totalBytes / 1048576, 2) . ' MB';
        } else {
            $totalBytesFormatted = round($totalBytes / 1024, 2) . ' KB';
        }

        // Get top items for insights
        $topSource = $topSources->first();
        $topDest = $topDestinations->first();
        $topConv = $topConversations->first();
    @endphp

    <!-- ==================== COVER PAGE ==================== -->
    <div class="cover-page">
        <div class="cover-accent-bar"></div>

        <div class="cover-content">
            <!-- Logo Section -->
            <div class="cover-logo-section">
                @if($logoExists)
                    <img src="data:image/png;base64,{{ $logoBase64 }}" class="cover-logo-img" alt="MonetX">
                @else
                    <div class="cover-logo-text">MonetX</div>
                @endif
                <div class="cover-tagline">Network Traffic Analyzer</div>
            </div>

            <div class="cover-divider"></div>

            <!-- Title Section -->
            <div class="cover-title-section">
                <div class="cover-report-type">Network Analysis Report</div>
                <div class="cover-title">Top Talkers</div>
                <div class="cover-subtitle">Endpoint Traffic Analysis & Top Bandwidth Consumers</div>
            </div>

            <!-- Metadata Card -->
            <div class="cover-meta-card">
                <div class="cover-meta-row">
                    <div class="cover-meta-label">Report Period</div>
                    <div class="cover-meta-value">{{ $start->format('M d, Y H:i') }} — {{ $end->format('M d, Y H:i') }}</div>
                </div>
                @if($selectedDevice)
                <div class="cover-meta-row">
                    <div class="cover-meta-label">Scope</div>
                    <div class="cover-meta-value">{{ $selectedDevice->name }} ({{ $selectedDevice->ip_address }})</div>
                </div>
                @else
                <div class="cover-meta-row">
                    <div class="cover-meta-label">Scope</div>
                    <div class="cover-meta-value">All Monitored Devices</div>
                </div>
                @endif
                <div class="cover-meta-row">
                    <div class="cover-meta-label">Generated</div>
                    <div class="cover-meta-value">{{ now()->format('F d, Y \a\t H:i:s') }}</div>
                </div>
                <div class="cover-meta-row">
                    <div class="cover-meta-label">Reference ID</div>
                    <div class="cover-meta-value">RPT-{{ now()->format('Y-md') }}-{{ strtoupper(substr(md5(now()), 0, 6)) }}</div>
                </div>
            </div>
        </div>

        <!-- Confidentiality Footer -->
        <div class="cover-footer">
            <div class="cover-confidential">Confidential — Internal Use Only</div>
        </div>
    </div>

    <!-- ==================== PAGE 1: EXECUTIVE SUMMARY ==================== -->
    <div class="page-header">
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td width="30%">
                    @if($logoExists)
                        <img src="data:image/png;base64,{{ $logoBase64 }}" class="header-logo-img" alt="MonetX">
                    @else
                        <span class="header-logo">MonetX</span>
                    @endif
                </td>
                <td width="40%">
                    <div class="header-title">Top Talkers Report</div>
                </td>
                <td width="30%">
                    <div class="header-date">{{ $start->format('M d') }} – {{ $end->format('M d, Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <!-- Executive Summary Section -->
        <div class="section">
            <div class="section-title">Executive Summary</div>

            <!-- Metric Cards -->
            <table class="summary-grid" cellpadding="0" cellspacing="8">
                <tr>
                    <td width="25%">
                        <div class="summary-card summary-card-cyan">
                            <div class="summary-value summary-value-cyan">{{ number_format($totalFlows) }}</div>
                            <div class="summary-label">Total Flows</div>
                        </div>
                    </td>
                    <td width="25%">
                        <div class="summary-card summary-card-emerald">
                            <div class="summary-value summary-value-emerald">{{ $totalBytesFormatted }}</div>
                            <div class="summary-label">Total Traffic</div>
                        </div>
                    </td>
                    <td width="25%">
                        <div class="summary-card summary-card-purple">
                            <div class="summary-value summary-value-purple">{{ $topSources->count() }}</div>
                            <div class="summary-label">Unique Sources</div>
                        </div>
                    </td>
                    <td width="25%">
                        <div class="summary-card summary-card-amber">
                            <div class="summary-value summary-value-amber">{{ $topDestinations->count() }}</div>
                            <div class="summary-label">Unique Destinations</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Key Insights Section -->
        <div class="insights-box">
            <div class="insights-title">Key Findings</div>
            @if($topSource)
            <div class="insight-item">
                Top bandwidth consumer: <span class="insight-highlight">{{ $topSource->source_ip }}</span> generated
                @php
                    $srcBytes = $topSource->total_bytes;
                    if ($srcBytes >= 1073741824) echo round($srcBytes / 1073741824, 2) . ' GB';
                    elseif ($srcBytes >= 1048576) echo round($srcBytes / 1048576, 2) . ' MB';
                    else echo round($srcBytes / 1024, 2) . ' KB';
                @endphp
                ({{ $totalBytes > 0 ? number_format(($topSource->total_bytes / $totalBytes) * 100, 1) : 0 }}% of total traffic)
            </div>
            @endif
            @if($topDest)
            <div class="insight-item">
                Top destination: <span class="insight-highlight">{{ $topDest->destination_ip }}</span> received
                @php
                    $dstBytes = $topDest->total_bytes;
                    if ($dstBytes >= 1073741824) echo round($dstBytes / 1073741824, 2) . ' GB';
                    elseif ($dstBytes >= 1048576) echo round($dstBytes / 1048576, 2) . ' MB';
                    else echo round($dstBytes / 1024, 2) . ' KB';
                @endphp
                ({{ $totalBytes > 0 ? number_format(($topDest->total_bytes / $totalBytes) * 100, 1) : 0 }}% of total traffic)
            </div>
            @endif
            @if($topConv)
            <div class="insight-item">
                Busiest conversation: <span class="insight-highlight">{{ $topConv->source_ip }}</span> → <span class="insight-highlight">{{ $topConv->destination_ip }}</span> transferred
                @php
                    $convBytes = $topConv->total_bytes;
                    if ($convBytes >= 1073741824) echo round($convBytes / 1073741824, 2) . ' GB';
                    elseif ($convBytes >= 1048576) echo round($convBytes / 1048576, 2) . ' MB';
                    else echo round($convBytes / 1024, 2) . ' KB';
                @endphp
            </div>
            @endif
            <div class="insight-item">
                <span class="insight-highlight">{{ $topSources->count() }}</span> unique source IPs and <span class="insight-highlight">{{ $topDestinations->count() }}</span> unique destination IPs observed
            </div>
            <div class="insight-item">
                <span class="insight-highlight">{{ $topConversations->count() }}</span> active conversations identified during the reporting period
            </div>
        </div>

        <!-- Visual Charts Row -->
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 15px;">
            <tr>
                <td width="48%" style="vertical-align: top;">
                    @if($topSources->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-heading">Top Sources by Traffic Volume</div>
                        @php $maxSrcBytes = $topSources->max('total_bytes'); @endphp
                        <table class="chart-table">
                            @foreach($topSources->take(7) as $index => $source)
                            <tr>
                                <td width="28">
                                    @php
                                        $rankClass = match($index) {
                                            0 => 'chart-rank chart-rank-1',
                                            1 => 'chart-rank chart-rank-2',
                                            2 => 'chart-rank chart-rank-3',
                                            default => $index < 10 ? 'chart-rank chart-rank-default' : 'chart-rank chart-rank-gray'
                                        };
                                    @endphp
                                    <div class="{{ $rankClass }}">{{ $index + 1 }}</div>
                                </td>
                                <td class="chart-label-cell">{{ $source->source_ip }}</td>
                                <td class="chart-bar-cell">
                                    <div class="chart-bar-bg">
                                        <div class="chart-bar-fill" style="width: {{ $maxSrcBytes > 0 ? max(($source->total_bytes / $maxSrcBytes) * 100, 5) : 5 }}%;"></div>
                                    </div>
                                </td>
                                <td class="chart-value-cell">
                                    @php
                                        $bytes = $source->total_bytes;
                                        if ($bytes >= 1073741824) echo round($bytes / 1073741824, 1) . ' GB';
                                        elseif ($bytes >= 1048576) echo round($bytes / 1048576, 1) . ' MB';
                                        else echo round($bytes / 1024, 1) . ' KB';
                                    @endphp
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    @endif
                </td>
                <td width="4%"></td>
                <td width="48%" style="vertical-align: top;">
                    @if($topDestinations->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-heading">Top Destinations by Traffic Volume</div>
                        @php $maxDstBytes = $topDestinations->max('total_bytes'); @endphp
                        <table class="chart-table">
                            @foreach($topDestinations->take(7) as $index => $dest)
                            <tr>
                                <td width="28">
                                    @php
                                        $rankClass = match($index) {
                                            0 => 'chart-rank chart-rank-1',
                                            1 => 'chart-rank chart-rank-2',
                                            2 => 'chart-rank chart-rank-3',
                                            default => $index < 10 ? 'chart-rank chart-rank-default' : 'chart-rank chart-rank-gray'
                                        };
                                    @endphp
                                    <div class="{{ $rankClass }}">{{ $index + 1 }}</div>
                                </td>
                                <td class="chart-label-cell">{{ $dest->destination_ip }}</td>
                                <td class="chart-bar-cell">
                                    <div class="chart-bar-bg">
                                        <div class="chart-bar-fill-green" style="width: {{ $maxDstBytes > 0 ? max(($dest->total_bytes / $maxDstBytes) * 100, 5) : 5 }}%;"></div>
                                    </div>
                                </td>
                                <td class="chart-value-cell">
                                    @php
                                        $bytes = $dest->total_bytes;
                                        if ($bytes >= 1073741824) echo round($bytes / 1073741824, 1) . ' GB';
                                        elseif ($bytes >= 1048576) echo round($bytes / 1048576, 1) . ' MB';
                                        else echo round($bytes / 1024, 1) . ' KB';
                                    @endphp
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Top Sources Table -->
        <div class="section">
            <div class="section-title">Top Source IP Addresses</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 6%;" class="text-center">Rank</th>
                        <th style="width: 22%;">Source IP</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Dest</th>
                        <th style="width: 26%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSources as $index => $source)
                    <tr>
                        <td class="text-center">
                            @php
                                $badgeClass = match($index) {
                                    0 => 'rank-badge rank-gold',
                                    1 => 'rank-badge rank-silver',
                                    2 => 'rank-badge rank-bronze',
                                    default => $index < 10 ? 'rank-badge rank-purple' : 'rank-badge rank-gray'
                                };
                            @endphp
                            <span class="{{ $badgeClass }}">{{ $index + 1 }}</span>
                        </td>
                        <td><span class="ip-code">{{ $source->source_ip }}</span></td>
                        <td class="text-right">{{ number_format($source->flow_count) }}</td>
                        <td class="text-right">{{ number_format($source->total_packets) }}</td>
                        <td class="text-right">
                            @php
                                $bytes = $source->total_bytes;
                                if ($bytes >= 1073741824) echo round($bytes / 1073741824, 2) . ' GB';
                                elseif ($bytes >= 1048576) echo round($bytes / 1048576, 2) . ' MB';
                                else echo round($bytes / 1024, 2) . ' KB';
                            @endphp
                        </td>
                        <td class="text-right">{{ number_format($source->unique_destinations) }}</td>
                        <td>
                            @php $percent = $totalBytes > 0 ? ($source->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <div class="progress-bg"><div class="progress-fill" style="width: {{ min($percent, 100) }}%;"></div></div>
                            <span class="progress-text">{{ number_format($percent, 1) }}%</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Top Destinations Table -->
        <div class="section">
            <div class="section-title">Top Destination IP Addresses</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 6%;" class="text-center">Rank</th>
                        <th style="width: 22%;">Destination IP</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Src</th>
                        <th style="width: 26%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDestinations as $index => $dest)
                    <tr>
                        <td class="text-center">
                            @php
                                $badgeClass = match($index) {
                                    0 => 'rank-badge rank-gold',
                                    1 => 'rank-badge rank-silver',
                                    2 => 'rank-badge rank-bronze',
                                    default => $index < 10 ? 'rank-badge rank-purple' : 'rank-badge rank-gray'
                                };
                            @endphp
                            <span class="{{ $badgeClass }}">{{ $index + 1 }}</span>
                        </td>
                        <td><span class="ip-code">{{ $dest->destination_ip }}</span></td>
                        <td class="text-right">{{ number_format($dest->flow_count) }}</td>
                        <td class="text-right">{{ number_format($dest->total_packets) }}</td>
                        <td class="text-right">
                            @php
                                $bytes = $dest->total_bytes;
                                if ($bytes >= 1073741824) echo round($bytes / 1073741824, 2) . ' GB';
                                elseif ($bytes >= 1048576) echo round($bytes / 1048576, 2) . ' MB';
                                else echo round($bytes / 1024, 2) . ' KB';
                            @endphp
                        </td>
                        <td class="text-right">{{ number_format($dest->unique_sources) }}</td>
                        <td>
                            @php $percent = $totalBytes > 0 ? ($dest->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <div class="progress-bg"><div class="progress-fill-green" style="width: {{ min($percent, 100) }}%;"></div></div>
                            <span class="progress-text">{{ number_format($percent, 1) }}%</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Page Break -->
        <div class="page-break"></div>

        <!-- Page Header for Page 2 -->
        <div class="page-header">
            <table class="header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="30%">
                        @if($logoExists)
                            <img src="data:image/png;base64,{{ $logoBase64 }}" class="header-logo-img" alt="MonetX">
                        @else
                            <span class="header-logo">MonetX</span>
                        @endif
                    </td>
                    <td width="40%">
                        <div class="header-title">Top Conversations</div>
                    </td>
                    <td width="30%">
                        <div class="header-date">{{ $start->format('M d') }} – {{ $end->format('M d, Y') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Top Conversations Table -->
        <div class="section">
            <div class="section-title">Top Conversations</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">Rank</th>
                        <th style="width: 20%;">Source IP</th>
                        <th style="width: 20%;">Destination IP</th>
                        <th style="width: 10%;">Protocol</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 13%;" class="text-right">Traffic</th>
                        <th style="width: 22%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topConversations as $index => $conv)
                    <tr>
                        <td class="text-center">
                            @php
                                $badgeClass = match($index) {
                                    0 => 'rank-badge rank-gold',
                                    1 => 'rank-badge rank-silver',
                                    2 => 'rank-badge rank-bronze',
                                    default => $index < 10 ? 'rank-badge rank-purple' : 'rank-badge rank-gray'
                                };
                            @endphp
                            <span class="{{ $badgeClass }}">{{ $index + 1 }}</span>
                        </td>
                        <td><span class="ip-code">{{ $conv->source_ip }}</span></td>
                        <td><span class="ip-code">{{ $conv->destination_ip }}</span></td>
                        <td><strong style="color: #0891b2;">{{ strtoupper($conv->protocol) }}</strong></td>
                        <td class="text-right">{{ number_format($conv->flow_count) }}</td>
                        <td class="text-right">
                            @php
                                $bytes = $conv->total_bytes;
                                if ($bytes >= 1073741824) echo round($bytes / 1073741824, 2) . ' GB';
                                elseif ($bytes >= 1048576) echo round($bytes / 1048576, 2) . ' MB';
                                else echo round($bytes / 1024, 2) . ' KB';
                            @endphp
                        </td>
                        <td>
                            @php $percent = $totalBytes > 0 ? ($conv->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <div class="progress-bg"><div class="progress-fill" style="width: {{ min($percent, 100) }}%;"></div></div>
                            <span class="progress-text">{{ number_format($percent, 1) }}%</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Page Footer -->
    <div class="page-footer">
        <table class="footer-table" cellpadding="0" cellspacing="0">
            <tr>
                <td width="33%">
                    <div class="footer-brand"><strong>MonetX</strong> Network Traffic Analyzer</div>
                </td>
                <td width="34%">
                    <div class="footer-center">Generated: {{ now()->format('M d, Y H:i') }}</div>
                </td>
                <td width="33%">
                    <div class="footer-right">Report ID: RPT-{{ now()->format('Ymd-His') }}</div>
                </td>
            </tr>
        </table>
        <div class="footer-confidential">Confidential — Internal Use Only</div>
    </div>
</body>
</html>
