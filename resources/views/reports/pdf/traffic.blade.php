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
            font-size: 11px;
            line-height: 1.4;
            color: #1f2937;
            background: #fff;
        }
        .header {
            background: linear-gradient(135deg, #5548F5 0%, #C843F3 50%, #9619B5 100%);
            color: white;
            padding: 25px 30px;
            margin-bottom: 25px;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo-section {
            display: flex;
            align-items: center;
        }
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .logo-sub {
            font-size: 10px;
            opacity: 0.9;
            margin-top: 2px;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-subtitle {
            font-size: 12px;
            opacity: 0.9;
        }
        .report-date {
            text-align: right;
            font-size: 10px;
        }
        .content {
            padding: 0 30px 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #5548F5;
            border-bottom: 2px solid #5548F5;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #5548F5;
        }
        .stat-label {
            font-size: 10px;
            color: #6b7280;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: linear-gradient(135deg, #5548F5 0%, #9619B5 100%);
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .progress-bar {
            width: 60px;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            display: inline-block;
            margin-right: 5px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #5548F5, #C843F3);
            border-radius: 3px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 30px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #6b7280;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-primary {
            background: #E4F2FF;
            color: #5548F5;
        }
        .badge-success {
            background: #D1FAE5;
            color: #059669;
        }
        .text-right {
            text-align: right;
        }
        .two-column {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }
        .column:first-child {
            padding-right: 15px;
        }
        .column:last-child {
            padding-left: 15px;
        }
        .highlight-box {
            background: linear-gradient(135deg, #E4F2FF 0%, #F2C7FF 100%);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .page-break {
            page-break-before: always;
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
            <table width="100%" cellspacing="10">
                <tr>
                    <td width="25%" style="background: #f8fafc; padding: 15px; border-radius: 8px; text-align: center;">
                        <div class="stat-value">{{ number_format($totalFlows) }}</div>
                        <div class="stat-label">Total Flows</div>
                    </td>
                    <td width="25%" style="background: #f8fafc; padding: 15px; border-radius: 8px; text-align: center;">
                        <div class="stat-value" style="color: #C843F3;">
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
                    <td width="25%" style="background: #f8fafc; padding: 15px; border-radius: 8px; text-align: center;">
                        <div class="stat-value" style="color: #9619B5;">{{ number_format($totalPackets) }}</div>
                        <div class="stat-label">Total Packets</div>
                    </td>
                    <td width="25%" style="background: #f8fafc; padding: 15px; border-radius: 8px; text-align: center;">
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

        <!-- Top Applications -->
        <div class="section">
            <div class="section-title">Top Applications by Traffic Volume</div>
            @if($topApplications->isEmpty())
                <p style="color: #6b7280; text-align: center; padding: 20px;">No application data available</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 30%;">Application</th>
                            <th style="width: 15%;" class="text-right">Flows</th>
                            <th style="width: 20%;" class="text-right">Traffic</th>
                            <th style="width: 30%;">Distribution</th>
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
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ min($percent, 100) }}%"></div>
                                </div>
                                {{ number_format($percent, 1) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Top Protocols -->
        <div class="section">
            <div class="section-title">Protocol Distribution</div>
            @if($topProtocols->isEmpty())
                <p style="color: #6b7280; text-align: center; padding: 20px;">No protocol data available</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 30%;">Protocol</th>
                            <th style="width: 15%;" class="text-right">Flows</th>
                            <th style="width: 20%;" class="text-right">Traffic</th>
                            <th style="width: 30%;">Distribution</th>
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
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ min($percent, 100) }}%"></div>
                                </div>
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
        <div class="section" style="margin-top: 30px;">
            <div class="section-title">Top Source IP Addresses</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">Source IP</th>
                        <th style="width: 15%;" class="text-right">Flows</th>
                        <th style="width: 20%;" class="text-right">Traffic</th>
                        <th style="width: 25%;">Distribution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSources as $index => $source)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><code style="font-family: monospace; background: #f3f4f6; padding: 2px 5px; border-radius: 3px;">{{ $source->source_ip }}</code></td>
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
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ min($percent, 100) }}%"></div>
                            </div>
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
                        <th style="width: 35%;">Destination IP</th>
                        <th style="width: 15%;" class="text-right">Flows</th>
                        <th style="width: 20%;" class="text-right">Traffic</th>
                        <th style="width: 25%;">Distribution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDestinations as $index => $dest)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><code style="font-family: monospace; background: #f3f4f6; padding: 2px 5px; border-radius: 3px;">{{ $dest->destination_ip }}</code></td>
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
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ min($percent, 100) }}%"></div>
                            </div>
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
