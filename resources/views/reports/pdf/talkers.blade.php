<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Top Talkers Report</title>
    <style>
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
        .header {
            background: #5548F5;
            color: white;
            padding: 25px 30px;
            margin-bottom: 25px;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #C843F3;
        }
        .logo-section {
            margin-bottom: 5px;
        }
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #ffffff;
        }
        .logo-sub {
            font-size: 10px;
            opacity: 0.9;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-subtitle {
            font-size: 10px;
            opacity: 0.9;
        }
        .content {
            padding: 0 30px 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #5548F5;
            border-bottom: 3px solid #5548F5;
            padding-bottom: 8px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stats-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 0;
            margin-bottom: 20px;
        }
        .stat-box {
            background: linear-gradient(135deg, #E4F2FF 0%, #F2C7FF 100%);
            padding: 18px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .stat-value {
            font-size: 22px;
            font-weight: bold;
            color: #5548F5;
            display: block;
            margin-bottom: 5px;
        }
        .stat-value.pink { color: #C843F3; }
        .stat-value.purple { color: #9619B5; }
        .stat-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Chart Styles */
        .chart-container {
            background: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .chart-title {
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .chart-row {
            margin-bottom: 8px;
            display: table;
            width: 100%;
        }
        .chart-label {
            display: table-cell;
            width: 130px;
            font-size: 9px;
            color: #374151;
            vertical-align: middle;
            padding-right: 10px;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .chart-bar-wrapper {
            display: table-cell;
            width: 220px;
            vertical-align: middle;
        }
        .chart-bar-bg {
            width: 100%;
            height: 16px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        .chart-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #5548F5 0%, #C843F3 100%);
            border-radius: 4px;
            min-width: 3px;
        }
        .chart-bar-fill.alt {
            background: linear-gradient(90deg, #C843F3 0%, #9619B5 100%);
        }
        .chart-value {
            display: table-cell;
            width: 80px;
            font-size: 9px;
            color: #5548F5;
            font-weight: bold;
            text-align: right;
            vertical-align: middle;
            padding-left: 10px;
        }
        .chart-value.alt {
            color: #9619B5;
        }

        /* Table Styles */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9px;
        }
        table.data-table th {
            background: linear-gradient(135deg, #5548F5 0%, #9619B5 100%);
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        table.data-table th.text-right {
            text-align: right;
        }
        table.data-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        table.data-table tr:nth-child(even) {
            background: #f9fafb;
        }
        table.data-table tr:hover {
            background: #E4F2FF;
        }
        .text-right {
            text-align: right;
        }

        /* Progress bar in tables */
        .progress-bar {
            display: inline-block;
            width: 60px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            margin-right: 8px;
            vertical-align: middle;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #5548F5, #C843F3);
            border-radius: 4px;
        }

        code {
            font-family: 'DejaVu Sans Mono', monospace;
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            color: #5548F5;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 30px;
            background: #f8fafc;
            border-top: 2px solid #5548F5;
            font-size: 8px;
            color: #6b7280;
        }
        .footer-brand {
            color: #5548F5;
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

        .two-col-charts {
            width: 100%;
        }
        .two-col-charts td {
            width: 50%;
            vertical-align: top;
            padding: 0 8px;
        }
        .two-col-charts td:first-child {
            padding-left: 0;
        }
        .two-col-charts td:last-child {
            padding-right: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td width="50%" style="vertical-align: top;">
                    <div class="logo-section">
                        <div class="logo-text">MonetX</div>
                        <div class="logo-sub">NETWORK TRAFFIC ANALYZER</div>
                    </div>
                </td>
                <td width="50%" style="text-align: right; vertical-align: top;">
                    <div class="report-title">Top Talkers Report</div>
                    <div class="report-subtitle">
                        {{ $start->format('M d, Y H:i') }} - {{ $end->format('M d, Y H:i') }}
                    </div>
                    @if($selectedDevice)
                        <div class="report-subtitle" style="margin-top: 3px;">Device: {{ $selectedDevice->name }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <!-- Summary Statistics -->
        <div class="section">
            <div class="section-title">Executive Summary</div>
            <table class="stats-grid">
                <tr>
                    <td width="50%" style="padding: 0 8px 0 0;">
                        <div class="stat-box">
                            <span class="stat-value">{{ number_format($totalFlows) }}</span>
                            <span class="stat-label">Total Flows Analyzed</span>
                        </div>
                    </td>
                    <td width="50%" style="padding: 0 0 0 8px;">
                        <div class="stat-box">
                            <span class="stat-value pink">
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
                            </span>
                            <span class="stat-label">Total Traffic Volume</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Visual Charts - Side by Side -->
        <table class="two-col-charts" style="margin-bottom: 20px;">
            <tr>
                <td>
                    @if($topSources->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-title">Top Sources by Traffic Volume</div>
                        @php $maxSrcBytes = $topSources->max('total_bytes'); @endphp
                        @foreach($topSources->take(8) as $source)
                        <div class="chart-row">
                            <span class="chart-label">{{ $source->source_ip }}</span>
                            <span class="chart-bar-wrapper">
                                <div class="chart-bar-bg">
                                    <div class="chart-bar-fill" style="width: {{ $maxSrcBytes > 0 ? max(($source->total_bytes / $maxSrcBytes) * 100, 2) : 2 }}%"></div>
                                </div>
                            </span>
                            <span class="chart-value">
                                @php
                                    $bytes = $source->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 1) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 1) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 1) . ' KB';
                                    }
                                @endphp
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </td>
                <td>
                    @if($topDestinations->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-title">Top Destinations by Traffic Volume</div>
                        @php $maxDstBytes = $topDestinations->max('total_bytes'); @endphp
                        @foreach($topDestinations->take(8) as $dest)
                        <div class="chart-row">
                            <span class="chart-label">{{ $dest->destination_ip }}</span>
                            <span class="chart-bar-wrapper">
                                <div class="chart-bar-bg">
                                    <div class="chart-bar-fill alt" style="width: {{ $maxDstBytes > 0 ? max(($dest->total_bytes / $maxDstBytes) * 100, 2) : 2 }}%"></div>
                                </div>
                            </span>
                            <span class="chart-value alt">
                                @php
                                    $bytes = $dest->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 1) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 1) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 1) . ' KB';
                                    }
                                @endphp
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Top Sources Table -->
        <div class="section">
            <div class="section-title">Top Source IP Addresses (Top Talkers)</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">#</th>
                        <th style="width: 20%;">Source IP</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Dest</th>
                        <th style="width: 30%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSources as $index => $source)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td><code>{{ $source->source_ip }}</code></td>
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
                            <span class="progress-bar">
                                <span class="progress-fill" style="width: {{ min($percent, 100) }}%"></span>
                            </span>
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
                        <th style="width: 4%;">#</th>
                        <th style="width: 20%;">Destination IP</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Src</th>
                        <th style="width: 30%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDestinations as $index => $dest)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td><code>{{ $dest->destination_ip }}</code></td>
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
                            <span class="progress-bar">
                                <span class="progress-fill" style="width: {{ min($percent, 100) }}%"></span>
                            </span>
                            <strong>{{ number_format($percent, 1) }}%</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Page Break for Conversations -->
        <div class="page-break"></div>

        <!-- Top Conversations -->
        <div class="section" style="margin-top: 25px;">
            <div class="section-title">Top Conversations (Source to Destination Pairs)</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">#</th>
                        <th style="width: 18%;">Source IP</th>
                        <th style="width: 18%;">Destination IP</th>
                        <th style="width: 10%;">Protocol</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 26%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topConversations as $index => $conv)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td><code>{{ $conv->source_ip }}</code></td>
                        <td><code>{{ $conv->destination_ip }}</code></td>
                        <td><strong>{{ strtoupper($conv->protocol) }}</strong></td>
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
                            <span class="progress-bar">
                                <span class="progress-fill" style="width: {{ min($percent, 100) }}%"></span>
                            </span>
                            <strong>{{ number_format($percent, 1) }}%</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <table width="100%">
            <tr>
                <td width="33%">
                    <span class="footer-brand">MonetX</span> Network Traffic Analyzer
                </td>
                <td width="34%" style="text-align: center;">
                    Generated: {{ now()->format('F d, Y H:i:s') }}
                </td>
                <td width="33%" style="text-align: right;">
                    Confidential Report
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
