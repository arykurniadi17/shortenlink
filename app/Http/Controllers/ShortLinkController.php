<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ShortLink;

class ShortLinkController extends Controller
{
    public function index() {}
     
    public function shortenLink($code)
    {
        $find = ShortLink::where('code', $code)->first();
        return redirect($find->link);
    }

    public function createShorten(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "link" => "required|url",
        ]);        

        $response = [
            'response' => ['code' => 404, 'message' => 'Initial error'],
        ];

        if ($validator->fails()) {
            $response['response']['code'] = 400;
            $response['response']['message'] = $validator->errors();
            return response()->json($response, $response['response']['code']);
        }        

        $input['link'] = $request->input('link');
        $input['code'] = str_random(6);

        $shortLinkDB = ShortLink::create($input);

        if($shortLinkDB) {
            $response['response']['code'] = 200;
            $response['response']['message'] = 'Shorten Link Generated Successfully';
            $response['data']['code'] = $input['code'];
            $response['data']['link_sort'] = url('/'.$input['code']);
        }
        else {
            $response['response']['code'] = 400;
            $response['response']['message'] = 'Shorten Link Generated failure';
        }

        return response()->json($response, $response['response']['code']);
    }

    public function updateShorten(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "code" => "required",
            "link" => "required|url",
        ]);        

        $response = [
            'response' => ['code' => 404, 'message' => 'Initial error'],
        ];

        if ($validator->fails()) {
            $response['response']['code'] = 400;
            $response['response']['message'] = $validator->errors();
            return response()->json($response, $response['response']['code']);
        }        

        $input['code'] = $request->input('code');
        $input['link'] = $request->input('link');

        $shortLinkDB = ShortLink::where('code',$input['code'])->first();
        if($shortLinkDB) {
            $shortLinkDB->link = $input['link'];
            $shortLinkDB->updated_at = date('Y-m-d H:i:s');
            $shortLinkDB->save();            

            $response['response']['code'] = 200;
            $response['response']['message'] = 'Update shorten Link Successfully';
            $response['data']['code'] = $input['code'];
            $response['data']['link_sort'] = url('/'.$input['code']);    
        }
        else {
            $response['response']['code'] = 400;
            $response['response']['message'] = 'Update Link failure';
        }
    
        return response()->json($response, $response['response']['code']);
    }

    public function deleteShorten(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "code" => "required",
        ]);        

        $response = [
            'response' => ['code' => 404, 'message' => 'Initial error'],
        ];

        if ($validator->fails()) {
            $response['response']['code'] = 400;
            $response['response']['message'] = $validator->errors();
            return response()->json($response, $response['response']['code']);
        }        

        $input['code'] = $request->input('code');

        $shortLinkDB = ShortLink::where('code',$input['code'])->delete();
        if($shortLinkDB) {
            $response['response']['code'] = 200;
            $response['response']['message'] = 'Delete shorten Link Successfully';
            $response['data']['code'] = $input['code'];
        }
        else {
            $response['response']['code'] = 400;
            $response['response']['message'] = 'Delete shorten Link failure';
        }
        
        return response()->json($response, $response['response']['code']);
    }
}