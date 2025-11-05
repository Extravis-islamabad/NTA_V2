<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TrafficAnalysisService;

class AggregateTraffic extends Command
{
    protected $signature = 'traffic:aggregate {interval=10min}';
    protected $description = 'Aggregate traffic statistics';

    private TrafficAnalysisService $trafficService;

    public function __construct(TrafficAnalysisService $trafficService)
    {
        parent::__construct();
        $this->trafficService = $trafficService;
    }

    public function handle()
    {
        $interval = $this->argument('interval');
        
        $this->info("Aggregating traffic data for interval: {$interval}");
        
        try {
            $this->trafficService->aggregateTrafficData($interval);
            $this->info('✓ Traffic aggregation completed successfully');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}