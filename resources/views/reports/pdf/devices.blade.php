<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Device Inventory Report</title>
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
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .report-subtitle {
            font-size: 11px;
            opacity: 0.9;
        }
        .content {
            padding: 0 25px 25px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #5548F5;
            border-bottom: 2px solid #5548F5;
            padding-bottom: 6px;
            margin-bottom: 12px;
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
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-online {
            background: #D1FAE5;
            color: #059669;
        }
        .badge-offline {
            background: #FEE2E2;
            color: #DC2626;
        }
        .badge-warning {
            background: #FEF3C7;
            color: #D97706;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
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
        .stat-box {
            background: #f8fafc;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #5548F5;
        }
        .stat-label {
            font-size: 9px;
            color: #6b7280;
            margin-top: 3px;
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
                    <div class="report-title">Device Inventory Report</div>
                    <div class="report-subtitle">Generated: {{ now()->format('M d, Y H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <!-- Summary Statistics -->
        <div class="section">
            <div class="section-title">Summary Overview</div>
            <table width="100%" cellspacing="8">
                <tr>
                    <td width="20%" style="background: #f8fafc; padding: 12px; border-radius: 6px; text-align: center;">
                        <div class="stat-value">{{ $stats['total_devices'] }}</div>
                        <div class="stat-label">Total Devices</div>
                    </td>
                    <td width="20%" style="background: #D1FAE5; padding: 12px; border-radius: 6px; text-align: center;">
                        <div class="stat-value" style="color: #059669;">{{ $stats['online_devices'] }}</div>
                        <div class="stat-label">Online</div>
                    </td>
                    <td width="20%" style="background: #FEE2E2; padding: 12px; border-radius: 6px; text-align: center;">
                        <div class="stat-value" style="color: #DC2626;">{{ $stats['offline_devices'] }}</div>
                        <div class="stat-label">Offline</div>
                    </td>
                    <td width="20%" style="background: #f8fafc; padding: 12px; border-radius: 6px; text-align: center;">
                        <div class="stat-value" style="color: #C843F3;">{{ number_format($stats['total_flows']) }}</div>
                        <div class="stat-label">Total Flows</div>
                    </td>
                    <td width="20%" style="background: #f8fafc; padding: 12px; border-radius: 6px; text-align: center;">
                        <div class="stat-value" style="color: #9619B5;">
                            @php
                                $bytes = $stats['total_bytes'];
                                if ($bytes >= 1099511627776) {
                                    echo round($bytes / 1099511627776, 2) . ' TB';
                                } elseif ($bytes >= 1073741824) {
                                    echo round($bytes / 1073741824, 2) . ' GB';
                                } elseif ($bytes >= 1048576) {
                                    echo round($bytes / 1048576, 2) . ' MB';
                                } else {
                                    echo round($bytes / 1024, 2) . ' KB';
                                }
                            @endphp
                        </div>
                        <div class="stat-label">Total Traffic</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Device List -->
        <div class="section">
            <div class="section-title">Device Inventory ({{ $devices->count() }} devices)</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 20%;">Device Name</th>
                        <th style="width: 15%;">IP Address</th>
                        <th style="width: 12%;">Type</th>
                        <th style="width: 8%;">Status</th>
                        <th style="width: 8%;" class="text-right">Interfaces</th>
                        <th style="width: 10%;" class="text-right">Flows</th>
                        <th style="width: 12%;">Location</th>
                        <th style="width: 10%;">Last Seen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devices as $index => $device)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $device->name }}</strong></td>
                        <td><code style="font-family: monospace; font-size: 9px;">{{ $device->ip_address }}</code></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $device->type)) }}</td>
                        <td>
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
                        <td>{{ $device->last_seen_at ? $device->last_seen_at->format('M d, H:i') : 'Never' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Distribution by Type -->
        @if($devicesByType->isNotEmpty())
        <div class="section">
            <div class="section-title">Distribution by Device Type</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 40%;">Device Type</th>
                        <th style="width: 20%;" class="text-right">Count</th>
                        <th style="width: 40%;">Distribution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devicesByType as $type => $count)
                    <tr>
                        <td><strong>{{ ucfirst(str_replace('_', ' ', $type)) }}</strong></td>
                        <td class="text-right">{{ $count }}</td>
                        <td>
                            @php $percent = $stats['total_devices'] > 0 ? ($count / $stats['total_devices']) * 100 : 0; @endphp
                            <div style="width: 60px; height: 6px; background: #e5e7eb; border-radius: 3px; display: inline-block; margin-right: 5px;">
                                <div style="height: 100%; background: linear-gradient(90deg, #5548F5, #C843F3); border-radius: 3px; width: {{ min($percent, 100) }}%"></div>
                            </div>
                            {{ number_format($percent, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
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
