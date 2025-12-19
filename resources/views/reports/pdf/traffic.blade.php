<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Traffic Analysis Report</title>
    <style>
        @page {
            margin: 0;
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
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #1f2937;
            background: #fff;
        }

        /* Cover Page Styles */
        .cover-page {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100%;
            padding: 0;
            position: relative;
            page-break-after: always;
        }
        .cover-header {
            padding: 60px 50px 40px;
            text-align: center;
        }
        .cover-logo {
            font-size: 42px;
            font-weight: bold;
            color: #22d3ee;
            letter-spacing: 4px;
            margin-bottom: 8px;
        }
        .cover-tagline {
            font-size: 11px;
            color: #94a3b8;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .cover-main {
            padding: 60px 50px;
            text-align: center;
        }
        .cover-title {
            font-size: 32px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        .cover-subtitle {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 40px;
        }
        .cover-divider {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #22d3ee, #8b5cf6);
            margin: 0 auto 40px;
        }
        .cover-meta {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 25px 40px;
            margin: 40px auto;
            max-width: 400px;
            text-align: left;
        }
        .cover-meta-row {
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .cover-meta-row:last-child {
            border-bottom: none;
        }
        .cover-meta-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .cover-meta-value {
            font-size: 12px;
            color: #f1f5f9;
            font-weight: bold;
            margin-top: 3px;
        }
        .cover-footer {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            text-align: center;
        }
        .cover-confidential {
            font-size: 9px;
            color: #ef4444;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 8px 20px;
            border: 1px solid #ef4444;
            display: inline-block;
        }

        /* Report Content Styles */
        .header {
            background-color: #0f172a;
            color: white;
            padding: 15px 25px;
            border-bottom: 3px solid #22d3ee;
        }
        .header-table {
            width: 100%;
        }
        .logo-text {
            font-size: 18px;
            font-weight: bold;
            color: #22d3ee;
            letter-spacing: 2px;
        }
        .report-info {
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
        }
        .report-info-title {
            font-size: 11px;
            color: #ffffff;
            font-weight: bold;
        }

        .content {
            padding: 20px 25px 60px;
        }

        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            border-left: 4px solid #22d3ee;
            padding-left: 12px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Executive Summary Dashboard */
        .dashboard-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .dashboard-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            padding: 15px;
            text-align: center;
        }
        .dashboard-card-accent {
            border-top: 3px solid #22d3ee;
        }
        .dashboard-card-accent-purple {
            border-top: 3px solid #8b5cf6;
        }
        .dashboard-card-accent-emerald {
            border-top: 3px solid #10b981;
        }
        .dashboard-card-accent-amber {
            border-top: 3px solid #f59e0b;
        }
        .dashboard-value {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 5px;
        }
        .dashboard-value-cyan { color: #0891b2; }
        .dashboard-value-purple { color: #7c3aed; }
        .dashboard-value-emerald { color: #059669; }
        .dashboard-value-amber { color: #d97706; }
        .dashboard-label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Chart Styles */
        .chart-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            margin-bottom: 15px;
        }
        .chart-heading {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .chart-table {
            width: 100%;
            border-collapse: collapse;
        }
        .chart-table td {
            padding: 4px 0;
            vertical-align: middle;
        }
        .chart-rank {
            width: 22px;
            height: 22px;
            background: #22d3ee;
            color: white;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            line-height: 22px;
            border-radius: 4px;
        }
        .chart-rank-purple {
            background: #8b5cf6;
        }
        .chart-label-cell {
            width: 80px;
            font-size: 9px;
            color: #374151;
            padding: 0 10px;
            font-weight: 500;
        }
        .chart-bar-cell {
            width: 140px;
        }
        .chart-bar-bg {
            background: #e2e8f0;
            height: 14px;
            border-radius: 2px;
            overflow: hidden;
        }
        .chart-bar-fill {
            background: linear-gradient(90deg, #22d3ee, #0891b2);
            height: 14px;
        }
        .chart-bar-fill-purple {
            background: linear-gradient(90deg, #a78bfa, #8b5cf6);
        }
        .chart-value-cell {
            width: 55px;
            font-size: 9px;
            color: #0f172a;
            font-weight: bold;
            text-align: right;
            padding-left: 10px;
        }

        /* Data Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        .data-table th {
            background: #0f172a;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table th.text-right {
            text-align: right;
        }
        .data-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .data-table tr:hover td {
            background: #f1f5f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }

        /* Rank Badge */
        .rank-badge {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #22d3ee;
            color: white;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            line-height: 20px;
            border-radius: 4px;
        }
        .rank-badge-2 { background: #10b981; }
        .rank-badge-3 { background: #8b5cf6; }
        .rank-badge-4 { background: #f59e0b; }
        .rank-badge-5 { background: #ec4899; }
        .rank-badge-default { background: #64748b; }

        /* Progress Bar in Tables */
        .progress-container {
            display: inline-block;
            vertical-align: middle;
        }
        .progress-bg {
            background: #e2e8f0;
            height: 8px;
            width: 60px;
            display: inline-block;
            vertical-align: middle;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            background: linear-gradient(90deg, #22d3ee, #0891b2);
            height: 8px;
        }
        .progress-fill-purple {
            background: linear-gradient(90deg, #a78bfa, #8b5cf6);
        }
        .progress-text {
            font-size: 9px;
            font-weight: bold;
            color: #0f172a;
            margin-left: 6px;
        }

        .ip-code {
            font-family: 'DejaVu Sans Mono', monospace;
            background: #f1f5f9;
            padding: 2px 6px;
            font-size: 8px;
            color: #0891b2;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 12px 25px;
            background: #0f172a;
            font-size: 8px;
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
        <div class="cover-header">
            <div class="cover-logo">MonetX</div>
            <div class="cover-tagline">Network Traffic Analyzer</div>
        </div>

        <div class="cover-main">
            <div class="cover-divider"></div>
            <div class="cover-title">Traffic Analysis Report</div>
            <div class="cover-subtitle">Comprehensive Network Traffic & Protocol Analysis</div>

            <div class="cover-meta">
                <div class="cover-meta-row">
                    <div class="cover-meta-label">Report Period</div>
                    <div class="cover-meta-value">{{ $start->format('M d, Y H:i') }} - {{ $end->format('M d, Y H:i') }}</div>
                </div>
                @if($selectedDevice)
                <div class="cover-meta-row">
                    <div class="cover-meta-label">Device</div>
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
            </div>
        </div>

        <div class="cover-footer">
            <div class="cover-confidential">Confidential - Internal Use Only</div>
        </div>
    </div>

    <!-- Report Header -->
    <div class="header">
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50%" style="vertical-align: middle;">
                    <div class="logo-text">MonetX</div>
                </td>
                <td width="50%" style="vertical-align: middle;">
                    <div class="report-info">
                        <div class="report-info-title">Traffic Analysis Report</div>
                        {{ $start->format('M d') }} - {{ $end->format('M d, Y') }}
                        @if($selectedDevice) | {{ $selectedDevice->name }} @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <!-- Executive Summary Dashboard -->
        <div class="section">
            <div class="section-title">Executive Summary</div>
            <table class="dashboard-grid" cellpadding="0" cellspacing="8">
                <tr>
                    <td width="25%">
                        <div class="dashboard-card dashboard-card-accent">
                            <div class="dashboard-value dashboard-value-cyan">{{ number_format($totalFlows) }}</div>
                            <div class="dashboard-label">Total Flows Analyzed</div>
                        </div>
                    </td>
                    <td width="25%">
                        <div class="dashboard-card dashboard-card-accent-emerald">
                            <div class="dashboard-value dashboard-value-emerald">
                                @php
                                    if ($totalBytes >= 1099511627776) {
                                        echo round($totalBytes / 1099511627776, 2) . ' TB';
                                    } elseif ($totalBytes >= 1073741824) {
                                        echo round($totalBytes / 1073741824, 2) . ' GB';
                                    } elseif ($totalBytes >= 1048576) {
                                        echo round($totalBytes / 1048576, 2) . ' MB';
                                    } else {
                                        echo round($totalBytes / 1024, 2) . ' KB';
                                    }
                                @endphp
                            </div>
                            <div class="dashboard-label">Total Traffic Volume</div>
                        </div>
                    </td>
                    <td width="25%">
                        <div class="dashboard-card dashboard-card-accent-purple">
                            <div class="dashboard-value dashboard-value-purple">{{ number_format($totalPackets) }}</div>
                            <div class="dashboard-label">Total Packets</div>
                        </div>
                    </td>
                    <td width="25%">
                        <div class="dashboard-card dashboard-card-accent-amber">
                            <div class="dashboard-value dashboard-value-amber">
                                @php
                                    if ($avgBandwidth >= 1000000000) {
                                        echo round($avgBandwidth / 1000000000, 2) . ' Gbps';
                                    } elseif ($avgBandwidth >= 1000000) {
                                        echo round($avgBandwidth / 1000000, 2) . ' Mbps';
                                    } elseif ($avgBandwidth >= 1000) {
                                        echo round($avgBandwidth / 1000, 2) . ' Kbps';
                                    } else {
                                        echo round($avgBandwidth, 2) . ' bps';
                                    }
                                @endphp
                            </div>
                            <div class="dashboard-label">Average Bandwidth</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Visual Charts Row -->
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="48%" style="vertical-align: top;">
                    @if($topApplications->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-heading">Top Applications by Traffic</div>
                        @php $maxAppBytes = $topApplications->max('total_bytes'); @endphp
                        <table class="chart-table">
                            @foreach($topApplications->take(6) as $index => $app)
                            <tr>
                                <td width="25">
                                    <div class="chart-rank">{{ $index + 1 }}</div>
                                </td>
                                <td class="chart-label-cell">{{ Str::limit($app->application, 12) }}</td>
                                <td class="chart-bar-cell">
                                    <div class="chart-bar-bg">
                                        <div class="chart-bar-fill" style="width: {{ $maxAppBytes > 0 ? max(($app->total_bytes / $maxAppBytes) * 100, 5) : 5 }}%;"></div>
                                    </div>
                                </td>
                                <td class="chart-value-cell">
                                    @php
                                        $bytes = $app->total_bytes;
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
                    @if($topProtocols->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-heading">Protocol Distribution</div>
                        @php $maxProtoBytes = $topProtocols->max('total_bytes'); @endphp
                        <table class="chart-table">
                            @foreach($topProtocols->take(6) as $index => $protocol)
                            <tr>
                                <td width="25">
                                    <div class="chart-rank chart-rank-purple">{{ $index + 1 }}</div>
                                </td>
                                <td class="chart-label-cell">{{ strtoupper($protocol->protocol) }}</td>
                                <td class="chart-bar-cell">
                                    <div class="chart-bar-bg">
                                        <div class="chart-bar-fill-purple" style="width: {{ $maxProtoBytes > 0 ? max(($protocol->total_bytes / $maxProtoBytes) * 100, 5) : 5 }}%;"></div>
                                    </div>
                                </td>
                                <td class="chart-value-cell">
                                    @php
                                        $bytes = $protocol->total_bytes;
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

        <!-- Top Applications Table -->
        <div class="section">
            <div class="section-title">Application Analysis</div>
            @if($topApplications->isEmpty())
                <p style="color: #64748b; text-align: center; padding: 20px;">No application data available for this period</p>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Application</th>
                            <th style="width: 12%;" class="text-right">Flows</th>
                            <th style="width: 15%;" class="text-right">Traffic</th>
                            <th style="width: 15%;" class="text-right">Packets</th>
                            <th style="width: 28%;">Share of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topApplications as $index => $app)
                        <tr>
                            <td>
                                <span class="rank-badge {{ $index < 5 ? ($index == 0 ? '' : 'rank-badge-' . ($index + 1)) : 'rank-badge-default' }}">{{ $index + 1 }}</span>
                            </td>
                            <td><strong>{{ $app->application }}</strong></td>
                            <td class="text-right">{{ number_format($app->flow_count) }}</td>
                            <td class="text-right">
                                @php
                                    $bytes = $app->total_bytes;
                                    if ($bytes >= 1073741824) echo round($bytes / 1073741824, 2) . ' GB';
                                    elseif ($bytes >= 1048576) echo round($bytes / 1048576, 2) . ' MB';
                                    else echo round($bytes / 1024, 2) . ' KB';
                                @endphp
                            </td>
                            <td class="text-right">{{ number_format($app->total_packets) }}</td>
                            <td>
                                @php $percent = $totalBytes > 0 ? ($app->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <div class="progress-bg"><div class="progress-fill" style="width: {{ min($percent, 100) }}%;"></div></div>
                                <span class="progress-text">{{ number_format($percent, 1) }}%</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Protocol Table -->
        <div class="section">
            <div class="section-title">Protocol Analysis</div>
            @if($topProtocols->isEmpty())
                <p style="color: #64748b; text-align: center; padding: 20px;">No protocol data available for this period</p>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Protocol</th>
                            <th style="width: 12%;" class="text-right">Flows</th>
                            <th style="width: 15%;" class="text-right">Traffic</th>
                            <th style="width: 15%;" class="text-right">Packets</th>
                            <th style="width: 28%;">Share of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProtocols as $index => $protocol)
                        <tr>
                            <td>
                                <span class="rank-badge {{ $index < 5 ? ($index == 0 ? '' : 'rank-badge-' . ($index + 1)) : 'rank-badge-default' }}">{{ $index + 1 }}</span>
                            </td>
                            <td><strong>{{ strtoupper($protocol->protocol) }}</strong></td>
                            <td class="text-right">{{ number_format($protocol->flow_count) }}</td>
                            <td class="text-right">
                                @php
                                    $bytes = $protocol->total_bytes;
                                    if ($bytes >= 1073741824) echo round($bytes / 1073741824, 2) . ' GB';
                                    elseif ($bytes >= 1048576) echo round($bytes / 1048576, 2) . ' MB';
                                    else echo round($bytes / 1024, 2) . ' KB';
                                @endphp
                            </td>
                            <td class="text-right">{{ number_format($protocol->total_packets) }}</td>
                            <td>
                                @php $percent = $totalBytes > 0 ? ($protocol->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <div class="progress-bg"><div class="progress-fill-purple" style="width: {{ min($percent, 100) }}%;"></div></div>
                                <span class="progress-text">{{ number_format($percent, 1) }}%</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Page Break for Network Analysis -->
        <div class="page-break"></div>

        <!-- Repeated Header for Page 2 -->
        <div class="header" style="margin: -20px -25px 20px; width: calc(100% + 50px);">
            <table class="header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="50%" style="vertical-align: middle;">
                        <div class="logo-text">MonetX</div>
                    </td>
                    <td width="50%" style="vertical-align: middle;">
                        <div class="report-info">
                            <div class="report-info-title">Traffic Analysis Report - Endpoints</div>
                            {{ $start->format('M d') }} - {{ $end->format('M d, Y') }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Top Sources -->
        <div class="section">
            <div class="section-title">Top Source IP Addresses</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Source IP</th>
                        <th style="width: 12%;" class="text-right">Flows</th>
                        <th style="width: 15%;" class="text-right">Traffic</th>
                        <th style="width: 15%;" class="text-right">Packets</th>
                        <th style="width: 28%;">Share of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSources as $index => $source)
                    <tr>
                        <td>
                            <span class="rank-badge {{ $index < 5 ? ($index == 0 ? '' : 'rank-badge-' . ($index + 1)) : 'rank-badge-default' }}">{{ $index + 1 }}</span>
                        </td>
                        <td><span class="ip-code">{{ $source->source_ip }}</span></td>
                        <td class="text-right">{{ number_format($source->flow_count) }}</td>
                        <td class="text-right">
                            @php
                                $bytes = $source->total_bytes;
                                if ($bytes >= 1073741824) echo round($bytes / 1073741824, 2) . ' GB';
                                elseif ($bytes >= 1048576) echo round($bytes / 1048576, 2) . ' MB';
                                else echo round($bytes / 1024, 2) . ' KB';
                            @endphp
                        </td>
                        <td class="text-right">{{ number_format($source->total_packets) }}</td>
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

        <!-- Top Destinations -->
        <div class="section">
            <div class="section-title">Top Destination IP Addresses</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Destination IP</th>
                        <th style="width: 12%;" class="text-right">Flows</th>
                        <th style="width: 15%;" class="text-right">Traffic</th>
                        <th style="width: 15%;" class="text-right">Packets</th>
                        <th style="width: 28%;">Share of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDestinations as $index => $dest)
                    <tr>
                        <td>
                            <span class="rank-badge {{ $index < 5 ? ($index == 0 ? '' : 'rank-badge-' . ($index + 1)) : 'rank-badge-default' }}">{{ $index + 1 }}</span>
                        </td>
                        <td><span class="ip-code">{{ $dest->destination_ip }}</span></td>
                        <td class="text-right">{{ number_format($dest->flow_count) }}</td>
                        <td class="text-right">
                            @php
                                $bytes = $dest->total_bytes;
                                if ($bytes >= 1073741824) echo round($bytes / 1073741824, 2) . ' GB';
                                elseif ($bytes >= 1048576) echo round($bytes / 1048576, 2) . ' MB';
                                else echo round($bytes / 1024, 2) . ' KB';
                            @endphp
                        </td>
                        <td class="text-right">{{ number_format($dest->total_packets) }}</td>
                        <td>
                            @php $percent = $totalBytes > 0 ? ($dest->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <div class="progress-bg"><div class="progress-fill-purple" style="width: {{ min($percent, 100) }}%;"></div></div>
                            <span class="progress-text">{{ number_format($percent, 1) }}%</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
