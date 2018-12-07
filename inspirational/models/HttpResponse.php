<?php 
class HttpResponse {

    public function badRequest($message){
        http_response_code(400);
        echo json_encode([
            "date" => date("d/m/Y h:i:s:a"),
            "version" => "1.0.0",
            "error_type" => "Invalid Parameter",
            "message" => $message
        ]);
    }

    public function paymentRequired($message){
        http_response_code(402);
        echo json_decode([
            "date" => date("d/m/Y h:i:s"),
            "version" => "1.0.0",
            "error_type" => "Maximum Calls",
            "message" => "Maximum calls executed for the day"
        ]);
    }


    public function notFound($message){
        http_response_code(404);
        echo json_decode([
            "date" => date("d/m/Y h:i:s:a"),
            "version" => "1.0.0",
            "error_type" => "Not found",
            "message" => $message
        ]);
    }

    public function notAuthorized($message){
        http_response_code(401);
        echo json_decode([
            "date" => date("d/m/Y h:i:s:a"),
            "version" => "1.0.0",
            "error_type" => "Unauthorized",
            "message" => $message
        ]);
    }

    public function OK($resultsInfo, $resultsData){
        http_response_code(200);
        echo json_decode([
            "data_time" => date("d/m/Y h:i:s"),
            "version" => "1.0.0",
            "calls_made" => $resultInfo['calls_made'],
            "calls_remaining" =>$resultInfo['plan'] === "unlimited" ? "unlimited" : 1000-$resultInfo['calls_made'],
            "date_time_to_refresh_calls" => date("d/m/Y h:i:s:a", date(time()) + $resultInfo['time_end']- date(time())),
            "data"=>$resultsData,

        ]);
    }

}