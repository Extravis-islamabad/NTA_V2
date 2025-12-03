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
        .text-right {
            text-align: right;
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
        .page-break {
            page-break-before: always;
        }
        code {
            font-family: monospace;
            background: #f3f4f6;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
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
                    <div class="report-title">Top Talkers Report</div>
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
                    <td width="50%" style="background: #f8fafc; padding: 15px; border-radius: 8px; text-align: center;">
                        <div class="stat-value">{{ number_format($totalFlows) }}</div>
                        <div class="stat-label">Total Flows Analyzed</div>
                    </td>
                    <td width="50%" style="background: #f8fafc; padding: 15px; border-radius: 8px; text-align: center;">
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
                        <div class="stat-label">Total Traffic Volume</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Top Sources -->
        <div class="section">
            <div class="section-title">Top Source IP Addresses (Top Talkers)</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Source IP</th>
                        <th style="width: 12%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 15%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Unique Dest</th>
                        <th style="width: 21%;">Distribution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSources as $index => $source)
                    <tr>
                        <td>{{ $index + 1 }}</td>
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

        <!-- Top Destinations -->
        <div class="section">
            <div class="section-title">Top Destination IP Addresses</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Destination IP</th>
                        <th style="width: 12%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 15%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Unique Src</th>
                        <th style="width: 21%;">Distribution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDestinations as $index => $dest)
                    <tr>
                        <td>{{ $index + 1 }}</td>
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

        <!-- Top Conversations -->
        <div class="page-break"></div>
        <div class="section" style="margin-top: 30px;">
            <div class="section-title">Top Conversations (Source to Destination Pairs)</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 4%;">#</th>
                        <th style="width: 22%;">Source IP</th>
                        <th style="width: 22%;">Destination IP</th>
                        <th style="width: 10%;">Protocol</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Traffic</th>
                        <th style="width: 20%;">Distribution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topConversations as $index => $conv)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><code>{{ $conv->source_ip }}</code></td>
                        <td><code>{{ $conv->destination_ip }}</code></td>
                        <td>{{ strtoupper($conv->protocol) }}</td>
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
