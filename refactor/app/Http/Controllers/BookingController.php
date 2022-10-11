<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Http\Requests;
use App\Models\Distance;
use Illuminate\Http\Request;
use App\Repository\BookingRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Validator;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'nullable|int',
            ]);

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response = array('success' => false, 'error' => trans('validation.invalidInput'), 'error_code' => 400, 'error_messages' => $error_messages);
            } else {
                if ($userId = $request->get('user_id')) {
                    $response = $this->repository->getUsersJobs($userId);
                } elseif (isset($request->__authenticatedUser->user_type) && ($request->__authenticatedUser->user_type == config('app.ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == config('app.SUPERADMIN_ROLE_ID'))) {
                    $response = $this->repository->getAll($request);
                } else {
                    $response = ['success' => false, 'error' => trans('validation.userNotExist')];
                }
            }
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $job = $this->repository->with('translatorJobRel.user')->find($id);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $job = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($job);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $response = $this->repository->store($request->__authenticatedUser, $data);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        try {
            $data = $request->all();
            $cuser = $request->__authenticatedUser;
            $response = $this->repository->updateJob($id, array_except($data, ['_token', 'submit']), $cuser);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {
        try {
            $adminSenderEmail = config('app.adminemail');
            $data = $request->all();
            $response = $this->repository->storeJobEmail($data);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'nullable|int',
            ]);

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response = array('success' => false, 'error' => trans('validation.InvalidInput'), 'error_code' => 400, 'error_messages' => $error_messages);
            } else {
                if ($userId = $request->get('user_id')) {
                    $response = $this->repository->getUsersJobsHistory($userId, $request);
                } else {
                    $response = ['success' => false, 'error' => trans('validation.userNotExist')];
                }
            }
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        try {

            $data = $request->all();
            $user = $request->__authenticatedUser;

            $response = $this->repository->acceptJob($data, $user);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $responseArray = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response($response);
    }

    public function acceptJobWithId(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'nullable|int',
            ]);

            if ($validator->fails()) {
                $error_messages = $validator->messages()->all();
                $response = array('success' => false, 'error' => trans('validation.InvalidInput'), 'error_code' => 400, 'error_messages' => $error_messages);
            } else {
            }

            $data = $request->get('job_id');
            $user = $request->__authenticatedUser;

            $response = $this->repository->acceptJobWithId($data, $user);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        try {

            $data = $request->all();
            $user = $request->__authenticatedUser;

            $response = $this->repository->cancelJobAjax($data, $user);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        try {
            $data = $request->all();

            $response = $this->repository->endJob($data);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);

    }

    public function customerNotCall(Request $request)
    {
        try {
            $data = $request->all();

            $response = $this->repository->customerNotCall($data);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        try {

            $data = $request->all();
            $user = $request->__authenticatedUser;

            $response = $this->repository->getPotentialJobs($user);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);
    }

    public function distanceFeed(Request $request)
    {
        try {
            $data = $request->all();

            if (isset($data['distance']) && $data['distance'] != "") {
                $distance = $data['distance'];
            } else {
                $distance = "";
            }
            if (isset($data['time']) && $data['time'] != "") {
                $time = $data['time'];
            } else {
                $time = "";
            }
            if (isset($data['jobid']) && $data['jobid'] != "") {
                $jobid = $data['jobid'];
            }

            if (isset($data['session_time']) && $data['session_time'] != "") {
                $session = $data['session_time'];
            } else {
                $session = "";
            }

            if ($data['flagged'] == 'true') {
                if ($data['admincomment'] == '') return "Please, add comment";
                $flagged = 'yes';
            } else {
                $flagged = 'no';
            }

            if ($data['manually_handled'] == 'true') {
                $manually_handled = 'yes';
            } else {
                $manually_handled = 'no';
            }

            if ($data['by_admin'] == 'true') {
                $by_admin = 'yes';
            } else {
                $by_admin = 'no';
            }

            if (isset($data['admincomment']) && $data['admincomment'] != "") {
                $admincomment = $data['admincomment'];
            } else {
                $admincomment = "";
            }
            if ($time || $distance) {

                $affectedRows = Distance::where('job_id', '=', $jobid)->update(array('distance' => $distance, 'time' => $time));
            }

            if ($admincomment || $session || $flagged || $manually_handled || $by_admin) {

                $affectedRows1 = Job::where('id', '=', $jobid)->update(array('admin_comments' => $admincomment, 'flagged' => $flagged, 'session_time' => $session, 'manually_handled' => $manually_handled, 'by_admin' => $by_admin));

            }
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }

        return response('Record updated!');
        return response()->json($response);
    }

    public function reopen(Request $request)
    {
        try {

            $data = $request->all();
            $response = $this->repository->reopen($data);

        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);
    }

    public function resendNotifications(Request $request)
    {
        try {

            $data = $request->all();
            $job = $this->repository->find($data['jobid']);
            $job_data = $this->repository->jobToData($job);
            $this->repository->sendNotificationTranslator($job, $job_data, '*');
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response(['success' => 'Push sent']);
        return response()->json($response);

    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        try {
            $data = $request->all();
            $job = $this->repository->find($data['jobid']);
            $job_data = $this->repository->jobToData($job);
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (Exception $e) {
            Log::error("Error in method 'Method name' " . $e->getLine() . ' with Error: ' . $e->getMessage());
            $response = array('success' => false, 'error' => trans('validation.exceptionError'), 'error_code' => 410, 'error_messages' => []);
        }
        return response()->json($response);
    }

}
