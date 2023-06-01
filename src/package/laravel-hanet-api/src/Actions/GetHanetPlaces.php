<?php

namespace Htqxd\LaravelHanetApi\Actions;

use Htqxd\LaravelHanetApi\Models\HanetPlace;
use Illuminate\Support\Facades\DB;
use Spatie\QueueableAction\QueueableAction;

class GetHanetPlaces
{
    use QueueableAction;

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Prepare the action for execution, leveraging constructor injection.
    }

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function execute() : bool
    {
        $places = hanet()->place()->getPlaces()->getData();
        if (is_array($places) && count($places) > 0) {
            DB::table(app(HanetPlace::class)->getTable())->truncate();
            HanetPlace::insert($this->processData($places));
            return true;
        }
        return false;
    }

    private function processData(array $places) : array
    {
        $datas = [];
        foreach ($places as $place) {
            $place['placeID'] = $place['id'];
            unset($place['id'], $place['permission'], $place['linked']);
            array_push($datas, $place);
        }
        return $datas;
    }
}
