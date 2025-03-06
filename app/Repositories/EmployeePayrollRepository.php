<?php

namespace App\Repositories;

use App\Models\Accountant;
use App\Models\EmployeePayroll;
use App\Models\Notification;
use App\Models\User;
use DateTime;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class EmployeePayrollRepository
 *
 * @version February 19, 2020, 8:03 am UTC
 */
class EmployeePayrollRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'sr_no',
        'payroll_id',
        'owner_id',
        'owner_type',
        'month',
        'year',
        'net_salary',
        'status',
        'basic_salary',
        'allowance',
        'deductions',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return EmployeePayroll::class;
    }

    public function create($input)
    {
        $input['basic_salary'] = removeCommaFromNumbers($input['basic_salary']);
        $input['allowance'] = removeCommaFromNumbers($input['allowance']);
        $input['deductions'] = removeCommaFromNumbers($input['deductions']);
        $input['net_salary'] = removeCommaFromNumbers($input['net_salary']);
        $input['month'] = DateTime::createFromFormat('!m', $input['month'])->format('F');
        $input['owner_type'] = EmployeePayroll::CLASS_TYPES[$input['type']];
        parent::create($input);

        return true;
    }

    public function update($input, $id)
    {
        $input['basic_salary'] = removeCommaFromNumbers($input['basic_salary']);
        $input['allowance'] = removeCommaFromNumbers($input['allowance']);
        $input['deductions'] = removeCommaFromNumbers($input['deductions']);
        $input['net_salary'] = removeCommaFromNumbers($input['net_salary']);
        $input['month'] = DateTime::createFromFormat('!m', $input['month'])->format('F');
        $input['owner_type'] = EmployeePayroll::CLASS_TYPES[$input['type']];
        parent::update($input, $id);

        return true;
    }

    public function createNotification($input)
    {
        $input['owner_type'] = EmployeePayroll::CLASS_TYPES[$input['type']];
        try {
            $employee = User::where('owner_type', $input['owner_type'])->where('owner_id', $input['owner_id'])->first();
            $accountant = Accountant::pluck('user_id', 'id')->toArray();
            $userIds = [
                $employee->id => Notification::NOTIFICATION_FOR[EmployeePayroll::TYPES[$input['type']]],
            ];

            foreach ($accountant as $key => $userId) {
                $userIds[$userId] = Notification::NOTIFICATION_FOR[Notification::ACCOUNTANT];
            }
            $users = getAllNotificationUser($userIds);

            foreach ($users as $key => $notification) {
                if ($notification == Notification::NOTIFICATION_FOR[EmployeePayroll::TYPES[$input['type']]]) {
                    $title = $employee->full_name.' your employee payroll has been created.';
                } else {
                    $title = $employee->full_name.' employee payroll has been created.';
                }
                addNotification([
                    Notification::NOTIFICATION_TYPE['Employee Payrolls'],
                    $key,
                    $notification,
                    $title,
                ]);
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
