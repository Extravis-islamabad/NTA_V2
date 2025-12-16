import './bootstrap';

import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';
import L from 'leaflet';

// Make libraries globally available
window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
window.L = L;

// MonetX brand colors for charts
window.monetxColors = {
    primary: '#5548F5',
    secondary: '#C843F3',
    tertiary: '#9619B5',
    light: '#E4F2FF',
    lightPink: '#F2C7FF',
    success: '#10B981',
    warning: '#F59E0B',
    danger: '#EF4444',
    info: '#3B82F6',
    gradientStart: '#5548F5',
    gradientEnd: '#C843F3'
};

// ApexCharts default options with MonetX theme
window.apexDefaultOptions = {
    chart: {
        fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
        toolbar: {
            show: true,
            tools: {
                download: true,
                selection: true,
                zoom: true,
                zoomin: true,
                zoomout: true,
                pan: true,
                reset: true
            }
        },
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800,
            animateGradually: {
                enabled: true,
                delay: 150
            },
            dynamicAnimation: {
                enabled: true,
                speed: 350
            }
        }
    },
    colors: [
        window.monetxColors.primary,
        window.monetxColors.secondary,
        window.monetxColors.tertiary,
        window.monetxColors.success,
        window.monetxColors.warning,
        window.monetxColors.info,
        window.monetxColors.danger
    ],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.3,
            stops: [0, 90, 100]
        }
    },
    tooltip: {
        theme: 'light',
        style: {
            fontSize: '12px'
        },
        y: {
            formatter: function(value) {
                return formatBytes(value);
            }
        }
    },
    grid: {
        borderColor: '#e5e7eb',
        strokeDashArray: 4
    },
    stroke: {
        curve: 'smooth',
        width: 2
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        position: 'bottom',
        horizontalAlign: 'center',
        fontSize: '12px',
        markers: {
            width: 12,
            height: 12,
            radius: 2
        }
    }
};

// Helper function to format bytes
window.formatBytes = function(bytes) {
    if (bytes === null || bytes === undefined) return '0 B';
    bytes = parseInt(bytes);
    if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
    if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
    return bytes + ' B';
};

// Helper function to format bandwidth
window.formatBandwidth = function(bps) {
    if (bps === null || bps === undefined) return '0 bps';
    bps = parseInt(bps);
    if (bps >= 1000000000) return (bps / 1000000000).toFixed(2) + ' Gbps';
    if (bps >= 1000000) return (bps / 1000000).toFixed(2) + ' Mbps';
    if (bps >= 1000) return (bps / 1000).toFixed(2) + ' Kbps';
    return bps + ' bps';
};

// Create sparkline chart helper
window.createSparkline = function(elementId, data, color = window.monetxColors.primary) {
    const options = {
        chart: {
            type: 'area',
            height: 60,
            sparkline: { enabled: true },
            animations: {
                enabled: true,
                dynamicAnimation: { speed: 500 }
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.5,
                opacityTo: 0.1,
            }
        },
        colors: [color],
        series: [{
            name: 'Bandwidth',
            data: data
        }],
        tooltip: {
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                    formatter: function() { return ''; }
                },
                formatter: function(val) {
                    return formatBandwidth(val);
                }
            }
        }
    };

    const el = document.querySelector(elementId);
    if (el) {
        return new ApexCharts(el, options);
    }
    return null;
};

// Create donut chart helper
window.createDonutChart = function(elementId, labels, series, colors = null) {
    const options = {
        ...window.apexDefaultOptions,
        chart: {
            ...window.apexDefaultOptions.chart,
            type: 'donut',
            height: 300
        },
        labels: labels,
        series: series,
        colors: colors || window.apexDefaultOptions.colors,
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function(w) {
                                return formatBytes(w.globals.seriesTotals.reduce((a, b) => a + b, 0));
                            }
                        }
                    }
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: { height: 250 },
                legend: { position: 'bottom' }
            }
        }]
    };

    const el = document.querySelector(elementId);
    if (el) {
        return new ApexCharts(el, options);
    }
    return null;
};

// Create bar chart helper
window.createBarChart = function(elementId, categories, series, horizontal = false) {
    const options = {
        ...window.apexDefaultOptions,
        chart: {
            ...window.apexDefaultOptions.chart,
            type: 'bar',
            height: 300
        },
        plotOptions: {
            bar: {
                horizontal: horizontal,
                borderRadius: 4,
                dataLabels: {
                    position: 'top'
                }
            }
        },
        xaxis: {
            categories: categories,
            labels: {
                style: {
                    colors: '#6b7280',
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return formatBytes(val);
                },
                style: {
                    colors: '#6b7280',
                    fontSize: '11px'
                }
            }
        },
        series: series
    };

    const el = document.querySelector(elementId);
    if (el) {
        return new ApexCharts(el, options);
    }
    return null;
};

// Create area chart helper
window.createAreaChart = function(elementId, categories, series) {
    const options = {
        ...window.apexDefaultOptions,
        chart: {
            ...window.apexDefaultOptions.chart,
            type: 'area',
            height: 300
        },
        xaxis: {
            categories: categories,
            labels: {
                style: {
                    colors: '#6b7280',
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return formatBytes(val);
                },
                style: {
                    colors: '#6b7280',
                    fontSize: '11px'
                }
            }
        },
        series: series
    };

    const el = document.querySelector(elementId);
    if (el) {
        return new ApexCharts(el, options);
    }
    return null;
};

// Initialize Leaflet map for traffic visualization
window.initTrafficMap = function(elementId, options = {}) {
    const defaultOptions = {
        center: [20, 0],
        zoom: 2,
        minZoom: 2,
        maxZoom: 10
    };

    const mapOptions = { ...defaultOptions, ...options };
    const map = L.map(elementId, mapOptions);

    // Add tile layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    return {
        map: map,
        markers: new Map(),
        lines: [],

        // Update traffic points on map
        updateTrafficPoints: function(data) {
            // Clear existing markers
            this.markers.forEach(marker => marker.remove());
            this.markers.clear();

            data.forEach(point => {
                if (point.latitude && point.longitude) {
                    const radius = Math.min(20, Math.max(5, Math.log(point.bytes / 1000000) * 3));
                    const marker = L.circleMarker([point.latitude, point.longitude], {
                        radius: radius,
                        fillColor: window.monetxColors.primary,
                        color: window.monetxColors.secondary,
                        weight: 2,
                        opacity: 0.8,
                        fillOpacity: 0.6
                    }).addTo(this.map);

                    marker.bindPopup(`
                        <div class="text-sm">
                            <strong>${point.country_name || 'Unknown'}</strong><br>
                            ${point.city || ''}<br>
                            Traffic: ${formatBytes(point.bytes)}
                        </div>
                    `);

                    this.markers.set(point.country_code, marker);
                }
            });
        },

        // Draw traffic flow lines
        drawTrafficFlows: function(flows) {
            // Clear existing lines
            this.lines.forEach(line => line.remove());
            this.lines = [];

            flows.forEach(flow => {
                if (flow.src_lat && flow.dst_lat) {
                    const weight = Math.min(5, Math.max(1, Math.log(flow.bytes / 1000000)));
                    const line = L.polyline([
                        [flow.src_lat, flow.src_lng],
                        [flow.dst_lat, flow.dst_lng]
                    ], {
                        color: window.monetxColors.secondary,
                        weight: weight,
                        opacity: 0.6
                    }).addTo(this.map);

                    this.lines.push(line);
                }
            });
        },

        // Clear all markers and lines
        clear: function() {
            this.markers.forEach(marker => marker.remove());
            this.markers.clear();
            this.lines.forEach(line => line.remove());
            this.lines = [];
        }
    };
};

// Start Alpine
Alpine.start();
