<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Device Inventory Report</title>
    <style>
        @page {
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #1f2937;
            background: #fff;
        }

        /* Cover Page Styles */
        .cover-page {
            width: 100%;
            height: 100%;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            padding: 0;
            position: relative;
            page-break-after: always;
        }
        .cover-inner {
            padding: 60px 50px;
        }
        .cover-logo {
            margin-bottom: 80px;
        }
        .cover-logo-text {
            font-size: 36px;
            font-weight: bold;
            color: #22d3ee;
            letter-spacing: 3px;
        }
        .cover-logo-sub {
            font-size: 11px;
            color: #94a3b8;
            letter-spacing: 2px;
            margin-top: 5px;
        }
        .cover-title-section {
            margin-bottom: 60px;
            padding-left: 20px;
            border-left: 4px solid #22d3ee;
        }
        .cover-report-type {
            font-size: 12px;
            color: #22d3ee;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }
        .cover-main-title {
            font-size: 42px;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.2;
            margin-bottom: 8px;
        }
        .cover-subtitle {
            font-size: 16px;
            color: #94a3b8;
        }
        .cover-meta-section {
            margin-top: 60px;
        }
        .cover-meta-table td {
            padding: 12px 0;
            vertical-align: top;
        }
        .cover-meta-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .cover-meta-value {
            font-size: 14px;
            color: #e2e8f0;
            margin-top: 4px;
        }
        .cover-footer {
            position: absolute;
            bottom: 40px;
            left: 50px;
            right: 50px;
        }
        .cover-confidential {
            font-size: 9px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding-top: 15px;
            border-top: 1px solid #334155;
        }
        .cover-accent-line {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #22d3ee, #0891b2);
            margin-bottom: 30px;
        }

        /* Regular Page Styles */
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 15px 25px;
            margin-bottom: 20px;
        }
        .logo-img {
            height: 30px;
            width: auto;
        }
        .logo-text {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #22d3ee;
        }
        .logo-sub {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 2px;
        }
        .report-subtitle {
            font-size: 9px;
            color: #94a3b8;
        }

        .content {
            padding: 0 25px 60px;
        }

        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            padding-bottom: 8px;
            margin-bottom: 12px;
            border-bottom: 2px solid #22d3ee;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Executive Summary Dashboard */
        .summary-dashboard {
            margin-bottom: 20px;
        }
        .summary-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 16px 12px;
            text-align: center;
            border-left: 4px solid #22d3ee;
        }
        .summary-card-success {
            border-left-color: #10b981;
        }
        .summary-card-danger {
            border-left-color: #ef4444;
        }
        .summary-card-purple {
            border-left-color: #a855f7;
        }
        .summary-card-amber {
            border-left-color: #f59e0b;
        }
        .summary-value {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }
        .summary-value-success {
            color: #10b981;
        }
        .summary-value-danger {
            color: #ef4444;
        }
        .summary-value-purple {
            color: #a855f7;
        }
        .summary-value-amber {
            color: #f59e0b;
        }
        .summary-label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-top: 8px;
        }
        .data-table th {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 10px 6px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table th.text-right {
            text-align: right;
        }
        .data-table th.text-center {
            text-align: center;
        }
        .data-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }

        /* Badge Styles */
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

        /* Progress Bar */
        .progress-bg {
            background-color: #e2e8f0;
            height: 8px;
            width: 60px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 6px;
            border-radius: 2px;
        }
        .progress-fill {
            background: linear-gradient(90deg, #22d3ee, #0891b2);
            height: 8px;
            border-radius: 2px;
        }

        /* Device Type Chart */
        .chart-container {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            margin-bottom: 12px;
        }
        .chart-heading {
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 10px;
            padding-bottom: 6px;
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
            width: 100px;
            font-size: 9px;
            color: #374151;
            padding-right: 8px;
        }
        .chart-bar-cell {
            width: 150px;
        }
        .chart-bar-bg {
            background-color: #e2e8f0;
            height: 14px;
            width: 100%;
            border-radius: 2px;
        }
        .chart-bar-fill {
            background: linear-gradient(90deg, #22d3ee, #0891b2);
            height: 14px;
            border-radius: 2px;
        }
        .chart-value-cell {
            width: 60px;
            font-size: 9px;
            color: #0f172a;
            font-weight: bold;
            text-align: right;
            padding-left: 8px;
        }

        .ip-code {
            font-family: 'DejaVu Sans Mono', monospace;
            background-color: #f1f5f9;
            padding: 2px 5px;
            font-size: 8px;
            color: #0f172a;
            border-radius: 2px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 12px 25px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            font-size: 7px;
            color: #94a3b8;
        }
        .footer-brand {
            color: #22d3ee;
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="cover-page">
        <div class="cover-inner">
            <!-- Logo -->
            <div class="cover-logo">
                @php
                    $logoPath = public_path('images/logo.png');
                    $logoExists = file_exists($logoPath);
                    $logoBase64 = $logoExists ? base64_encode(file_get_contents($logoPath)) : '';
                @endphp
                @if($logoExists)
                    <img src="data:image/png;base64,{{ $logoBase64 }}" style="height: 50px; width: auto;" alt="MonetX">
                @else
                    <div class="cover-logo-text">MonetX</div>
                    <div class="cover-logo-sub">NETWORK TRAFFIC ANALYZER</div>
                @endif
            </div>

            <!-- Title Section -->
            <div class="cover-accent-line"></div>
            <div class="cover-title-section">
                <div class="cover-report-type">Infrastructure Report</div>
                <div class="cover-main-title">Device Inventory</div>
                <div class="cover-subtitle">Network Infrastructure Overview</div>
            </div>

            <!-- Metadata -->
            <div class="cover-meta-section">
                <table class="cover-meta-table" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="33%">
                            <div class="cover-meta-label">Total Devices</div>
                            <div class="cover-meta-value">{{ $stats['total_devices'] }} Devices</div>
                        </td>
                        <td width="33%">
                            <div class="cover-meta-label">Status Overview</div>
                            <div class="cover-meta-value">{{ $stats['online_devices'] }} Online / {{ $stats['offline_devices'] }} Offline</div>
                        </td>
                        <td width="34%">
                            <div class="cover-meta-label">Generated</div>
                            <div class="cover-meta-value">{{ now()->format('F d, Y') }}</div>
                            <div class="cover-meta-value" style="font-size: 11px; color: #64748b;">{{ now()->format('H:i:s') }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Footer -->
            <div class="cover-footer">
                <div class="cover-confidential">
                    Confidential - Internal Use Only
                </div>
            </div>
        </div>
    </div>

    <!-- Content Pages -->
    <div class="header">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50%" style="vertical-align: middle;">
                    @if($logoExists)
                        <img src="data:image/png;base64,{{ $logoBase64 }}" class="logo-img" alt="MonetX">
                    @else
                        <div class="logo-text">MonetX</div>
                        <div class="logo-sub">NETWORK TRAFFIC ANALYZER</div>
                    @endif
                </td>
                <td width="50%" style="text-align: right; vertical-align: middle;">
                    <div class="report-title">Device Inventory Report</div>
                    <div class="report-subtitle">Generated: {{ now()->format('M d, Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <!-- Executive Summary Dashboard -->
        <div class="section">
            <div class="section-title">Executive Summary</div>
            <div class="summary-dashboard">
                <table width="100%" cellpadding="0" cellspacing="8">
                    <tr>
                        <td width="20%">
                            <div class="summary-card">
                                <div class="summary-value">{{ $stats['total_devices'] }}</div>
                                <div class="summary-label">Total Devices</div>
                            </div>
                        </td>
                        <td width="20%">
                            <div class="summary-card summary-card-success">
                                <div class="summary-value summary-value-success">{{ $stats['online_devices'] }}</div>
                                <div class="summary-label">Online</div>
                            </div>
                        </td>
                        <td width="20%">
                            <div class="summary-card summary-card-danger">
                                <div class="summary-value summary-value-danger">{{ $stats['offline_devices'] }}</div>
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
                                <div class="summary-value summary-value-amber">
                                    @php
                                        $bytes = $stats['total_bytes'];
                                        if ($bytes >= 1099511627776) {
                                            echo round($bytes / 1099511627776, 1) . ' TB';
                                        } elseif ($bytes >= 1073741824) {
                                            echo round($bytes / 1073741824, 1) . ' GB';
                                        } elseif ($bytes >= 1048576) {
                                            echo round($bytes / 1048576, 1) . ' MB';
                                        } else {
                                            echo round($bytes / 1024, 1) . ' KB';
                                        }
                                    @endphp
                                </div>
                                <div class="summary-label">Total Traffic</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Device Type Distribution Chart -->
        @if($devicesByType->isNotEmpty())
        <div class="section">
            <div class="section-title">Device Type Distribution</div>
            <div class="chart-container">
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
        </div>
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

        <!-- Traffic by Device (Top 10) -->
        @if($devices->count() > 0)
        <div class="section">
            <div class="section-title">Traffic Distribution by Device (Top 10)</div>
            <div class="chart-container">
                @php
                    $topDevices = $devices->sortByDesc('flow_count')->take(10);
                    $maxFlows = $topDevices->max('flow_count');
                @endphp
                <table class="chart-table">
                    @foreach($topDevices as $device)
                    <tr>
                        <td class="chart-label-cell">{{ Str::limit($device->name, 15) }}</td>
                        <td class="chart-bar-cell">
                            <div class="chart-bar-bg">
                                <div class="chart-bar-fill" style="width: {{ $maxFlows > 0 ? max(($device->flow_count / $maxFlows) * 100, 3) : 3 }}%;"></div>
                            </div>
                        </td>
                        <td class="chart-value-cell">
                            {{ number_format($device->flow_count) }} flows
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="footer">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="33%">
                    <span class="footer-brand">MonetX</span> Network Traffic Analyzer
                </td>
                <td width="34%" style="text-align: center;">
                    Generated: {{ now()->format('F d, Y H:i:s') }}
                </td>
                <td width="33%" style="text-align: right;">
                    Confidential - Internal Use Only
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
