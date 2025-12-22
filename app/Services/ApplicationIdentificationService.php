<?php

namespace App\Services;

class ApplicationIdentificationService
{
    /**
     * Application categories with metadata
     */
    protected array $categories = [
        'Streaming' => ['icon' => 'play-circle', 'color' => '#E50914', 'priority' => 1],
        'Social Media' => ['icon' => 'users', 'color' => '#1877F2', 'priority' => 2],
        'Cloud Services' => ['icon' => 'cloud', 'color' => '#FF9900', 'priority' => 3],
        'Communication' => ['icon' => 'message-square', 'color' => '#2D8CFF', 'priority' => 4],
        'Productivity' => ['icon' => 'briefcase', 'color' => '#00A4EF', 'priority' => 5],
        'Gaming' => ['icon' => 'gamepad-2', 'color' => '#9146FF', 'priority' => 6],
        'Development' => ['icon' => 'code', 'color' => '#181717', 'priority' => 7],
        'Security' => ['icon' => 'shield', 'color' => '#F38020', 'priority' => 8],
        'E-commerce' => ['icon' => 'shopping-cart', 'color' => '#FF9900', 'priority' => 9],
        'Web' => ['icon' => 'globe', 'color' => '#6366F1', 'priority' => 10],
        'Email' => ['icon' => 'mail', 'color' => '#EF4444', 'priority' => 11],
        'File Transfer' => ['icon' => 'file-text', 'color' => '#F59E0B', 'priority' => 12],
        'Database' => ['icon' => 'database', 'color' => '#336791', 'priority' => 13],
        'Remote Access' => ['icon' => 'monitor', 'color' => '#0078D4', 'priority' => 14],
        'Network' => ['icon' => 'wifi', 'color' => '#8B5CF6', 'priority' => 15],
        'VPN' => ['icon' => 'lock', 'color' => '#10B981', 'priority' => 16],
        'CDN' => ['icon' => 'zap', 'color' => '#F38020', 'priority' => 17],
        'AI/ML' => ['icon' => 'cpu', 'color' => '#10A37F', 'priority' => 18],
        'Unknown' => ['icon' => 'help-circle', 'color' => '#6B7280', 'priority' => 99],
    ];

    /**
     * Well-known application IP ranges (CIDR notation)
     */
    protected array $ipRanges = [
        // Streaming Services
        'Netflix' => [
            'category' => 'Streaming',
            'icon' => 'film',
            'color' => '#E50914',
            'ranges' => [
                '23.246.0.0/18', '37.77.184.0/21', '45.57.0.0/17', '64.120.128.0/17',
                '66.197.128.0/17', '69.53.224.0/19', '108.175.32.0/20', '185.2.220.0/22',
                '185.9.188.0/22', '192.173.64.0/18', '198.38.96.0/19', '198.45.48.0/20',
                '208.75.76.0/22',
            ],
        ],
        'YouTube' => [
            'category' => 'Streaming',
            'icon' => 'play-circle',
            'color' => '#FF0000',
            'ranges' => [
                '208.117.252.0/24', '208.117.253.0/24', '208.117.254.0/24', '208.117.255.0/24',
                '208.65.153.0/24', '208.65.154.0/24', '208.117.236.0/24', '208.117.250.0/24',
            ],
        ],
        'Spotify' => [
            'category' => 'Streaming',
            'icon' => 'music',
            'color' => '#1DB954',
            'ranges' => [
                '35.186.224.0/20', '78.31.8.0/22', '193.182.8.0/22', '194.68.28.0/22',
                '194.68.169.0/24',
            ],
        ],
        'Twitch' => [
            'category' => 'Streaming',
            'icon' => 'tv',
            'color' => '#9146FF',
            'ranges' => [
                '52.223.192.0/18', '54.239.98.0/24', '99.181.64.0/21', '185.42.204.0/22',
                '192.108.150.0/24', '192.16.64.0/21', '199.9.248.0/21',
            ],
        ],
        'Disney+' => [
            'category' => 'Streaming',
            'icon' => 'tv',
            'color' => '#113CCF',
            'ranges' => [
                '54.231.0.0/17', '99.84.0.0/16',
            ],
        ],
        'HBO Max' => [
            'category' => 'Streaming',
            'icon' => 'tv',
            'color' => '#8500FF',
            'ranges' => [
                '173.222.0.0/15', '23.0.0.0/12',
            ],
        ],
        'Prime Video' => [
            'category' => 'Streaming',
            'icon' => 'tv',
            'color' => '#00A8E1',
            'ranges' => [
                '52.94.224.0/20', '54.239.0.0/17',
            ],
        ],

        // Social Media
        'Facebook' => [
            'category' => 'Social Media',
            'icon' => 'users',
            'color' => '#1877F2',
            'ranges' => [
                '31.13.24.0/21', '31.13.64.0/18', '45.64.40.0/22', '66.220.144.0/20',
                '69.63.176.0/20', '69.171.224.0/19', '74.119.76.0/22', '102.132.96.0/20',
                '103.4.96.0/22', '129.134.0.0/16', '147.75.208.0/20', '157.240.0.0/16',
                '173.252.64.0/18', '179.60.192.0/22', '185.60.216.0/22', '204.15.20.0/22',
            ],
        ],
        'Instagram' => [
            'category' => 'Social Media',
            'icon' => 'camera',
            'color' => '#E4405F',
            'ranges' => [
                '31.13.64.0/18', '157.240.0.0/16', '185.60.216.0/22',
            ],
        ],
        'WhatsApp' => [
            'category' => 'Communication',
            'icon' => 'message-circle',
            'color' => '#25D366',
            'ranges' => [
                '31.13.64.0/18', '157.240.0.0/16',
            ],
        ],
        'Twitter' => [
            'category' => 'Social Media',
            'icon' => 'hash',
            'color' => '#1DA1F2',
            'ranges' => [
                '69.195.160.0/19', '104.244.40.0/21', '185.45.4.0/22', '192.133.76.0/22',
                '199.16.156.0/22', '199.59.148.0/22', '199.96.56.0/21',
            ],
        ],
        'LinkedIn' => [
            'category' => 'Social Media',
            'icon' => 'briefcase',
            'color' => '#0A66C2',
            'ranges' => [
                '8.23.16.0/21', '8.23.119.0/24', '8.23.164.0/24', '91.225.248.0/23',
                '103.20.92.0/22', '108.174.0.0/20', '144.2.0.0/16', '185.63.144.0/22',
                '199.101.160.0/22', '216.52.16.0/20',
            ],
        ],
        'TikTok' => [
            'category' => 'Social Media',
            'icon' => 'smartphone',
            'color' => '#000000',
            'ranges' => [
                '5.180.60.0/22', '161.117.96.0/19', '184.28.240.0/22', '192.229.162.0/24',
                '203.107.236.0/22',
            ],
        ],
        'Snapchat' => [
            'category' => 'Social Media',
            'icon' => 'camera',
            'color' => '#FFFC00',
            'ranges' => [
                '3.167.0.0/16', '15.197.0.0/16', '52.223.0.0/17',
            ],
        ],
        'Reddit' => [
            'category' => 'Social Media',
            'icon' => 'message-square',
            'color' => '#FF4500',
            'ranges' => [
                '151.101.0.0/16', '199.232.0.0/16',
            ],
        ],
        'Pinterest' => [
            'category' => 'Social Media',
            'icon' => 'image',
            'color' => '#E60023',
            'ranges' => [
                '151.101.0.0/16',
            ],
        ],

        // Cloud Services
        'Google' => [
            'category' => 'Cloud Services',
            'icon' => 'search',
            'color' => '#4285F4',
            'ranges' => [
                '8.8.4.0/24', '8.8.8.0/24', '8.34.208.0/20', '8.35.192.0/20',
                '34.64.0.0/10', '35.184.0.0/13', '35.192.0.0/14', '35.196.0.0/15',
                '64.233.160.0/19', '66.102.0.0/20', '66.249.64.0/19', '70.32.128.0/19',
                '72.14.192.0/18', '74.125.0.0/16', '104.154.0.0/15', '104.196.0.0/14',
                '108.177.0.0/17', '142.250.0.0/15', '172.217.0.0/16', '172.253.0.0/16',
                '173.194.0.0/16', '209.85.128.0/17', '216.58.192.0/19', '216.239.32.0/19',
            ],
        ],
        'Microsoft' => [
            'category' => 'Cloud Services',
            'icon' => 'monitor',
            'color' => '#00A4EF',
            'ranges' => [
                '13.64.0.0/11', '13.96.0.0/13', '13.104.0.0/14', '20.0.0.0/11',
                '20.33.0.0/16', '20.34.0.0/15', '20.36.0.0/14', '20.40.0.0/13',
                '20.48.0.0/12', '20.64.0.0/10', '20.128.0.0/16', '23.96.0.0/13',
                '40.64.0.0/10', '40.128.0.0/12', '52.96.0.0/12', '52.112.0.0/14',
                '65.52.0.0/14', '104.40.0.0/13', '104.208.0.0/13', '131.253.0.0/16',
                '157.55.0.0/16', '157.56.0.0/14', '168.61.0.0/16', '168.62.0.0/15',
                '191.232.0.0/13', '207.46.0.0/16',
            ],
        ],
        'Amazon' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#FF9900',
            'ranges' => [
                '3.0.0.0/8', '13.32.0.0/15', '13.35.0.0/16', '15.177.0.0/18',
                '18.130.0.0/15', '18.132.0.0/14', '34.192.0.0/10', '35.153.0.0/16',
                '44.192.0.0/10', '50.16.0.0/15', '52.0.0.0/11', '52.32.0.0/11',
                '52.64.0.0/12', '54.64.0.0/11', '54.144.0.0/12', '54.176.0.0/13',
                '54.192.0.0/12', '54.208.0.0/13', '54.224.0.0/12', '63.32.0.0/14',
                '72.21.192.0/19', '99.77.128.0/18', '107.20.0.0/14', '174.129.0.0/16',
                '184.72.0.0/15', '205.251.192.0/18',
            ],
        ],
        'Cloudflare' => [
            'category' => 'CDN',
            'icon' => 'shield',
            'color' => '#F38020',
            'ranges' => [
                '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '104.16.0.0/13',
                '104.24.0.0/14', '108.162.192.0/18', '131.0.72.0/22', '141.101.64.0/18',
                '162.158.0.0/15', '172.64.0.0/13', '173.245.48.0/20', '188.114.96.0/20',
                '190.93.240.0/20', '197.234.240.0/22', '198.41.128.0/17',
            ],
        ],
        'Akamai' => [
            'category' => 'CDN',
            'icon' => 'zap',
            'color' => '#0096D6',
            'ranges' => [
                '23.0.0.0/12', '23.32.0.0/11', '23.64.0.0/14', '23.192.0.0/11',
                '72.246.0.0/15', '96.16.0.0/15', '104.64.0.0/10', '173.222.0.0/15',
                '184.24.0.0/13', '184.50.0.0/15', '184.84.0.0/14',
            ],
        ],
        'Fastly' => [
            'category' => 'CDN',
            'icon' => 'zap',
            'color' => '#FF282D',
            'ranges' => [
                '23.235.32.0/20', '43.249.72.0/22', '103.244.50.0/24', '103.245.222.0/23',
                '103.245.224.0/24', '104.156.80.0/20', '140.248.64.0/18', '146.75.0.0/17',
                '151.101.0.0/16', '157.52.64.0/18', '167.82.0.0/17', '172.111.64.0/18',
                '185.31.16.0/22', '199.27.72.0/21', '199.232.0.0/16',
            ],
        ],

        // Communication
        'Zoom' => [
            'category' => 'Communication',
            'icon' => 'video',
            'color' => '#2D8CFF',
            'ranges' => [
                '3.7.35.0/25', '3.21.137.128/25', '3.22.11.0/24', '3.23.93.0/24',
                '8.5.128.0/24', '13.52.6.128/25', '18.157.88.0/24', '50.239.202.0/23',
                '64.69.74.0/24', '64.211.144.0/24', '64.224.32.0/19', '69.174.57.0/24',
                '69.174.108.0/22', '144.195.0.0/16', '147.124.96.0/19', '149.137.0.0/17',
                '161.199.136.0/22', '162.12.232.0/22', '162.255.36.0/22', '170.114.0.0/16',
                '173.231.80.0/20', '207.226.132.0/24', '209.9.211.0/24', '213.19.144.0/24',
            ],
        ],
        'Slack' => [
            'category' => 'Communication',
            'icon' => 'message-square',
            'color' => '#4A154B',
            'ranges' => [
                '3.165.0.0/24', '13.32.68.0/22', '18.164.0.0/24', '54.192.195.0/24',
                '54.230.0.0/17', '54.230.128.0/18', '54.239.128.0/18', '99.86.0.0/16',
            ],
        ],
        'Discord' => [
            'category' => 'Communication',
            'icon' => 'message-circle',
            'color' => '#5865F2',
            'ranges' => [
                '66.22.196.0/22', '162.159.128.0/17', '66.22.230.0/24', '66.22.231.0/24',
                '66.22.232.0/24', '66.22.233.0/24',
            ],
        ],
        'Microsoft Teams' => [
            'category' => 'Communication',
            'icon' => 'video',
            'color' => '#6264A7',
            'ranges' => [
                '52.112.0.0/14', '52.120.0.0/14',
            ],
        ],
        'Webex' => [
            'category' => 'Communication',
            'icon' => 'video',
            'color' => '#00BCEB',
            'ranges' => [
                '64.68.96.0/19', '66.114.160.0/20', '66.163.32.0/19', '114.29.192.0/19',
                '150.253.128.0/17', '170.72.0.0/16', '170.133.128.0/18', '173.39.224.0/19',
                '173.243.0.0/20', '207.182.160.0/19', '209.197.192.0/19', '210.4.192.0/20',
            ],
        ],

        // Development
        'GitHub' => [
            'category' => 'Development',
            'icon' => 'github',
            'color' => '#181717',
            'ranges' => [
                '140.82.112.0/20', '143.55.64.0/20', '185.199.108.0/22', '192.30.252.0/22',
                '20.201.28.151/32', '20.205.243.166/32',
            ],
        ],
        'GitLab' => [
            'category' => 'Development',
            'icon' => 'git-branch',
            'color' => '#FC6D26',
            'ranges' => [
                '35.231.145.151/32', '34.74.90.64/28',
            ],
        ],
        'Bitbucket' => [
            'category' => 'Development',
            'icon' => 'git-branch',
            'color' => '#0052CC',
            'ranges' => [
                '104.192.136.0/21', '185.166.140.0/22',
            ],
        ],
        'npm' => [
            'category' => 'Development',
            'icon' => 'package',
            'color' => '#CB3837',
            'ranges' => [
                '104.16.0.0/12',
            ],
        ],
        'Docker Hub' => [
            'category' => 'Development',
            'icon' => 'box',
            'color' => '#2496ED',
            'ranges' => [
                '54.156.140.159/32', '52.72.232.213/32',
            ],
        ],

        // Gaming
        'Steam' => [
            'category' => 'Gaming',
            'icon' => 'gamepad-2',
            'color' => '#1B2838',
            'ranges' => [
                '103.10.124.0/23', '103.28.54.0/24', '146.66.152.0/23', '146.66.154.0/23',
                '146.66.156.0/23', '146.66.158.0/23', '155.133.224.0/22', '155.133.230.0/24',
                '155.133.232.0/23', '155.133.234.0/24', '155.133.236.0/22', '155.133.240.0/23',
                '155.133.244.0/23', '155.133.246.0/24', '155.133.248.0/21', '162.254.192.0/21',
                '185.25.180.0/23', '190.217.32.0/21', '192.69.96.0/22',
            ],
        ],
        'Xbox Live' => [
            'category' => 'Gaming',
            'icon' => 'gamepad-2',
            'color' => '#107C10',
            'ranges' => [
                '20.36.0.0/14', '40.64.0.0/10', '52.96.0.0/12',
            ],
        ],
        'PlayStation' => [
            'category' => 'Gaming',
            'icon' => 'gamepad-2',
            'color' => '#003087',
            'ranges' => [
                '103.19.36.0/22', '103.232.136.0/22',
            ],
        ],
        'Epic Games' => [
            'category' => 'Gaming',
            'icon' => 'gamepad-2',
            'color' => '#313131',
            'ranges' => [
                '3.217.42.64/26', '18.232.51.64/26', '52.0.64.0/26',
            ],
        ],
        'Riot Games' => [
            'category' => 'Gaming',
            'icon' => 'gamepad-2',
            'color' => '#D32936',
            'ranges' => [
                '104.160.128.0/17', '162.249.72.0/21', '192.64.170.0/23',
            ],
        ],
        'EA/Origin' => [
            'category' => 'Gaming',
            'icon' => 'gamepad-2',
            'color' => '#FF4747',
            'ranges' => [
                '159.153.0.0/16',
            ],
        ],

        // VPN Services
        'NordVPN' => [
            'category' => 'VPN',
            'icon' => 'lock',
            'color' => '#4687FF',
            'ranges' => [
                '185.230.126.0/23', '192.145.118.0/23',
            ],
        ],
        'ExpressVPN' => [
            'category' => 'VPN',
            'icon' => 'lock',
            'color' => '#DA3940',
            'ranges' => [
                '174.127.64.0/18',
            ],
        ],

        // AI/ML Services
        'OpenAI' => [
            'category' => 'AI/ML',
            'icon' => 'cpu',
            'color' => '#10A37F',
            'ranges' => [
                '13.65.0.0/16', '40.84.0.0/14',
            ],
        ],
        'Anthropic' => [
            'category' => 'AI/ML',
            'icon' => 'cpu',
            'color' => '#D97706',
            'ranges' => [
                '35.185.0.0/16',
            ],
        ],

        // E-commerce
        'Shopify' => [
            'category' => 'E-commerce',
            'icon' => 'shopping-cart',
            'color' => '#96BF48',
            'ranges' => [
                '23.227.32.0/22', '104.16.0.0/12',
            ],
        ],
        'eBay' => [
            'category' => 'E-commerce',
            'icon' => 'shopping-cart',
            'color' => '#E53238',
            'ranges' => [
                '66.135.192.0/18', '66.211.160.0/19',
            ],
        ],
        'PayPal' => [
            'category' => 'E-commerce',
            'icon' => 'credit-card',
            'color' => '#003087',
            'ranges' => [
                '64.4.240.0/21', '66.211.168.0/22', '173.0.80.0/20', '184.105.184.0/22',
            ],
        ],
        'Stripe' => [
            'category' => 'E-commerce',
            'icon' => 'credit-card',
            'color' => '#635BFF',
            'ranges' => [
                '35.244.0.0/14',
            ],
        ],

        // Apple Services
        'Apple' => [
            'category' => 'Cloud Services',
            'icon' => 'smartphone',
            'color' => '#555555',
            'ranges' => [
                '17.0.0.0/8', '63.92.224.0/19', '144.178.0.0/16', '192.35.50.0/24',
            ],
        ],

        // Additional Cloud Providers
        'DigitalOcean' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#0080FF',
            'ranges' => [
                '104.131.0.0/16', '104.236.0.0/16', '138.68.0.0/16', '138.197.0.0/16',
                '139.59.0.0/16', '142.93.0.0/16', '146.185.128.0/17', '159.65.0.0/16',
                '159.89.0.0/16', '161.35.0.0/16', '162.243.0.0/16', '167.71.0.0/16',
                '167.99.0.0/16', '178.62.0.0/16', '178.128.0.0/16', '188.166.0.0/16',
                '188.226.128.0/17', '192.241.128.0/17', '198.199.64.0/18', '206.189.0.0/16',
                '207.154.192.0/18', '209.97.128.0/17',
            ],
        ],
        'Linode' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#00A95C',
            'ranges' => [
                '45.33.0.0/17', '45.56.64.0/18', '45.79.0.0/17', '50.116.0.0/17',
                '66.175.208.0/20', '69.164.192.0/19', '72.14.176.0/20', '74.207.224.0/19',
                '96.126.96.0/19', '97.107.128.0/17', '139.144.0.0/16', '139.162.0.0/16',
                '162.216.16.0/22', '172.104.0.0/15', '173.255.192.0/18', '176.58.64.0/18',
                '178.79.128.0/17', '192.155.80.0/20', '194.195.240.0/21', '198.58.96.0/19',
                '212.71.232.0/21', '213.52.128.0/17',
            ],
        ],
        'Vultr' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#007BFC',
            'ranges' => [
                '45.32.0.0/16', '45.63.0.0/17', '45.76.0.0/16', '45.77.0.0/16',
                '64.156.0.0/16', '64.176.0.0/16', '66.42.32.0/19', '78.141.192.0/18',
                '95.179.128.0/17', '104.156.224.0/19', '104.207.128.0/18', '108.61.0.0/16',
                '136.244.64.0/18', '140.82.0.0/17', '149.28.0.0/16', '155.138.128.0/17',
                '199.247.0.0/17', '207.148.64.0/18', '209.250.224.0/19', '216.128.128.0/17',
            ],
        ],
        'OVH' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#123F6D',
            'ranges' => [
                '37.59.0.0/16', '37.187.0.0/16', '46.105.0.0/16', '51.68.0.0/16',
                '51.75.0.0/16', '51.77.0.0/16', '51.79.0.0/16', '51.81.0.0/16',
                '51.83.0.0/16', '51.89.0.0/16', '51.91.0.0/16', '51.161.0.0/16',
                '54.36.0.0/16', '54.37.0.0/16', '54.38.0.0/16', '54.39.0.0/16',
                '87.98.128.0/17', '91.121.0.0/16', '92.222.0.0/16', '135.125.0.0/16',
                '137.74.0.0/16', '139.99.0.0/16', '141.94.0.0/16', '141.95.0.0/16',
                '145.239.0.0/16', '147.135.0.0/16', '149.56.0.0/16', '149.202.0.0/16',
                '151.80.0.0/16', '164.132.0.0/16', '167.114.0.0/16', '176.31.0.0/16',
                '178.32.0.0/16', '188.165.0.0/16', '193.70.0.0/16', '198.27.64.0/18',
                '198.50.128.0/17', '198.100.144.0/20',
            ],
        ],
        'Hetzner' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#D50C2D',
            'ranges' => [
                '5.9.0.0/16', '5.75.128.0/17', '23.88.0.0/17', '49.12.0.0/14',
                '65.21.0.0/16', '65.108.0.0/16', '78.46.0.0/15', '88.99.0.0/16',
                '88.198.0.0/16', '91.107.128.0/17', '95.216.0.0/15', '116.202.0.0/16',
                '116.203.0.0/16', '128.140.0.0/17', '135.181.0.0/16', '136.243.0.0/16',
                '138.201.0.0/16', '142.132.128.0/17', '144.76.0.0/16', '148.251.0.0/16',
                '157.90.0.0/16', '159.69.0.0/16', '162.55.0.0/16', '167.233.0.0/16',
                '168.119.0.0/16', '176.9.0.0/16', '178.63.0.0/16', '188.40.0.0/16',
                '195.201.0.0/16', '213.133.96.0/19', '213.239.192.0/18',
            ],
        ],
        'Alibaba Cloud' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#FF6A00',
            'ranges' => [
                '47.74.0.0/15', '47.88.0.0/14', '47.92.0.0/14', '47.96.0.0/11',
                '101.132.0.0/14', '106.11.0.0/16', '106.14.0.0/15', '112.124.0.0/14',
                '115.28.0.0/15', '115.124.16.0/20', '116.62.0.0/15', '118.31.0.0/16',
                '118.178.0.0/16', '118.190.0.0/15', '119.23.0.0/16', '119.38.208.0/20',
                '120.24.0.0/14', '120.55.0.0/16', '120.76.0.0/14', '121.40.0.0/14',
                '121.196.0.0/14', '123.56.0.0/14', '139.196.0.0/14', '139.224.0.0/16',
                '140.205.0.0/16', '182.92.0.0/16',
            ],
        ],
        'Oracle Cloud' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#F80000',
            'ranges' => [
                '129.146.0.0/16', '129.148.0.0/15', '129.150.0.0/15', '129.152.0.0/16',
                '129.154.0.0/15', '129.156.0.0/15', '129.158.0.0/15', '130.35.0.0/16',
                '132.145.0.0/16', '134.65.0.0/16', '134.70.0.0/16', '140.83.0.0/16',
                '140.84.0.0/16', '140.91.0.0/16', '141.144.0.0/16', '141.145.0.0/16',
                '141.147.0.0/16', '144.21.0.0/16', '144.22.0.0/16', '144.24.0.0/15',
                '146.56.0.0/16', '147.154.0.0/15', '150.136.0.0/13', '152.67.0.0/16',
                '152.70.0.0/15', '155.248.0.0/15', '158.101.0.0/16', '158.178.0.0/15',
                '168.138.0.0/15', '192.9.128.0/17', '193.122.0.0/16', '193.123.0.0/16',
            ],
        ],
        'IBM Cloud' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#1261FE',
            'ranges' => [
                '50.23.0.0/16', '66.228.118.0/23', '67.228.0.0/16', '75.126.0.0/16',
                '108.168.128.0/17', '129.33.0.0/16', '129.34.0.0/16', '129.35.0.0/16',
                '129.36.0.0/16', '150.239.0.0/16', '158.175.0.0/16', '158.176.0.0/15',
                '159.8.0.0/16', '159.122.0.0/16', '161.156.0.0/16', '169.38.0.0/16',
                '169.44.0.0/14', '169.48.0.0/14', '169.53.0.0/16', '169.54.0.0/15',
                '169.56.0.0/14', '169.60.0.0/14', '174.36.0.0/16', '174.37.0.0/16',
                '208.43.0.0/16',
            ],
        ],

        // Additional Popular Services
        'Telegram' => [
            'category' => 'Communication',
            'icon' => 'message-circle',
            'color' => '#0088CC',
            'ranges' => [
                '91.108.4.0/22', '91.108.8.0/21', '91.108.16.0/21', '91.108.56.0/22',
                '109.239.140.0/24', '149.154.160.0/20', '185.76.151.0/24',
            ],
        ],
        'Signal' => [
            'category' => 'Communication',
            'icon' => 'message-circle',
            'color' => '#3A76F0',
            'ranges' => [
                '142.250.0.0/15', '172.217.0.0/16',
            ],
        ],
        'Dropbox' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#0061FF',
            'ranges' => [
                '108.160.160.0/20', '162.125.0.0/16', '199.47.216.0/22',
            ],
        ],
        'Box' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#0061D5',
            'ranges' => [
                '107.152.24.0/21', '185.166.128.0/22',
            ],
        ],
        'OneDrive' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#0078D4',
            'ranges' => [
                '13.107.0.0/16', '52.96.0.0/12',
            ],
        ],
        'iCloud' => [
            'category' => 'Cloud Services',
            'icon' => 'cloud',
            'color' => '#3693F3',
            'ranges' => [
                '17.248.0.0/14', '17.252.0.0/16',
            ],
        ],
        'Salesforce' => [
            'category' => 'Productivity',
            'icon' => 'briefcase',
            'color' => '#00A1E0',
            'ranges' => [
                '13.108.0.0/14', '96.43.144.0/20', '136.146.0.0/15', '161.71.0.0/17',
                '182.50.76.0/22', '185.79.140.0/22',
            ],
        ],
        'Atlassian' => [
            'category' => 'Productivity',
            'icon' => 'briefcase',
            'color' => '#0052CC',
            'ranges' => [
                '13.52.5.0/25', '13.236.8.0/25', '18.184.99.0/25', '18.234.32.0/25',
                '18.246.31.0/25', '52.215.192.0/25', '104.192.136.0/21', '185.166.140.0/22',
            ],
        ],
        'Notion' => [
            'category' => 'Productivity',
            'icon' => 'briefcase',
            'color' => '#000000',
            'ranges' => [
                '99.84.0.0/16', '143.204.0.0/16',
            ],
        ],
        'Figma' => [
            'category' => 'Productivity',
            'icon' => 'edit',
            'color' => '#F24E1E',
            'ranges' => [
                '18.66.0.0/16', '54.230.0.0/16',
            ],
        ],
        'Canva' => [
            'category' => 'Productivity',
            'icon' => 'edit',
            'color' => '#00C4CC',
            'ranges' => [
                '13.236.0.0/14', '52.62.0.0/15',
            ],
        ],
        'Adobe' => [
            'category' => 'Productivity',
            'icon' => 'edit',
            'color' => '#FF0000',
            'ranges' => [
                '66.117.16.0/20', '66.235.0.0/16', '130.248.0.0/16', '153.32.0.0/14',
                '185.34.188.0/22', '192.147.117.0/24', '192.150.0.0/16', '192.243.224.0/19',
                '193.104.215.0/24', '193.169.128.0/17', '208.77.40.0/22',
            ],
        ],
        'Uber' => [
            'category' => 'E-commerce',
            'icon' => 'truck',
            'color' => '#000000',
            'ranges' => [
                '35.167.0.0/16', '52.42.0.0/16', '54.191.0.0/16',
            ],
        ],
        'DoorDash' => [
            'category' => 'E-commerce',
            'icon' => 'truck',
            'color' => '#FF3008',
            'ranges' => [
                '34.192.0.0/10', '52.0.0.0/11',
            ],
        ],
        'Okta' => [
            'category' => 'Security',
            'icon' => 'shield',
            'color' => '#007DC1',
            'ranges' => [
                '75.98.88.0/21', '99.86.0.0/16', '104.98.80.0/20',
            ],
        ],
        'Auth0' => [
            'category' => 'Security',
            'icon' => 'shield',
            'color' => '#EB5424',
            'ranges' => [
                '35.167.74.0/24', '52.40.0.0/14',
            ],
        ],
        'Datadog' => [
            'category' => 'Development',
            'icon' => 'activity',
            'color' => '#632CA6',
            'ranges' => [
                '3.233.144.0/20', '18.210.0.0/16', '44.232.119.0/26', '44.241.198.0/26',
            ],
        ],
        'Sentry' => [
            'category' => 'Development',
            'icon' => 'activity',
            'color' => '#362D59',
            'ranges' => [
                '35.184.0.0/13', '35.192.0.0/14',
            ],
        ],
        'NewRelic' => [
            'category' => 'Development',
            'icon' => 'activity',
            'color' => '#008C99',
            'ranges' => [
                '162.247.240.0/22', '185.221.84.0/22',
            ],
        ],
        'Vercel' => [
            'category' => 'Development',
            'icon' => 'code',
            'color' => '#000000',
            'ranges' => [
                '76.76.21.0/24', '76.223.0.0/16',
            ],
        ],
        'Netlify' => [
            'category' => 'Development',
            'icon' => 'code',
            'color' => '#00C7B7',
            'ranges' => [
                '104.198.14.0/24', '151.101.0.0/16',
            ],
        ],
    ];

    /**
     * Port to application mappings with categories
     */
    protected array $portMappings = [
        // Web
        80 => ['name' => 'HTTP', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'],
        443 => ['name' => 'HTTPS', 'category' => 'Web', 'icon' => 'lock', 'color' => '#10B981'],
        8080 => ['name' => 'HTTP-Alt', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'],
        8443 => ['name' => 'HTTPS-Alt', 'category' => 'Web', 'icon' => 'lock', 'color' => '#10B981'],

        // Email
        25 => ['name' => 'SMTP', 'category' => 'Email', 'icon' => 'mail', 'color' => '#EF4444'],
        110 => ['name' => 'POP3', 'category' => 'Email', 'icon' => 'mail', 'color' => '#EF4444'],
        143 => ['name' => 'IMAP', 'category' => 'Email', 'icon' => 'mail', 'color' => '#EF4444'],
        465 => ['name' => 'SMTPS', 'category' => 'Email', 'icon' => 'mail', 'color' => '#EF4444'],
        587 => ['name' => 'SMTP-Submission', 'category' => 'Email', 'icon' => 'mail', 'color' => '#EF4444'],
        993 => ['name' => 'IMAPS', 'category' => 'Email', 'icon' => 'mail', 'color' => '#EF4444'],
        995 => ['name' => 'POP3S', 'category' => 'Email', 'icon' => 'mail', 'color' => '#EF4444'],

        // File Transfer
        20 => ['name' => 'FTP-DATA', 'category' => 'File Transfer', 'icon' => 'file-text', 'color' => '#F59E0B'],
        21 => ['name' => 'FTP', 'category' => 'File Transfer', 'icon' => 'file-text', 'color' => '#F59E0B'],
        69 => ['name' => 'TFTP', 'category' => 'File Transfer', 'icon' => 'file-text', 'color' => '#F59E0B'],
        115 => ['name' => 'SFTP', 'category' => 'File Transfer', 'icon' => 'file-text', 'color' => '#F59E0B'],
        873 => ['name' => 'rsync', 'category' => 'File Transfer', 'icon' => 'file-text', 'color' => '#F59E0B'],

        // Remote Access
        22 => ['name' => 'SSH', 'category' => 'Remote Access', 'icon' => 'terminal', 'color' => '#1F2937'],
        23 => ['name' => 'Telnet', 'category' => 'Remote Access', 'icon' => 'terminal', 'color' => '#1F2937'],
        3389 => ['name' => 'RDP', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#0078D4'],
        5900 => ['name' => 'VNC', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#444444'],
        5901 => ['name' => 'VNC', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#444444'],
        5902 => ['name' => 'VNC', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#444444'],

        // Database
        3306 => ['name' => 'MySQL', 'category' => 'Database', 'icon' => 'database', 'color' => '#4479A1'],
        5432 => ['name' => 'PostgreSQL', 'category' => 'Database', 'icon' => 'database', 'color' => '#336791'],
        1433 => ['name' => 'MSSQL', 'category' => 'Database', 'icon' => 'database', 'color' => '#CC2927'],
        1434 => ['name' => 'MSSQL', 'category' => 'Database', 'icon' => 'database', 'color' => '#CC2927'],
        1521 => ['name' => 'Oracle', 'category' => 'Database', 'icon' => 'database', 'color' => '#F80000'],
        6379 => ['name' => 'Redis', 'category' => 'Database', 'icon' => 'database', 'color' => '#DC382D'],
        27017 => ['name' => 'MongoDB', 'category' => 'Database', 'icon' => 'database', 'color' => '#47A248'],
        27018 => ['name' => 'MongoDB', 'category' => 'Database', 'icon' => 'database', 'color' => '#47A248'],
        27019 => ['name' => 'MongoDB', 'category' => 'Database', 'icon' => 'database', 'color' => '#47A248'],
        9200 => ['name' => 'Elasticsearch', 'category' => 'Database', 'icon' => 'database', 'color' => '#005571'],
        11211 => ['name' => 'Memcached', 'category' => 'Database', 'icon' => 'database', 'color' => '#3E6E93'],
        5984 => ['name' => 'CouchDB', 'category' => 'Database', 'icon' => 'database', 'color' => '#E42528'],
        7474 => ['name' => 'Neo4j', 'category' => 'Database', 'icon' => 'database', 'color' => '#008CC1'],
        9042 => ['name' => 'Cassandra', 'category' => 'Database', 'icon' => 'database', 'color' => '#1287B1'],

        // Network Services
        53 => ['name' => 'DNS', 'category' => 'Network', 'icon' => 'server', 'color' => '#8B5CF6'],
        67 => ['name' => 'DHCP', 'category' => 'Network', 'icon' => 'wifi', 'color' => '#8B5CF6'],
        68 => ['name' => 'DHCP', 'category' => 'Network', 'icon' => 'wifi', 'color' => '#8B5CF6'],
        123 => ['name' => 'NTP', 'category' => 'Network', 'icon' => 'clock', 'color' => '#8B5CF6'],
        161 => ['name' => 'SNMP', 'category' => 'Network', 'icon' => 'activity', 'color' => '#8B5CF6'],
        162 => ['name' => 'SNMP-Trap', 'category' => 'Network', 'icon' => 'activity', 'color' => '#8B5CF6'],
        179 => ['name' => 'BGP', 'category' => 'Network', 'icon' => 'git-branch', 'color' => '#8B5CF6'],
        389 => ['name' => 'LDAP', 'category' => 'Network', 'icon' => 'users', 'color' => '#8B5CF6'],
        636 => ['name' => 'LDAPS', 'category' => 'Network', 'icon' => 'users', 'color' => '#8B5CF6'],
        853 => ['name' => 'DNS-over-TLS', 'category' => 'Network', 'icon' => 'server', 'color' => '#8B5CF6'],
        514 => ['name' => 'Syslog', 'category' => 'Network', 'icon' => 'file-text', 'color' => '#8B5CF6'],
        520 => ['name' => 'RIP', 'category' => 'Network', 'icon' => 'git-branch', 'color' => '#8B5CF6'],
        88 => ['name' => 'Kerberos', 'category' => 'Security', 'icon' => 'shield', 'color' => '#F38020'],
        135 => ['name' => 'RPC', 'category' => 'Network', 'icon' => 'server', 'color' => '#8B5CF6'],
        137 => ['name' => 'NetBIOS', 'category' => 'Network', 'icon' => 'server', 'color' => '#8B5CF6'],
        138 => ['name' => 'NetBIOS', 'category' => 'Network', 'icon' => 'server', 'color' => '#8B5CF6'],
        139 => ['name' => 'NetBIOS', 'category' => 'Network', 'icon' => 'server', 'color' => '#8B5CF6'],
        445 => ['name' => 'SMB', 'category' => 'File Transfer', 'icon' => 'folder', 'color' => '#0078D4'],
        2049 => ['name' => 'NFS', 'category' => 'File Transfer', 'icon' => 'folder', 'color' => '#F59E0B'],
        1812 => ['name' => 'RADIUS', 'category' => 'Security', 'icon' => 'shield', 'color' => '#F38020'],
        1813 => ['name' => 'RADIUS', 'category' => 'Security', 'icon' => 'shield', 'color' => '#F38020'],

        // VPN
        500 => ['name' => 'IKE', 'category' => 'VPN', 'icon' => 'lock', 'color' => '#10B981'],
        1194 => ['name' => 'OpenVPN', 'category' => 'VPN', 'icon' => 'lock', 'color' => '#10B981'],
        1701 => ['name' => 'L2TP', 'category' => 'VPN', 'icon' => 'lock', 'color' => '#10B981'],
        1723 => ['name' => 'PPTP', 'category' => 'VPN', 'icon' => 'lock', 'color' => '#10B981'],
        4500 => ['name' => 'IPSec-NAT', 'category' => 'VPN', 'icon' => 'lock', 'color' => '#10B981'],
        51820 => ['name' => 'WireGuard', 'category' => 'VPN', 'icon' => 'lock', 'color' => '#88171A'],

        // VoIP/Communication
        5060 => ['name' => 'SIP', 'category' => 'Communication', 'icon' => 'phone', 'color' => '#2D8CFF'],
        5061 => ['name' => 'SIP-TLS', 'category' => 'Communication', 'icon' => 'phone', 'color' => '#2D8CFF'],
        5222 => ['name' => 'XMPP', 'category' => 'Communication', 'icon' => 'message-square', 'color' => '#2D8CFF'],
        5223 => ['name' => 'XMPP-SSL', 'category' => 'Communication', 'icon' => 'message-square', 'color' => '#2D8CFF'],
        3478 => ['name' => 'STUN', 'category' => 'Communication', 'icon' => 'phone', 'color' => '#2D8CFF'],
        3479 => ['name' => 'TURN', 'category' => 'Communication', 'icon' => 'phone', 'color' => '#2D8CFF'],

        // Proxy
        1080 => ['name' => 'SOCKS', 'category' => 'Network', 'icon' => 'shuffle', 'color' => '#6B7280'],
        3128 => ['name' => 'Squid', 'category' => 'Network', 'icon' => 'shuffle', 'color' => '#6B7280'],
        8888 => ['name' => 'HTTP-Proxy', 'category' => 'Network', 'icon' => 'shuffle', 'color' => '#6B7280'],

        // Development/Container
        2375 => ['name' => 'Docker', 'category' => 'Development', 'icon' => 'box', 'color' => '#2496ED'],
        2376 => ['name' => 'Docker-TLS', 'category' => 'Development', 'icon' => 'box', 'color' => '#2496ED'],
        9000 => ['name' => 'PHP-FPM', 'category' => 'Development', 'icon' => 'code', 'color' => '#777BB4'],
        6443 => ['name' => 'Kubernetes', 'category' => 'Development', 'icon' => 'box', 'color' => '#326CE5'],
        10250 => ['name' => 'Kubelet', 'category' => 'Development', 'icon' => 'box', 'color' => '#326CE5'],
        2379 => ['name' => 'etcd', 'category' => 'Development', 'icon' => 'database', 'color' => '#419EDA'],

        // Gaming
        25565 => ['name' => 'Minecraft', 'category' => 'Gaming', 'icon' => 'gamepad-2', 'color' => '#62B47A'],
        27015 => ['name' => 'Steam', 'category' => 'Gaming', 'icon' => 'gamepad-2', 'color' => '#1B2838'],
        27016 => ['name' => 'Steam', 'category' => 'Gaming', 'icon' => 'gamepad-2', 'color' => '#1B2838'],
        27017 => ['name' => 'Steam', 'category' => 'Gaming', 'icon' => 'gamepad-2', 'color' => '#1B2838'],
        19132 => ['name' => 'Minecraft-Bedrock', 'category' => 'Gaming', 'icon' => 'gamepad-2', 'color' => '#62B47A'],
        19133 => ['name' => 'Minecraft-Bedrock', 'category' => 'Gaming', 'icon' => 'gamepad-2', 'color' => '#62B47A'],

        // Additional Web/API Services
        3000 => ['name' => 'Dev-Server', 'category' => 'Development', 'icon' => 'code', 'color' => '#181717'],
        4000 => ['name' => 'Dev-Server', 'category' => 'Development', 'icon' => 'code', 'color' => '#181717'],
        4200 => ['name' => 'Angular', 'category' => 'Development', 'icon' => 'code', 'color' => '#DD0031'],
        5000 => ['name' => 'Flask/API', 'category' => 'Development', 'icon' => 'code', 'color' => '#000000'],
        5173 => ['name' => 'Vite', 'category' => 'Development', 'icon' => 'code', 'color' => '#646CFF'],
        5500 => ['name' => 'LiveServer', 'category' => 'Development', 'icon' => 'code', 'color' => '#007ACC'],
        8000 => ['name' => 'Web-Service', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'],
        8001 => ['name' => 'Web-Service', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'],
        8008 => ['name' => 'HTTP-Alt', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'],
        8081 => ['name' => 'HTTP-Proxy', 'category' => 'Network', 'icon' => 'shuffle', 'color' => '#6B7280'],
        8082 => ['name' => 'HTTP-Proxy', 'category' => 'Network', 'icon' => 'shuffle', 'color' => '#6B7280'],
        8090 => ['name' => 'Web-Service', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'],
        8181 => ['name' => 'Web-Service', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'],
        8443 => ['name' => 'HTTPS-Alt', 'category' => 'Web', 'icon' => 'lock', 'color' => '#10B981'],
        8880 => ['name' => 'Web-Service', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'],
        8888 => ['name' => 'HTTP-Proxy', 'category' => 'Network', 'icon' => 'shuffle', 'color' => '#6B7280'],
        9080 => ['name' => 'WebSphere', 'category' => 'Web', 'icon' => 'globe', 'color' => '#054ADA'],
        9090 => ['name' => 'Prometheus', 'category' => 'Development', 'icon' => 'activity', 'color' => '#E6522C'],
        9091 => ['name' => 'Prometheus', 'category' => 'Development', 'icon' => 'activity', 'color' => '#E6522C'],
        9100 => ['name' => 'Node-Exporter', 'category' => 'Development', 'icon' => 'activity', 'color' => '#E6522C'],
        9443 => ['name' => 'HTTPS-Alt', 'category' => 'Web', 'icon' => 'lock', 'color' => '#10B981'],

        // Monitoring/Logging
        3000 => ['name' => 'Grafana', 'category' => 'Development', 'icon' => 'activity', 'color' => '#F46800'],
        5601 => ['name' => 'Kibana', 'category' => 'Development', 'icon' => 'activity', 'color' => '#005571'],
        9200 => ['name' => 'Elasticsearch', 'category' => 'Database', 'icon' => 'database', 'color' => '#005571'],
        9300 => ['name' => 'Elasticsearch', 'category' => 'Database', 'icon' => 'database', 'color' => '#005571'],

        // Message Queues
        5672 => ['name' => 'RabbitMQ', 'category' => 'Development', 'icon' => 'git-branch', 'color' => '#FF6600'],
        15672 => ['name' => 'RabbitMQ-Mgmt', 'category' => 'Development', 'icon' => 'git-branch', 'color' => '#FF6600'],
        9092 => ['name' => 'Kafka', 'category' => 'Development', 'icon' => 'git-branch', 'color' => '#231F20'],
        2181 => ['name' => 'ZooKeeper', 'category' => 'Development', 'icon' => 'git-branch', 'color' => '#3C4043'],
        4369 => ['name' => 'EPMD', 'category' => 'Network', 'icon' => 'server', 'color' => '#8B5CF6'],

        // Additional Services
        111 => ['name' => 'RPC-Portmapper', 'category' => 'Network', 'icon' => 'server', 'color' => '#8B5CF6'],
        548 => ['name' => 'AFP', 'category' => 'File Transfer', 'icon' => 'folder', 'color' => '#555555'],
        631 => ['name' => 'IPP', 'category' => 'Network', 'icon' => 'printer', 'color' => '#8B5CF6'],
        843 => ['name' => 'Flash-Policy', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'],
        902 => ['name' => 'VMware', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#607078'],
        903 => ['name' => 'VMware', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#607078'],
        1099 => ['name' => 'Java-RMI', 'category' => 'Development', 'icon' => 'code', 'color' => '#007396'],
        1433 => ['name' => 'MSSQL', 'category' => 'Database', 'icon' => 'database', 'color' => '#CC2927'],
        1434 => ['name' => 'MSSQL-UDP', 'category' => 'Database', 'icon' => 'database', 'color' => '#CC2927'],
        1883 => ['name' => 'MQTT', 'category' => 'Network', 'icon' => 'activity', 'color' => '#660066'],
        8883 => ['name' => 'MQTT-TLS', 'category' => 'Network', 'icon' => 'activity', 'color' => '#660066'],
        1935 => ['name' => 'RTMP', 'category' => 'Streaming', 'icon' => 'video', 'color' => '#E50914'],
        3283 => ['name' => 'Apple-Remote', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#555555'],
        5353 => ['name' => 'mDNS', 'category' => 'Network', 'icon' => 'wifi', 'color' => '#8B5CF6'],
        5355 => ['name' => 'LLMNR', 'category' => 'Network', 'icon' => 'wifi', 'color' => '#8B5CF6'],
        5357 => ['name' => 'WSDAPI', 'category' => 'Network', 'icon' => 'server', 'color' => '#8B5CF6'],
        5938 => ['name' => 'TeamViewer', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#0E8EE9'],
        6000 => ['name' => 'X11', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#444444'],
        6001 => ['name' => 'X11', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#444444'],
        6881 => ['name' => 'BitTorrent', 'category' => 'File Transfer', 'icon' => 'download', 'color' => '#228B22'],
        7070 => ['name' => 'RTSP', 'category' => 'Streaming', 'icon' => 'video', 'color' => '#E50914'],
        8291 => ['name' => 'Mikrotik-API', 'category' => 'Network', 'icon' => 'server', 'color' => '#293239'],
        8728 => ['name' => 'Mikrotik-API', 'category' => 'Network', 'icon' => 'server', 'color' => '#293239'],
        8729 => ['name' => 'Mikrotik-API-SSL', 'category' => 'Network', 'icon' => 'server', 'color' => '#293239'],
        10000 => ['name' => 'Webmin', 'category' => 'Remote Access', 'icon' => 'monitor', 'color' => '#5A5A5A'],
    ];

    /**
     * Identify application from flow data
     */
    public function identify(string $srcIp, string $dstIp, int $srcPort, int $dstPort, string $protocol): array
    {
        // First try to identify by destination IP
        $result = $this->identifyByIP($dstIp);
        if ($result) {
            return $result;
        }

        // Try source IP
        $result = $this->identifyByIP($srcIp);
        if ($result) {
            return $result;
        }

        // Fall back to port-based identification
        $result = $this->identifyByPort($dstPort);
        if ($result) {
            return $result;
        }

        // Try source port as well
        $result = $this->identifyByPort($srcPort);
        if ($result) {
            return $result;
        }

        // Try to identify based on well-known port ranges
        $result = $this->identifyByPortRange($srcPort, $dstPort, $protocol);
        if ($result) {
            return $result;
        }

        // Return unknown
        return [
            'name' => 'Unknown',
            'category' => 'Unknown',
            'icon' => 'help-circle',
            'color' => '#6B7280',
        ];
    }

    /**
     * Identify application by port ranges for common services
     */
    protected function identifyByPortRange(int $srcPort, int $dstPort, string $protocol): ?array
    {
        $checkPort = max($srcPort, $dstPort);
        $lowPort = min($srcPort, $dstPort);

        // High ports typically used by specific applications
        if ($checkPort >= 8000 && $checkPort <= 9999) {
            return ['name' => 'Web-Service', 'category' => 'Web', 'icon' => 'globe', 'color' => '#6366F1'];
        }

        // Common gaming ports
        if (($checkPort >= 27000 && $checkPort <= 27050) || ($checkPort >= 7777 && $checkPort <= 7799)) {
            return ['name' => 'Gaming', 'category' => 'Gaming', 'icon' => 'gamepad-2', 'color' => '#9146FF'];
        }

        // VoIP/Streaming ports
        if ($checkPort >= 16384 && $checkPort <= 32767 && strtoupper($protocol) === 'UDP') {
            return ['name' => 'Media-Stream', 'category' => 'Streaming', 'icon' => 'video', 'color' => '#E50914'];
        }

        // Dynamic/ephemeral ports with low ports indicate client connections
        if ($lowPort <= 1024 && $checkPort >= 49152) {
            // Client connecting to a well-known service
            $result = $this->identifyByPort($lowPort);
            if ($result) {
                return $result;
            }
        }

        return null;
    }

    /**
     * Identify application by IP address
     */
    public function identifyByIP(string $ip): ?array
    {
        foreach ($this->ipRanges as $appName => $appData) {
            foreach ($appData['ranges'] as $range) {
                if ($this->ipInRange($ip, $range)) {
                    return [
                        'name' => $appName,
                        'category' => $appData['category'],
                        'icon' => $appData['icon'],
                        'color' => $appData['color'],
                    ];
                }
            }
        }
        return null;
    }

    /**
     * Identify application by port
     */
    public function identifyByPort(int $port): ?array
    {
        if (!isset($this->portMappings[$port])) {
            return null;
        }

        $mapping = $this->portMappings[$port];
        return [
            'name' => $mapping['name'],
            'category' => $mapping['category'],
            'icon' => $mapping['icon'],
            'color' => $mapping['color'],
        ];
    }

    /**
     * Get application info (icon, color, category)
     */
    public function getApplicationInfo(string $application): array
    {
        // Check IP ranges
        foreach ($this->ipRanges as $appName => $appData) {
            if (strcasecmp($appName, $application) === 0) {
                return [
                    'name' => $appName,
                    'icon' => $appData['icon'],
                    'color' => $appData['color'],
                    'category' => $appData['category'],
                ];
            }
        }

        // Check port mappings
        foreach ($this->portMappings as $mapping) {
            if (strcasecmp($mapping['name'], $application) === 0) {
                return [
                    'name' => $mapping['name'],
                    'icon' => $mapping['icon'],
                    'color' => $mapping['color'],
                    'category' => $mapping['category'],
                ];
            }
        }

        return [
            'name' => $application,
            'icon' => 'help-circle',
            'color' => '#6B7280',
            'category' => 'Unknown',
        ];
    }

    /**
     * Analyze a flow and return application info (legacy method for compatibility)
     */
    public function analyzeFlow(string $sourceIp, string $destIp, int $destPort = 0, ?string $existingApp = null): array
    {
        // If existing app is valid, return its info
        if ($existingApp && $existingApp !== 'Unknown') {
            return $this->getApplicationInfo($existingApp);
        }

        return $this->identify($sourceIp, $destIp, 0, $destPort, 'TCP');
    }

    /**
     * Get all categories with metadata
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Get all applications grouped by category
     */
    public function getApplicationsByCategory(): array
    {
        $byCategory = [];

        foreach ($this->ipRanges as $appName => $appData) {
            $category = $appData['category'];
            if (!isset($byCategory[$category])) {
                $byCategory[$category] = [];
            }
            $byCategory[$category][] = [
                'name' => $appName,
                'icon' => $appData['icon'],
                'color' => $appData['color'],
            ];
        }

        return $byCategory;
    }

    /**
     * Get all application names
     */
    public function getAllApplications(): array
    {
        $apps = [];

        foreach ($this->ipRanges as $appName => $appData) {
            $apps[$appName] = [
                'icon' => $appData['icon'],
                'color' => $appData['color'],
                'category' => $appData['category'],
            ];
        }

        foreach ($this->portMappings as $port => $mapping) {
            if (!isset($apps[$mapping['name']])) {
                $apps[$mapping['name']] = [
                    'icon' => $mapping['icon'],
                    'color' => $mapping['color'],
                    'category' => $mapping['category'],
                ];
            }
        }

        return $apps;
    }

    /**
     * Check if IP is in CIDR range
     */
    protected function ipInRange(string $ip, string $range): bool
    {
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }

        [$subnet, $mask] = explode('/', $range);
        $mask = (int)$mask;

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);

        if ($ipLong === false || $subnetLong === false) {
            return false;
        }

        $maskLong = -1 << (32 - $mask);
        return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
    }
}
