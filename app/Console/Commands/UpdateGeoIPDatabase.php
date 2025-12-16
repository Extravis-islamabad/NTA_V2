<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class UpdateGeoIPDatabase extends Command
{
    protected $signature = 'geoip:update {--force : Force download even if database exists}';
    protected $description = 'Download or update MaxMind GeoLite2 databases';

    private const DATABASES = [
        'GeoLite2-City' => 'GeoLite2-City.mmdb',
        'GeoLite2-ASN' => 'GeoLite2-ASN.mmdb',
    ];

    public function handle(): int
    {
        $licenseKey = config('services.maxmind.license_key');
        $accountId = config('services.maxmind.account_id');
        $dbPath = config('services.maxmind.database_path');

        if (!$licenseKey) {
            $this->error('MaxMind license key not configured.');
            $this->info('Please set MAXMIND_LICENSE_KEY in your .env file.');
            $this->info('Sign up for free at: https://www.maxmind.com/en/geolite2/signup');
            return 1;
        }

        // Create directory if it doesn't exist
        if (!File::exists($dbPath)) {
            File::makeDirectory($dbPath, 0755, true);
            $this->info("Created directory: {$dbPath}");
        }

        $success = true;

        foreach (self::DATABASES as $edition => $filename) {
            $this->info("Processing {$edition}...");

            $targetPath = "{$dbPath}/{$filename}";

            // Check if update needed
            if (File::exists($targetPath) && !$this->option('force')) {
                $age = now()->diffInDays(File::lastModified($targetPath));
                if ($age < 7) {
                    $this->info("  {$filename} is up to date (updated {$age} days ago)");
                    continue;
                }
            }

            if (!$this->downloadDatabase($edition, $targetPath, $licenseKey, $accountId)) {
                $success = false;
            }
        }

        if ($success) {
            $this->info('');
            $this->info('GeoIP databases updated successfully!');
            return 0;
        }

        $this->error('Some databases failed to download.');
        return 1;
    }

    private function downloadDatabase(string $edition, string $targetPath, string $licenseKey, ?string $accountId): bool
    {
        $tempDir = storage_path('app/temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $tempFile = "{$tempDir}/{$edition}.tar.gz";

        // Download URL for MaxMind GeoLite2
        // Format: https://download.maxmind.com/geoip/databases/{edition}/download?suffix=tar.gz
        $url = "https://download.maxmind.com/geoip/databases/{$edition}/download?suffix=tar.gz";

        $this->info("  Downloading from MaxMind...");

        try {
            // Use basic auth with account ID and license key
            $auth = $accountId ? "{$accountId}:{$licenseKey}" : $licenseKey;

            $response = Http::withBasicAuth($accountId ?? $licenseKey, $licenseKey)
                ->timeout(300)
                ->withOptions(['sink' => $tempFile])
                ->get($url);

            if (!$response->successful()) {
                $this->error("  Failed to download {$edition}: HTTP " . $response->status());
                return false;
            }

            $this->info("  Downloaded successfully. Extracting...");

            // Extract the tar.gz file
            if (!$this->extractDatabase($tempFile, $edition, $targetPath)) {
                return false;
            }

            // Clean up temp file
            File::delete($tempFile);

            $this->info("  {$edition} updated successfully!");
            return true;

        } catch (\Exception $e) {
            $this->error("  Error downloading {$edition}: " . $e->getMessage());
            if (File::exists($tempFile)) {
                File::delete($tempFile);
            }
            return false;
        }
    }

    private function extractDatabase(string $tarFile, string $edition, string $targetPath): bool
    {
        $tempDir = storage_path('app/temp');

        try {
            // Use PharData to extract tar.gz
            $phar = new \PharData($tarFile);
            $phar->decompress(); // Creates .tar file

            $tarPath = str_replace('.tar.gz', '.tar', $tarFile);
            $phar = new \PharData($tarPath);
            $phar->extractTo($tempDir, null, true);

            // Find the .mmdb file in extracted directory
            $extractedDir = glob("{$tempDir}/{$edition}_*")[0] ?? null;
            if (!$extractedDir) {
                $this->error("  Could not find extracted directory for {$edition}");
                return false;
            }

            $mmdbFile = "{$extractedDir}/{$edition}.mmdb";
            if (!File::exists($mmdbFile)) {
                $this->error("  Could not find .mmdb file in extracted archive");
                return false;
            }

            // Move to target location
            File::copy($mmdbFile, $targetPath);

            // Clean up
            File::delete($tarPath);
            File::deleteDirectory($extractedDir);

            return true;

        } catch (\Exception $e) {
            $this->error("  Error extracting {$edition}: " . $e->getMessage());
            return false;
        }
    }
}
