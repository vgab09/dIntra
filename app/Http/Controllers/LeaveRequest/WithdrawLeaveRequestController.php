<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019.04.04.
 * Time: 15:57
 */

namespace App\Http\Controllers\LeaveRequest;


use App\Http\Components\Calendar\Event;
use App\Http\Controllers\Controller;
use App\Persistence\Models\Employee;
use App\Persistence\Repositories\LeaveCalendarRepository;
use App\Persistence\Services\LeaveRequestService;
use App\Traits\AlertMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WithdrawLeaveRequestController extends Controller
{
    use AlertMessage;

    /**
     * @var LeaveCalendarRepository $leaveCalendarRepository
     */
    private $leaveCalendarRepository;

    /**
     * @var LeaveRequestService $LeaveRequestService
     */
    private $LeaveRequestService;

    public function __construct()
    {
        $this->leaveCalendarRepository = new LeaveCalendarRepository();
        $this->LeaveRequestService = new LeaveRequestService();
    }

    public function withdraw()
    {
        /**
         * @var Employee $user
         */
        $user = Auth::user();


        $leaveTypes = $user->getAvailableLeaveDaysCount();

        if ($leaveTypes->isEmpty()) {
            return $this->redirectError(route('dashboard'), 'Nincs szabadság szabály hozzárendelve a felhasználóhoz. Szabadság így nem igényelhető.');
        }
        $start_at = $leaveTypes->min('start_at');
        $end_at = $leaveTypes->max('end_at');

        $holidays = $this->leaveCalendarRepository->getHolidays($start_at, $end_at)->mapInto(Event::class);
        $workdays = $this->leaveCalendarRepository->getWorkDays($start_at, $end_at)->mapInto(Event::class);
        $leaveRequests = $this->leaveCalendarRepository->getLeaveRequests($start_at, $end_at, $user)->mapInto(Event::class);

        return view('leaves.withdraw',
            [
                'leaveTypes' => $leaveTypes,
                'minDate' => $start_at,
                'maxDate' => $end_at,
                'holidays' => $holidays,
                'workdays' => $workdays,
                'leaves' => $leaveRequests
            ]);
    }

}