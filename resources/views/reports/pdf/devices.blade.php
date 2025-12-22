<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Device Inventory Report</title>
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
            padding: 16px 10px;
            text-align: center;
        }
        .summary-card-cyan { border-top: 4px solid #22d3ee; }
        .summary-card-emerald { border-top: 4px solid #10b981; }
        .summary-card-red { border-top: 4px solid #ef4444; }
        .summary-card-purple { border-top: 4px solid #8b5cf6; }
        .summary-card-amber { border-top: 4px solid #f59e0b; }
        .summary-value {
            font-size: 22px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
        }
        .summary-value-cyan { color: #0891b2; }
        .summary-value-emerald { color: #059669; }
        .summary-value-red { color: #dc2626; }
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
        .insight-success {
            color: #059669;
        }
        .insight-danger {
            color: #dc2626;
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
        .chart-label-cell {
            width: 110px;
            font-size: 9px;
            color: #374151;
            padding-right: 10px;
            font-weight: 500;
        }
        .chart-bar-cell {
            width: 150px;
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
        .chart-value-cell {
            width: 80px;
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
            padding: 10px 8px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table th.text-right { text-align: right; }
        .data-table th.text-center { text-align: center; }
        .data-table td {
            padding: 8px;
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

        /* ==================== BADGES ==================== */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-online {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }
        .badge-offline {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }
        .badge-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
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
    </style>
</head>
<body>
    @php
        // Logo handling
        $logoPath = public_path('images/logo.png');
        $logoExists = file_exists($logoPath);
        $logoBase64 = $logoExists ? base64_encode(file_get_contents($logoPath)) : '';

        // Format total bytes
        $totalBytes = $stats['total_bytes'];
        if ($totalBytes >= 1099511627776) {
            $totalBytesFormatted = round($totalBytes / 1099511627776, 2) . ' TB';
        } elseif ($totalBytes >= 1073741824) {
            $totalBytesFormatted = round($totalBytes / 1073741824, 2) . ' GB';
        } elseif ($totalBytes >= 1048576) {
            $totalBytesFormatted = round($totalBytes / 1048576, 2) . ' MB';
        } else {
            $totalBytesFormatted = round($totalBytes / 1024, 2) . ' KB';
        }

        // Calculate uptime percentage
        $uptimePercent = $stats['total_devices'] > 0 ? ($stats['online_devices'] / $stats['total_devices']) * 100 : 0;

        // Get top device by flows
        $topDevice = $devices->sortByDesc('flow_count')->first();
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
                <div class="cover-report-type">Infrastructure Report</div>
                <div class="cover-title">Device Inventory</div>
                <div class="cover-subtitle">Network Infrastructure Overview</div>
            </div>

            <!-- Metadata Card -->
            <div class="cover-meta-card">
                <div class="cover-meta-row">
                    <div class="cover-meta-label">Total Devices</div>
                    <div class="cover-meta-value">{{ $stats['total_devices'] }} Devices Monitored</div>
                </div>
                <div class="cover-meta-row">
                    <div class="cover-meta-label">Status Overview</div>
                    <div class="cover-meta-value">{{ $stats['online_devices'] }} Online / {{ $stats['offline_devices'] }} Offline</div>
                </div>
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
                    <div class="header-title">Device Inventory Report</div>
                </td>
                <td width="30%">
                    <div class="header-date">Generated: {{ now()->format('M d, Y') }}</div>
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
                    <td width="20%">
                        <div class="summary-card summary-card-cyan">
                            <div class="summary-value summary-value-cyan">{{ $stats['total_devices'] }}</div>
                            <div class="summary-label">Total Devices</div>
                        </div>
                    </td>
                    <td width="20%">
                        <div class="summary-card summary-card-emerald">
                            <div class="summary-value summary-value-emerald">{{ $stats['online_devices'] }}</div>
                            <div class="summary-label">Online</div>
                        </div>
                    </td>
                    <td width="20%">
                        <div class="summary-card summary-card-red">
                            <div class="summary-value summary-value-red">{{ $stats['offline_devices'] }}</div>
                            <div class="summary-label">Offline</div>
                        </div>
                    </td>
                    <td width="20%">
                        <div class="summary-card summary-card-purple">
                            <div class="summary-value summary-value-purple">{{ number_format($stats['total_flows']) }}</div>
                            <div class="summary-label">Total Flows</div>
                        </div>
                    </td>
                    <td width="20%">
                        <div class="summary-card summary-card-amber">
                            <div class="summary-value summary-value-amber">{{ $totalBytesFormatted }}</div>
                            <div class="summary-label">Total Traffic</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Key Insights Section -->
        <div class="insights-box">
            <div class="insights-title">Key Findings</div>
            <div class="insight-item">
                Network uptime: <span class="insight-highlight {{ $uptimePercent >= 90 ? 'insight-success' : 'insight-danger' }}">{{ number_format($uptimePercent, 1) }}%</span> of devices are currently online
            </div>
            @if($topDevice)
            <div class="insight-item">
                Most active device: <span class="insight-highlight">{{ $topDevice->name }}</span> with {{ number_format($topDevice->flow_count) }} flows
            </div>
            @endif
            <div class="insight-item">
                <span class="insight-highlight">{{ $stats['total_devices'] }}</span> total devices across <span class="insight-highlight">{{ $devicesByType->count() }}</span> device types
            </div>
            @if($stats['offline_devices'] > 0)
            <div class="insight-item">
                <span class="insight-danger">{{ $stats['offline_devices'] }} device(s)</span> currently offline and require attention
            </div>
            @endif
            <div class="insight-item">
                Total network traffic processed: <span class="insight-highlight">{{ $totalBytesFormatted }}</span>
            </div>
        </div>

        <!-- Device Type Distribution Chart -->
        @if($devicesByType->isNotEmpty())
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 15px;">
            <tr>
                <td width="48%" style="vertical-align: top;">
                    <div class="chart-container">
                        <div class="chart-heading">Device Type Distribution</div>
                        @php $maxCount = $devicesByType->max(); @endphp
                        <table class="chart-table">
                            @foreach($devicesByType as $type => $count)
                            <tr>
                                <td class="chart-label-cell">{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                                <td class="chart-bar-cell">
                                    <div class="chart-bar-bg">
                                        <div class="chart-bar-fill" style="width: {{ $maxCount > 0 ? max(($count / $maxCount) * 100, 5) : 5 }}%;"></div>
                                    </div>
                                </td>
                                <td class="chart-value-cell">
                                    {{ $count }} ({{ number_format(($count / $stats['total_devices']) * 100, 1) }}%)
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </td>
                <td width="4%"></td>
                <td width="48%" style="vertical-align: top;">
                    @if($devices->count() > 0)
                    <div class="chart-container">
                        <div class="chart-heading">Top Devices by Flow Count</div>
                        @php
                            $topDevices = $devices->sortByDesc('flow_count')->take(6);
                            $maxFlows = $topDevices->max('flow_count');
                        @endphp
                        <table class="chart-table">
                            @foreach($topDevices as $device)
                            <tr>
                                <td class="chart-label-cell">{{ Str::limit($device->name, 14) }}</td>
                                <td class="chart-bar-cell">
                                    <div class="chart-bar-bg">
                                        <div class="chart-bar-fill" style="width: {{ $maxFlows > 0 ? max(($device->flow_count / $maxFlows) * 100, 5) : 5 }}%;"></div>
                                    </div>
                                </td>
                                <td class="chart-value-cell">
                                    {{ number_format($device->flow_count) }}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    @endif
                </td>
            </tr>
        </table>
        @endif

        <!-- Device Inventory Table -->
        <div class="section">
            <div class="section-title">Device Inventory ({{ $devices->count() }} devices)</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">#</th>
                        <th style="width: 18%;">Device Name</th>
                        <th style="width: 14%;">IP Address</th>
                        <th style="width: 11%;">Type</th>
                        <th style="width: 8%;" class="text-center">Status</th>
                        <th style="width: 7%;" class="text-right">Intf.</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 13%;">Location</th>
                        <th style="width: 15%;">Last Seen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devices as $index => $device)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $device->name }}</strong></td>
                        <td><span class="ip-code">{{ $device->ip_address }}</span></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $device->type)) }}</td>
                        <td class="text-center">
                            @if($device->status === 'online')
                                <span class="badge badge-online">Online</span>
                            @elseif($device->status === 'offline')
                                <span class="badge badge-offline">Offline</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($device->status) }}</span>
                            @endif
                        </td>
                        <td class="text-right">{{ $device->interface_count }}</td>
                        <td class="text-right">{{ number_format($device->flow_count) }}</td>
                        <td>{{ $device->location ?? 'N/A' }}</td>
                        <td>{{ $device->last_seen_at ? $device->last_seen_at->format('M d, Y H:i') : 'Never' }}</td>
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
