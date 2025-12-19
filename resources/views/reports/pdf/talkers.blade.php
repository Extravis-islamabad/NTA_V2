<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Top Talkers Report</title>
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
            margin: 0;
            padding: 0;
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
            margin-bottom: 18px;
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
            padding: 18px 15px;
            text-align: center;
            border-left: 4px solid #22d3ee;
        }
        .summary-card-alt {
            border-left-color: #10b981;
        }
        .summary-card-purple {
            border-left-color: #a855f7;
        }
        .summary-card-amber {
            border-left-color: #f59e0b;
        }
        .summary-value {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
        }
        .summary-label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        /* Visual Bar Charts */
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
            padding: 4px 0;
            vertical-align: middle;
        }
        .chart-label-cell {
            width: 100px;
            font-size: 7px;
            color: #374151;
            padding-right: 8px;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .chart-bar-cell {
            width: 130px;
        }
        .chart-bar-bg {
            background-color: #e2e8f0;
            height: 12px;
            width: 100%;
            border-radius: 2px;
        }
        .chart-bar-fill {
            background: linear-gradient(90deg, #22d3ee, #0891b2);
            height: 12px;
            border-radius: 2px;
        }
        .chart-bar-fill-alt {
            background: linear-gradient(90deg, #10b981, #059669);
            height: 12px;
            border-radius: 2px;
        }
        .chart-value-cell {
            width: 55px;
            font-size: 8px;
            color: #0f172a;
            font-weight: bold;
            text-align: right;
            padding-left: 8px;
        }

        /* Data Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
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
        .data-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }

        /* Rank Badge */
        .rank-badge {
            display: inline-block;
            width: 18px;
            height: 18px;
            background: linear-gradient(135deg, #22d3ee, #0891b2);
            color: white;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            line-height: 18px;
            border-radius: 3px;
        }
        .rank-2 { background: linear-gradient(135deg, #10b981, #059669); }
        .rank-3 { background: linear-gradient(135deg, #a855f7, #9333ea); }
        .rank-4 { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .rank-5 { background: linear-gradient(135deg, #f97316, #ea580c); }
        .rank-other { background: #64748b; }

        /* Progress Bar */
        .progress-bg {
            background-color: #e2e8f0;
            height: 8px;
            width: 55px;
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
        .progress-fill-alt {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .ip-code {
            font-family: 'DejaVu Sans Mono', monospace;
            background-color: #f1f5f9;
            padding: 2px 5px;
            font-size: 7px;
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
                <div class="cover-report-type">Network Analysis Report</div>
                <div class="cover-main-title">Top Talkers</div>
                <div class="cover-subtitle">Endpoint Traffic Analysis</div>
            </div>

            <!-- Metadata -->
            <div class="cover-meta-section">
                <table class="cover-meta-table" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="33%">
                            <div class="cover-meta-label">Report Period</div>
                            <div class="cover-meta-value">{{ $start->format('M d, Y H:i') }}</div>
                            <div class="cover-meta-value" style="font-size: 11px; color: #64748b;">to {{ $end->format('M d, Y H:i') }}</div>
                        </td>
                        <td width="33%">
                            <div class="cover-meta-label">Device Filter</div>
                            <div class="cover-meta-value">{{ $selectedDevice ? $selectedDevice->name : 'All Devices' }}</div>
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
                    <div class="report-title">Top Talkers Report</div>
                    <div class="report-subtitle">
                        {{ $start->format('M d, Y H:i') }} - {{ $end->format('M d, Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <!-- Executive Summary Dashboard -->
        <div class="section">
            <div class="section-title">Executive Summary</div>
            <div class="summary-dashboard">
                <table width="100%" cellpadding="0" cellspacing="10">
                    <tr>
                        <td width="25%">
                            <div class="summary-card">
                                <div class="summary-value">{{ number_format($totalFlows) }}</div>
                                <div class="summary-label">Total Flows</div>
                            </div>
                        </td>
                        <td width="25%">
                            <div class="summary-card summary-card-alt">
                                <div class="summary-value">
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
                                <div class="summary-label">Total Traffic</div>
                            </div>
                        </td>
                        <td width="25%">
                            <div class="summary-card summary-card-purple">
                                <div class="summary-value">{{ $topSources->count() }}</div>
                                <div class="summary-label">Unique Sources</div>
                            </div>
                        </td>
                        <td width="25%">
                            <div class="summary-card summary-card-amber">
                                <div class="summary-value">{{ $topDestinations->count() }}</div>
                                <div class="summary-label">Unique Destinations</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Visual Charts - Side by Side -->
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 15px;">
            <tr>
                <td width="48%" style="vertical-align: top;">
                    @if($topSources->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-heading">Top Sources by Traffic Volume</div>
                        @php $maxSrcBytes = $topSources->max('total_bytes'); @endphp
                        <table class="chart-table">
                            @foreach($topSources->take(7) as $source)
                            <tr>
                                <td class="chart-label-cell">{{ $source->source_ip }}</td>
                                <td class="chart-bar-cell">
                                    <div class="chart-bar-bg">
                                        <div class="chart-bar-fill" style="width: {{ $maxSrcBytes > 0 ? max(($source->total_bytes / $maxSrcBytes) * 100, 3) : 3 }}%;"></div>
                                    </div>
                                </td>
                                <td class="chart-value-cell">
                                    @php
                                        $bytes = $source->total_bytes;
                                        if ($bytes >= 1073741824) {
                                            echo round($bytes / 1073741824, 1) . 'G';
                                        } elseif ($bytes >= 1048576) {
                                            echo round($bytes / 1048576, 1) . 'M';
                                        } else {
                                            echo round($bytes / 1024, 1) . 'K';
                                        }
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
                            @foreach($topDestinations->take(7) as $dest)
                            <tr>
                                <td class="chart-label-cell">{{ $dest->destination_ip }}</td>
                                <td class="chart-bar-cell">
                                    <div class="chart-bar-bg">
                                        <div class="chart-bar-fill-alt" style="width: {{ $maxDstBytes > 0 ? max(($dest->total_bytes / $maxDstBytes) * 100, 3) : 3 }}%;"></div>
                                    </div>
                                </td>
                                <td class="chart-value-cell">
                                    @php
                                        $bytes = $dest->total_bytes;
                                        if ($bytes >= 1073741824) {
                                            echo round($bytes / 1073741824, 1) . 'G';
                                        } elseif ($bytes >= 1048576) {
                                            echo round($bytes / 1048576, 1) . 'M';
                                        } else {
                                            echo round($bytes / 1024, 1) . 'K';
                                        }
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
                        <th style="width: 5%;">Rank</th>
                        <th style="width: 22%;">Source IP</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Dest</th>
                        <th style="width: 27%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSources as $index => $source)
                    <tr>
                        <td>
                            @php
                                $rankClass = match($index + 1) {
                                    1 => 'rank-badge',
                                    2 => 'rank-badge rank-2',
                                    3 => 'rank-badge rank-3',
                                    4 => 'rank-badge rank-4',
                                    5 => 'rank-badge rank-5',
                                    default => 'rank-badge rank-other'
                                };
                            @endphp
                            <span class="{{ $rankClass }}">{{ $index + 1 }}</span>
                        </td>
                        <td><span class="ip-code">{{ $source->source_ip }}</span></td>
                        <td class="text-right">{{ number_format($source->flow_count) }}</td>
                        <td class="text-right">{{ number_format($source->total_packets) }}</td>
                        <td class="text-right">
                            @php
                                $bytes = $source->total_bytes;
                                if ($bytes >= 1073741824) {
                                    echo round($bytes / 1073741824, 2) . ' GB';
                                } elseif ($bytes >= 1048576) {
                                    echo round($bytes / 1048576, 2) . ' MB';
                                } else {
                                    echo round($bytes / 1024, 2) . ' KB';
                                }
                            @endphp
                        </td>
                        <td class="text-right">{{ number_format($source->unique_destinations) }}</td>
                        <td>
                            @php $percent = $totalBytes > 0 ? ($source->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <div class="progress-bg"><div class="progress-fill" style="width: {{ min($percent, 100) }}%;"></div></div>
                            <strong>{{ number_format($percent, 1) }}%</strong>
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
                        <th style="width: 5%;">Rank</th>
                        <th style="width: 22%;">Destination IP</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Src</th>
                        <th style="width: 27%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDestinations as $index => $dest)
                    <tr>
                        <td>
                            @php
                                $rankClass = match($index + 1) {
                                    1 => 'rank-badge',
                                    2 => 'rank-badge rank-2',
                                    3 => 'rank-badge rank-3',
                                    4 => 'rank-badge rank-4',
                                    5 => 'rank-badge rank-5',
                                    default => 'rank-badge rank-other'
                                };
                            @endphp
                            <span class="{{ $rankClass }}">{{ $index + 1 }}</span>
                        </td>
                        <td><span class="ip-code">{{ $dest->destination_ip }}</span></td>
                        <td class="text-right">{{ number_format($dest->flow_count) }}</td>
                        <td class="text-right">{{ number_format($dest->total_packets) }}</td>
                        <td class="text-right">
                            @php
                                $bytes = $dest->total_bytes;
                                if ($bytes >= 1073741824) {
                                    echo round($bytes / 1073741824, 2) . ' GB';
                                } elseif ($bytes >= 1048576) {
                                    echo round($bytes / 1048576, 2) . ' MB';
                                } else {
                                    echo round($bytes / 1024, 2) . ' KB';
                                }
                            @endphp
                        </td>
                        <td class="text-right">{{ number_format($dest->unique_sources) }}</td>
                        <td>
                            @php $percent = $totalBytes > 0 ? ($dest->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <div class="progress-bg"><div class="progress-fill progress-fill-alt" style="width: {{ min($percent, 100) }}%;"></div></div>
                            <strong>{{ number_format($percent, 1) }}%</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Page Break -->
        <div class="page-break"></div>

        <!-- Top Conversations -->
        <div class="section" style="margin-top: 20px;">
            <div class="section-title">Top Conversations</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">Rank</th>
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
                        <td>
                            @php
                                $rankClass = match($index + 1) {
                                    1 => 'rank-badge',
                                    2 => 'rank-badge rank-2',
                                    3 => 'rank-badge rank-3',
                                    4 => 'rank-badge rank-4',
                                    5 => 'rank-badge rank-5',
                                    default => 'rank-badge rank-other'
                                };
                            @endphp
                            <span class="{{ $rankClass }}">{{ $index + 1 }}</span>
                        </td>
                        <td><span class="ip-code">{{ $conv->source_ip }}</span></td>
                        <td><span class="ip-code">{{ $conv->destination_ip }}</span></td>
                        <td><strong style="color: #0891b2;">{{ strtoupper($conv->protocol) }}</strong></td>
                        <td class="text-right">{{ number_format($conv->flow_count) }}</td>
                        <td class="text-right">
                            @php
                                $bytes = $conv->total_bytes;
                                if ($bytes >= 1073741824) {
                                    echo round($bytes / 1073741824, 2) . ' GB';
                                } elseif ($bytes >= 1048576) {
                                    echo round($bytes / 1048576, 2) . ' MB';
                                } else {
                                    echo round($bytes / 1024, 2) . ' KB';
                                }
                            @endphp
                        </td>
                        <td>
                            @php $percent = $totalBytes > 0 ? ($conv->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <div class="progress-bg"><div class="progress-fill" style="width: {{ min($percent, 100) }}%;"></div></div>
                            <strong>{{ number_format($percent, 1) }}%</strong>
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
