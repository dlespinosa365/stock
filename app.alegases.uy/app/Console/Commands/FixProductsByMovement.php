<?php

namespace App\Console\Commands;

use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Product;
use Illuminate\Console\Command;

class FixProductsByMovement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:products';

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
        $products = Product::all();
        foreach ($products as $product) {
            $movement = Movement::where('product_id', $product->id)->orderBy('id', 'DESC')->first();
            if ($movement) {
                if (
                    $movement->movement_type_id === MovementType::$LOCAL_OUT ||
                    $movement->movement_type_id === MovementType::$CLIENT_OUT
                ) {
                    $product->is_out = true;
                    $product->current_location_id = null;
                    $this->info('UPDATE out true' . $product->serial_number);
                } else {
                    $product->is_out = false;
                    $product->current_location_id = $movement->location_to_id;
                    $this->info('UPDATE out false' . $product->serial_number);
                }
                $product->save();
            }
        }
        return Command::SUCCESS;
    }
}
