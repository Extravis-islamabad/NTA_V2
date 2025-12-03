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
        .header {
            background-color: #5548F5;
            color: white;
            padding: 20px 25px;
            margin-bottom: 20px;
            border-bottom: 4px solid #C843F3;
        }
        .logo-img {
            height: 40px;
            width: auto;
        }
        .logo-text {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #ffffff;
        }
        .logo-sub {
            font-size: 9px;
            color: #E4F2FF;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .report-subtitle {
            font-size: 9px;
            color: #E4F2FF;
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
            color: #5548F5;
            border-bottom: 2px solid #5548F5;
            padding-bottom: 6px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-box {
            background-color: #E4F2FF;
            padding: 15px 10px;
            text-align: center;
            border: 1px solid #d1d5db;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #5548F5;
        }
        .stat-value-pink { color: #C843F3; }
        .stat-value-purple { color: #9619B5; }
        .stat-label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* Simple Chart Bar - DomPDF Compatible */
        .chart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .chart-table td {
            padding: 3px 0;
            vertical-align: middle;
        }
        .chart-label-cell {
            width: 100px;
            font-size: 7px;
            color: #374151;
            padding-right: 5px;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .chart-bar-cell {
            width: 120px;
        }
        .chart-bar-bg {
            background-color: #e5e7eb;
            height: 10px;
            width: 100%;
        }
        .chart-bar-fill {
            background-color: #5548F5;
            height: 10px;
        }
        .chart-bar-fill-alt {
            background-color: #C843F3;
            height: 10px;
        }
        .chart-value-cell {
            width: 55px;
            font-size: 7px;
            color: #5548F5;
            font-weight: bold;
            text-align: right;
            padding-left: 5px;
        }
        .chart-value-alt {
            color: #9619B5;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        .data-table th {
            background-color: #5548F5;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .data-table th.text-right {
            text-align: right;
        }
        .data-table td {
            padding: 6px 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }

        /* Progress bar in tables */
        .progress-bg {
            background-color: #e5e7eb;
            height: 6px;
            width: 50px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
        }
        .progress-fill {
            background-color: #5548F5;
            height: 6px;
        }

        .ip-code {
            font-family: 'DejaVu Sans Mono', monospace;
            background-color: #f3f4f6;
            padding: 1px 4px;
            font-size: 7px;
            color: #5548F5;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 25px;
            background-color: #f8fafc;
            border-top: 2px solid #5548F5;
            font-size: 7px;
            color: #6b7280;
        }
        .footer-brand {
            color: #5548F5;
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

        .chart-container {
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            padding: 10px;
            margin-bottom: 10px;
        }
        .chart-heading {
            font-size: 10px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50%" style="vertical-align: middle;">
                    @php
                        $logoPath = public_path('images/logo.png');
                        $logoExists = file_exists($logoPath);
                        $logoBase64 = $logoExists ? base64_encode(file_get_contents($logoPath)) : '';
                    @endphp
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
            <div class="section-title">Executive Summary</div>
            <table width="100%" cellpadding="0" cellspacing="10">
                <tr>
                    <td width="50%">
                        <div class="stat-box">
                            <div class="stat-value">{{ number_format($totalFlows) }}</div>
                            <div class="stat-label">Total Flows Analyzed</div>
                        </div>
                    </td>
                    <td width="50%">
                        <div class="stat-box">
                            <div class="stat-value stat-value-pink">
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
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Visual Charts - Side by Side -->
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="48%" style="vertical-align: top;">
                    @if($topSources->isNotEmpty())
                    <div class="chart-container">
                        <div class="chart-heading">Top Sources</div>
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
                        <div class="chart-heading">Top Destinations</div>
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
                                <td class="chart-value-cell chart-value-alt">
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
                        <th style="width: 4%;">#</th>
                        <th style="width: 22%;">Source IP</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Dest</th>
                        <th style="width: 28%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSources as $index => $source)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
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
                        <th style="width: 4%;">#</th>
                        <th style="width: 22%;">Destination IP</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;" class="text-right">Packets</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 10%;" class="text-right">Src</th>
                        <th style="width: 28%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDestinations as $index => $dest)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
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
                            <div class="progress-bg"><div class="progress-fill" style="width: {{ min($percent, 100) }}%;"></div></div>
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
                        <th style="width: 4%;">#</th>
                        <th style="width: 20%;">Source IP</th>
                        <th style="width: 20%;">Destination IP</th>
                        <th style="width: 10%;">Protocol</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 14%;" class="text-right">Traffic</th>
                        <th style="width: 22%;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topConversations as $index => $conv)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td><span class="ip-code">{{ $conv->source_ip }}</span></td>
                        <td><span class="ip-code">{{ $conv->destination_ip }}</span></td>
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
                    Confidential Report
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
