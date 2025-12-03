<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Traffic Analysis Report</title>
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
        .report-meta {
            text-align: right;
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
            border-spacing: 10px 0;
            margin-bottom: 20px;
        }
        .stat-box {
            background: linear-gradient(135deg, #E4F2FF 0%, #F2C7FF 100%);
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #5548F5;
            display: block;
            margin-bottom: 5px;
        }
        .stat-value.pink { color: #C843F3; }
        .stat-value.purple { color: #9619B5; }
        .stat-value.green { color: #10B981; }
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
            margin-bottom: 15px;
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
            width: 120px;
            font-size: 9px;
            color: #374151;
            vertical-align: middle;
            padding-right: 10px;
            font-weight: 500;
        }
        .chart-bar-wrapper {
            display: table-cell;
            width: 200px;
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

        .highlight-box {
            background: linear-gradient(135deg, #5548F5 0%, #C843F3 100%);
            color: white;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .highlight-box .label {
            font-size: 9px;
            opacity: 0.9;
        }
        .highlight-box .value {
            font-size: 16px;
            font-weight: bold;
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
                    <div class="report-title">Traffic Analysis Report</div>
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
                    <td width="25%" style="padding: 0 5px;">
                        <div class="stat-box">
                            <span class="stat-value">{{ number_format($totalFlows) }}</span>
                            <span class="stat-label">Total Flows</span>
                        </div>
                    </td>
                    <td width="25%" style="padding: 0 5px;">
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
                            <span class="stat-label">Total Traffic</span>
                        </div>
                    </td>
                    <td width="25%" style="padding: 0 5px;">
                        <div class="stat-box">
                            <span class="stat-value purple">{{ number_format($totalPackets) }}</span>
                            <span class="stat-label">Total Packets</span>
                        </div>
                    </td>
                    <td width="25%" style="padding: 0 5px;">
                        <div class="stat-box">
                            <span class="stat-value green">
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
                            </span>
                            <span class="stat-label">Avg Bandwidth</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Visual Charts Section -->
        <table width="100%" style="margin-bottom: 20px;">
            <tr>
                <td width="50%" style="vertical-align: top; padding-right: 10px;">
                    @if($topApplications->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-title">Top Applications by Traffic</div>
                        @php $maxAppBytes = $topApplications->max('total_bytes'); @endphp
                        @foreach($topApplications->take(6) as $app)
                        <div class="chart-row">
                            <span class="chart-label">{{ Str::limit($app->application, 12) }}</span>
                            <span class="chart-bar-wrapper">
                                <div class="chart-bar-bg">
                                    <div class="chart-bar-fill" style="width: {{ $maxAppBytes > 0 ? max(($app->total_bytes / $maxAppBytes) * 100, 2) : 2 }}%"></div>
                                </div>
                            </span>
                            <span class="chart-value">
                                @php
                                    $bytes = $app->total_bytes;
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
                <td width="50%" style="vertical-align: top; padding-left: 10px;">
                    @if($topProtocols->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-title">Protocol Distribution</div>
                        @php $maxProtoBytes = $topProtocols->max('total_bytes'); @endphp
                        @foreach($topProtocols->take(6) as $protocol)
                        <div class="chart-row">
                            <span class="chart-label">{{ strtoupper($protocol->protocol) }}</span>
                            <span class="chart-bar-wrapper">
                                <div class="chart-bar-bg">
                                    <div class="chart-bar-fill alt" style="width: {{ $maxProtoBytes > 0 ? max(($protocol->total_bytes / $maxProtoBytes) * 100, 2) : 2 }}%"></div>
                                </div>
                            </span>
                            <span class="chart-value alt">
                                @php
                                    $bytes = $protocol->total_bytes;
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

        <!-- Top Applications Table -->
        <div class="section">
            <div class="section-title">Top Applications Details</div>
            @if($topApplications->isEmpty())
                <p style="color: #6b7280; text-align: center; padding: 20px;">No application data available for this period</p>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Application</th>
                            <th style="width: 12%;" class="text-right">Flows</th>
                            <th style="width: 15%;" class="text-right">Traffic</th>
                            <th style="width: 15%;" class="text-right">Packets</th>
                            <th style="width: 28%;">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topApplications as $index => $app)
                        <tr>
                            <td><strong>{{ $index + 1 }}</strong></td>
                            <td><strong>{{ $app->application }}</strong></td>
                            <td class="text-right">{{ number_format($app->flow_count) }}</td>
                            <td class="text-right">
                                @php
                                    $bytes = $app->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 2) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 2) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 2) . ' KB';
                                    }
                                @endphp
                            </td>
                            <td class="text-right">{{ number_format($app->total_packets) }}</td>
                            <td>
                                @php $percent = $totalBytes > 0 ? ($app->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <span class="progress-bar">
                                    <span class="progress-fill" style="width: {{ min($percent, 100) }}%"></span>
                                </span>
                                <strong>{{ number_format($percent, 1) }}%</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Protocol Distribution Table -->
        <div class="section">
            <div class="section-title">Protocol Analysis</div>
            @if($topProtocols->isEmpty())
                <p style="color: #6b7280; text-align: center; padding: 20px;">No protocol data available</p>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Protocol</th>
                            <th style="width: 12%;" class="text-right">Flows</th>
                            <th style="width: 15%;" class="text-right">Traffic</th>
                            <th style="width: 15%;" class="text-right">Packets</th>
                            <th style="width: 28%;">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProtocols as $index => $protocol)
                        <tr>
                            <td><strong>{{ $index + 1 }}</strong></td>
                            <td><strong>{{ strtoupper($protocol->protocol) }}</strong></td>
                            <td class="text-right">{{ number_format($protocol->flow_count) }}</td>
                            <td class="text-right">
                                @php
                                    $bytes = $protocol->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 2) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 2) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 2) . ' KB';
                                    }
                                @endphp
                            </td>
                            <td class="text-right">{{ number_format($protocol->total_packets) }}</td>
                            <td>
                                @php $percent = $totalBytes > 0 ? ($protocol->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <span class="progress-bar">
                                    <span class="progress-fill" style="width: {{ min($percent, 100) }}%"></span>
                                </span>
                                <strong>{{ number_format($percent, 1) }}%</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Page Break for IP addresses -->
        <div class="page-break"></div>

        <!-- Top Sources -->
        <div class="section" style="margin-top: 25px;">
            <div class="section-title">Top Source IP Addresses</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 28%;">Source IP</th>
                        <th style="width: 12%;" class="text-right">Flows</th>
                        <th style="width: 15%;" class="text-right">Traffic</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 28%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSources as $index => $source)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td><code>{{ $source->source_ip }}</code></td>
                        <td class="text-right">{{ number_format($source->flow_count) }}</td>
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
                        <td class="text-right">{{ number_format($source->total_packets) }}</td>
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

        <!-- Top Destinations -->
        <div class="section">
            <div class="section-title">Top Destination IP Addresses</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 28%;">Destination IP</th>
                        <th style="width: 12%;" class="text-right">Flows</th>
                        <th style="width: 15%;" class="text-right">Traffic</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 28%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDestinations as $index => $dest)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td><code>{{ $dest->destination_ip }}</code></td>
                        <td class="text-right">{{ number_format($dest->flow_count) }}</td>
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
                        <td class="text-right">{{ number_format($dest->total_packets) }}</td>
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
