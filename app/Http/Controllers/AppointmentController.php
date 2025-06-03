<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller
{
    public function index():JsonResponse {
        $appointments = Appointment::with(['tutor', 'student', 'topic', 'topic.subject'])->get();
        return response()->json($appointments, 200);
    }
    public function showOpenAppointments(): JsonResponse {
        $appointments = Appointment::where('status', 'open')->with(['tutor', 'student', 'topic'])->get();
        return response()->json($appointments, 200);
    }

    public function findById(int $id):JsonResponse {
        $appointments = Appointment::where('id', $id)->with(['tutor', 'student', 'topic'])->get();
        return response()->json($appointments, 200);
    }

    public function showTutorAppointments(int $tutor_id):JsonResponse {
        $appointments = Appointment::where('tutor_id', $tutor_id)->with(['tutor', 'student', 'topic'])->get();
        return response()->json($appointments, 200);
    }
    public function showStudentAppointments(int $student_id):JsonResponse {
        $appointments = Appointment::where('student_id', $student_id)->with(['tutor', 'student', 'topic'])->get();
        return response()->json($appointments, 200);
    }

    public function saveAppointment(Request $request): JsonResponse {
        DB::beginTransaction();
        try {
            $request = $this->parseRequest($request);
            $user = auth()->user();

            $tutor = User::find($request['tutor_id']);
            $student = User::find($request['student_id']);
            if ($tutor->role !== 'tutor') {
                return response()->json(['error' => 'User does not have role tutor'], 403);
            }
            if ($student !== null && $student->role !== 'student') {
                return response()->json(['error' => 'User does not have role student'], 403);
            }
            if($user->role === 'tutor') {
                $request['tutor_id'] = $user->id;
            } else {
                $request['student_id'] =  $user->id;
                $request['status'] = 'proposed_by_student';
            }
            $appointment = Appointment::create($request->all());
            DB::commit();
            return response()->json($appointment, 201);
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json(["Appointment could not be created: ". $e->getMessage()], 500);
        }
    }

    public function updateAppointment(int $id, Request $request): JsonResponse {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $appointment = Appointment::with(['tutor', 'student', 'topic'])->where('id', $id)->first();
            if (!$appointment) {
                return response()->json(["message" => "Appointment with ID $id doesn't exist"], 404);
            }
            if ($user->role === 'tutor'){
                if( !Gate::allows('own-appointment', $appointment)){
                    return response()->json("User is not allowed to update this appointment",403);
                }
                $request['tutor_id'] = $user->id;
                $request = $this->parseRequest($request);
                $appointment->update($request->all());
                $appointment->save();

            } else if($user->role === 'student'){
                if($appointment->status === 'open' && ($appointment->student_id === null || $appointment->student_id === $user->id)){
                    $appointment->status = 'confirmed';
                    $appointment->student_id = $user->id;
                    $appointment->save();
                } else {
                    return response()->json("Student can only confirm an appointment that is open and unassigned or already assigned to them",403);
                }
            }
            DB::commit();
            $updatedAppointment = Appointment::with(['tutor', 'student', 'topic'])->where('id', $id)->first();
            return response()->json($updatedAppointment, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Appointment could not be edited: " . $e->getMessage()], 500);
        }
    }

    public function deleteAppointment(int $id): JsonResponse {
        $appointment = Appointment::where('id', $id)->first();
        if ($appointment != null){
            if( !Gate::allows('own-appointment', $appointment)){
                return response()->json("user is not allowed to delete this appointment",403);
            }
            $appointment->delete();
            return response()->json('appointment (' . $appointment. ') deleted', 200);
        }else{
            return response()->json('appointment (' . $appointment. ') not found', 404);
        }
    }

    private function parseRequest(Request $request): Request {
        if ($request->has('proposed_time')) {
            try {
                $date = new \DateTime($request->proposed_time);
                $request['proposed_time'] = $date->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                throw new \Exception("Invalid proposed_time format: " . $e->getMessage());
            }
        }
        return $request;
    }
}
