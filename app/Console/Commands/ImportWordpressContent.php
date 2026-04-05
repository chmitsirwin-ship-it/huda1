<?php

namespace App\Console\Commands;

use App\Services\WordpressContentImporter;
use Illuminate\Console\Command;

class ImportWordpressContent extends Command
{
    protected $signature = 'import:wordpress-content {--type=all} {--limit=} {--dry-run}';

    protected $description = 'Import news and khutbas from WordPress';

    public function __construct(private WordpressContentImporter $importer)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $type = (string) $this->option('type');
        $limit = $this->option('limit');
        $dryRun = (bool) $this->option('dry-run');

        if (! in_array($type, ['all', 'news', 'khutbas'], true)) {
            $this->components->error(__('The selected import type is invalid.'));

            return self::FAILURE;
        }

        $summary = $this->importer->import(
            type: $type,
            dryRun: $dryRun,
            limit: $limit ? (int) $limit : null,
        );

        $this->table(['Section', 'Imported'], [
            [__('Khutba Categories'), $summary['khutba_categories']],
            [__('News Categories'), $summary['news_categories']],
            [__('Khutbas'), $summary['khutbas']],
            [__('News'), $summary['news']],
        ]);

        $this->components->info($dryRun ? __('Dry run completed successfully.') : __('WordPress content imported successfully.'));

        return self::SUCCESS;
    }
}
