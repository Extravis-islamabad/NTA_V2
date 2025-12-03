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
            background: linear-gradient(135deg, #5548F5 0%, #C843F3 50%, #9619B5 100%);
            color: white;
            padding: 20px 25px;
            margin-bottom: 20px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .logo-sub {
            font-size: 9px;
            opacity: 0.9;
            margin-top: 2px;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .report-subtitle {
            font-size: 10px;
            opacity: 0.9;
        }
        .content {
            padding: 0 25px 25px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #5548F5;
            border-bottom: 2px solid #5548F5;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .stats-row {
            width: 100%;
            margin-bottom: 15px;
        }
        .stat-box {
            display: inline-block;
            width: 24%;
            background: linear-gradient(135deg, #E4F2FF 0%, #F2C7FF 100%);
            padding: 12px;
            text-align: center;
            border-radius: 6px;
            margin-right: 1%;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #5548F5;
        }
        .stat-value.pink { color: #C843F3; }
        .stat-value.purple { color: #9619B5; }
        .stat-label {
            font-size: 8px;
            color: #6b7280;
            margin-top: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th {
            background: linear-gradient(135deg, #5548F5 0%, #9619B5 100%);
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .bar-container {
            width: 80px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            display: inline-block;
            margin-right: 5px;
            vertical-align: middle;
        }
        .bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #5548F5, #C843F3);
            border-radius: 4px;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 12px 25px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            font-size: 8px;
            color: #6b7280;
        }
        .chart-section {
            margin-bottom: 15px;
        }
        .chart-title {
            font-size: 10px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
        }
        .chart-bar {
            margin-bottom: 6px;
        }
        .chart-label {
            display: inline-block;
            width: 100px;
            font-size: 9px;
            color: #374151;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .chart-bar-outer {
            display: inline-block;
            width: 200px;
            height: 14px;
            background: #E4F2FF;
            border-radius: 4px;
            vertical-align: middle;
            margin: 0 8px;
        }
        .chart-bar-inner {
            height: 100%;
            background: linear-gradient(90deg, #5548F5, #C843F3);
            border-radius: 4px;
            min-width: 2px;
        }
        .chart-value {
            display: inline-block;
            font-size: 9px;
            color: #5548F5;
            font-weight: bold;
        }
        .page-break {
            page-break-before: always;
        }
        code {
            font-family: monospace;
            background: #f3f4f6;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
        }
        .two-col {
            width: 100%;
        }
        .two-col td {
            width: 50%;
            vertical-align: top;
            padding: 0 10px 0 0;
            border: none;
        }
        .two-col td:last-child {
            padding: 0 0 0 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td width="50%">
                    <div class="logo-text">MonetX</div>
                    <div class="logo-sub">Network Traffic Analyzer</div>
                </td>
                <td width="50%" style="text-align: right;">
                    <div class="report-title">Traffic Analysis Report</div>
                    <div class="report-subtitle">
                        {{ $start->format('M d, Y H:i') }} - {{ $end->format('M d, Y H:i') }}
                    </div>
                    @if($selectedDevice)
                        <div class="report-subtitle">Device: {{ $selectedDevice->name }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <!-- Summary Statistics -->
        <div class="section">
            <div class="section-title">Summary Statistics</div>
            <table width="100%">
                <tr>
                    <td width="25%" style="background: linear-gradient(135deg, #E4F2FF 0%, #F2C7FF 100%); padding: 12px; border-radius: 6px; text-align: center; border: none;">
                        <div class="stat-value">{{ number_format($totalFlows) }}</div>
                        <div class="stat-label">Total Flows</div>
                    </td>
                    <td width="25%" style="background: linear-gradient(135deg, #E4F2FF 0%, #F2C7FF 100%); padding: 12px; border-radius: 6px; text-align: center; border: none;">
                        <div class="stat-value pink">
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
                        <div class="stat-label">Total Traffic</div>
                    </td>
                    <td width="25%" style="background: linear-gradient(135deg, #E4F2FF 0%, #F2C7FF 100%); padding: 12px; border-radius: 6px; text-align: center; border: none;">
                        <div class="stat-value purple">{{ number_format($totalPackets) }}</div>
                        <div class="stat-label">Total Packets</div>
                    </td>
                    <td width="25%" style="background: linear-gradient(135deg, #E4F2FF 0%, #F2C7FF 100%); padding: 12px; border-radius: 6px; text-align: center; border: none;">
                        <div class="stat-value" style="color: #10B981;">
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
                        <div class="stat-label">Avg Bandwidth</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Visual Charts Section -->
        @if($topApplications->isNotEmpty())
        <div class="section">
            <div class="section-title">Top Applications - Visual Distribution</div>
            <div class="chart-section">
                @php $maxAppBytes = $topApplications->max('total_bytes'); @endphp
                @foreach($topApplications->take(8) as $app)
                <div class="chart-bar">
                    <span class="chart-label">{{ Str::limit($app->application, 15) }}</span>
                    <span class="chart-bar-outer">
                        <span class="chart-bar-inner" style="width: {{ $maxAppBytes > 0 ? ($app->total_bytes / $maxAppBytes) * 100 : 0 }}%"></span>
                    </span>
                    <span class="chart-value">
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
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($topProtocols->isNotEmpty())
        <div class="section">
            <div class="section-title">Protocol Distribution - Visual Chart</div>
            <div class="chart-section">
                @php $maxProtoBytes = $topProtocols->max('total_bytes'); @endphp
                @foreach($topProtocols->take(6) as $protocol)
                <div class="chart-bar">
                    <span class="chart-label">{{ strtoupper($protocol->protocol) }}</span>
                    <span class="chart-bar-outer" style="background: #F2C7FF;">
                        <span class="chart-bar-inner" style="width: {{ $maxProtoBytes > 0 ? ($protocol->total_bytes / $maxProtoBytes) * 100 : 0 }}%; background: linear-gradient(90deg, #C843F3, #9619B5);"></span>
                    </span>
                    <span class="chart-value" style="color: #9619B5;">
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
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Applications Table -->
        <div class="section">
            <div class="section-title">Top Applications by Traffic Volume</div>
            @if($topApplications->isEmpty())
                <p style="color: #6b7280; text-align: center; padding: 15px;">No application data available</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 30%;">Application</th>
                            <th style="width: 12%;" class="text-right">Flows</th>
                            <th style="width: 18%;" class="text-right">Traffic</th>
                            <th style="width: 35%;">Distribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topApplications as $index => $app)
                        <tr>
                            <td>{{ $index + 1 }}</td>
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
                            <td>
                                @php $percent = $totalBytes > 0 ? ($app->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <span class="bar-container">
                                    <span class="bar-fill" style="width: {{ min($percent, 100) }}%"></span>
                                </span>
                                {{ number_format($percent, 1) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Top Protocols Table -->
        <div class="section">
            <div class="section-title">Protocol Distribution</div>
            @if($topProtocols->isEmpty())
                <p style="color: #6b7280; text-align: center; padding: 15px;">No protocol data available</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 30%;">Protocol</th>
                            <th style="width: 12%;" class="text-right">Flows</th>
                            <th style="width: 18%;" class="text-right">Traffic</th>
                            <th style="width: 35%;">Distribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProtocols as $index => $protocol)
                        <tr>
                            <td>{{ $index + 1 }}</td>
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
                            <td>
                                @php $percent = $totalBytes > 0 ? ($protocol->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <span class="bar-container">
                                    <span class="bar-fill" style="width: {{ min($percent, 100) }}%"></span>
                                </span>
                                {{ number_format($percent, 1) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Top Sources and Destinations -->
        <div class="page-break"></div>
        <div class="section" style="margin-top: 25px;">
            <div class="section-title">Top Source IP Addresses</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Source IP</th>
                        <th style="width: 12%;" class="text-right">Flows</th>
                        <th style="width: 18%;" class="text-right">Traffic</th>
                        <th style="width: 35%;">Distribution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSources as $index => $source)
                    <tr>
                        <td>{{ $index + 1 }}</td>
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
                        <td>
                            @php $percent = $totalBytes > 0 ? ($source->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <span class="bar-container">
                                <span class="bar-fill" style="width: {{ min($percent, 100) }}%"></span>
                            </span>
                            {{ number_format($percent, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Top Destination IP Addresses</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Destination IP</th>
                        <th style="width: 12%;" class="text-right">Flows</th>
                        <th style="width: 18%;" class="text-right">Traffic</th>
                        <th style="width: 35%;">Distribution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDestinations as $index => $dest)
                    <tr>
                        <td>{{ $index + 1 }}</td>
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
                        <td>
                            @php $percent = $totalBytes > 0 ? ($dest->total_bytes / $totalBytes) * 100 : 0; @endphp
                            <span class="bar-container">
                                <span class="bar-fill" style="width: {{ min($percent, 100) }}%"></span>
                            </span>
                            {{ number_format($percent, 1) }}%
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
                    <strong>MonetX</strong> Network Traffic Analyzer
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
