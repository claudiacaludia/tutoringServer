<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TopicController extends Controller
{
    public function index(): JsonResponse {
        $topic = Topic::with('subject')->get();
        return response()->json($topic, 200);
    }

    public function saveTopic(Request $request): JsonResponse {
        DB::beginTransaction();
        try {
            $topic = new Topic();
            $topic->name = $request->name;
            $topic->description = $request->description;
            $topic->subject_id = $request->subject_id;
            $topic->save();
            DB::commit();
            return response()->json($topic, 201);
        } catch(\Exception $e){
            DB::rollBack();
            return response()->json(["Topic could not be created: ". $e->getMessage()], 500);
        }
    }


    public function updateTopic(int $id, Request $request): JsonResponse {
        DB::beginTransaction();
        try {
            $topic = Topic::with('subject')->where('id', $id)->first();
            if ($topic != null) {
                $request = $this->parseRequest($request);
                $topic->update($request->all());
                $updatedTopic = Topic::with('subject')->where('id', $id)->first(); //query builder
                DB::commit();
                return response()->json($updatedTopic, 200);
            } else {
                DB::rollBack();
                return response()->json(["message" => "Topic with the ID ".$id." doesn't exist"], 404);
            }
        } catch (\Exception $e) {
            return response()->json(["error" => "Topic could not be edited: " . $e->getMessage()], 500);
        }
    }


    public function deleteTopic(int $id): JsonResponse {
        $topic = Topic::with('subject')->where('id', $id)->first();
        if ($topic != null){
            $topic->delete();
            return response()->json('topic (' . $topic. ') deleted', 200);
        }else {
            return response()->json('topic (' . $topic . ') not found', 404);
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
