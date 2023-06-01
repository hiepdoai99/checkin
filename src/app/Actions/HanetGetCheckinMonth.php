<?php

namespace App\Actions;

use App\Models\Core\Auth\User;
use Illuminate\Support\Carbon;
use Spatie\QueueableAction\QueueableAction;
use Htqxd\LaravelHanetApi\Models\HanetPlace;
use Illuminate\Support\Facades\Auth;

class HanetGetCheckinMonth
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
    public function execute(string $monthString)
    {
        $month = Carbon::createFromDate($monthString);
        $start = $month->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $place = HanetPlace::first();
     
        while ($start->lte($end)) {
            $datas = hanet()->person()
                ->getCheckinByPlaceIdInDay($place->placeID, $start->format('Y-m-d'))
                ->getData();

            if (is_array($datas)) {
                foreach ($datas as $checkin) {
                    if (!empty($checkin['aliasID'])) {
                        app(HanetPunchInManual::class)->execute($checkin);
                        // app(HanetPunchInManual::class)->onQueue()->execute($checkin);
                    }
                }
            }
            $start->addDay();
        }
    }
}
