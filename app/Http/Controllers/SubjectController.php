<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index(): JsonResponse {
        $subjects = Subject::with('topics')->get();
        return response()->json($subjects, 200);
    }
    public function saveSubject(Request $request): JsonResponse {
        DB::beginTransaction();
        try {
            $subject = Subject::create($request->all());
            DB::commit();
            return response()->json($subject, 201);
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json(["Subject could not be created: ". $e->getMessage()], 500);
        }
    }
    public function updateSubject(int $id, Request $request): JsonResponse {
        DB::beginTransaction();
        try {
            $subject = Subject::with('topics')->where('id', $id)->first();
            if ($subject != null) {
                $request = $this->parseRequest($request);
                $subject->update($request->all());
                $subject->save();
                DB::commit();
                $updatedSubject = Subject::with('topics')->where('id', $id)->first();
                return response()->json($updatedSubject, 200);
            } else {
                return response()->json(["message" => "Subject with the ID ".$id." doesn't exist"], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Subject could not be edited: " . $e->getMessage()], 500);
        }
    }

    public function deleteSubject(int $id): JsonResponse {
        $subject = Subject::where('id', $id)->first();
        if ($subject != null){
            $subject->delete();
            return response()->json('subject (' . $subject. ') deleted', 200);
        }else {
            return response()->json('subject (' . $subject . ') not found', 404);
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
