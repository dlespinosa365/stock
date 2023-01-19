<?php

namespace App\Console\Commands;

use App\Helpers\MovementHelper;
use App\Models\Location;
use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Product;
use Illuminate\Console\Command;

class UpdateMovementsType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:movement_type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $movements = Movement::all();
        foreach ($movements as $movement) {
            $new_movement_type_id = MovementHelper::resolveMovementType($movement);
            if ($new_movement_type_id !== $movement->movement_type_id) {
                $this->info('new to update movement ' . $movement->id . ' form '. $movement->movement_type_id . ' to '. $new_movement_type_id);
                $movement->movement_type_id = $new_movement_type_id;
                $movement->save();
            }

        }
        return Command::SUCCESS;
    }
}
