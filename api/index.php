<?php

    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    // http foundation for basic data processing: https://symfony.com/doc/current/components/http_foundation.html
    include_once "../vendor/autoload.php";

    // default init
    $token = "";
    $method = "listAllMethods";           // requested method
    $output_format = "html";    // html or json
    $content = "";

    // request
    $request = Request::createFromGlobals();

    // params
    if ($request->query->has("method")) {
        // is defined
        $method = $request->query->get("method");
    }
    //echo "selected method: $method";

    // list of all available methods
    $methods_all_list = array();

    // add method start
    $new_method = array();
    $new_method["name"] = "listAllMethods";
    $new_method["desc"] = "List of all available methods";
    $new_method["params"] = array();
    $new_method["params"]["token"] = "(String) access token from Dalibor Fiala.";
    $methods_all_list[] = $new_method;
    // add method end

    // add method start
    $new_method = array();
    $new_method["name"] = "getAllAvailableStates";
    $new_method["desc"] = "Currently avaliable only in JSON.";
    $new_method["params"] = array();
    $new_method["params"]["token"] = "(String) access token from Dalibor Fiala.";
    $methods_all_list[] = $new_method;
    // add method end

    // konec seznam metod

    /**
     * Send response to the user.
     * @param $content
     * @param string $type
     * @param int $http_status
     * @return Response
     */
    function SendResponse($content, $type = "html", $http_status = Response::HTTP_OK){
        if ($type == "html") {
            $content_type = "text/html";
        }
        else if ($type == "json") {
            // will be used jsonResponse
        }

        // send response
        if ($type == "json") {
            // add aditional information
            $content["response_ok"] = true;
            $content["response_msg"] = "Everything OK. Detail information in case that response_ok is false.";

            // json response
            $response = new JsonResponse($content, $http_status);
            $response->send();
        }
        else {
            // other types
            $response = new Response($content, $http_status, array('content-type' => $content_type));
            $response->send();
        }
    }

    // params from $_GET - for example docs + token - from browser
    if (isset($_GET["token"])) {
        $token = $_GET["token"];
    }

    if ($method == "listAllMethods") {
        if ($output_format == "html") {

            $content .= "<html><head></head><body>";
            if ($methods_all_list != null) {
                foreach ($methods_all_list as $method) {
                    $content .= "<h2>$method[name]</h2>";

                    if ($method["params"] != null)
                        $content .= "<table>";
                            $content .= "<tr><td>Parameter name</td><td>Description</td></tr>";

                        foreach ($method["params"] as $param_name => $param_desc) {
                            $content .= "<tr><td>$param_name</td><td>$param_desc</td></tr>";
                        }
                        $content .= "</table>";
                }
            }
            $content .= "</body></html>";

            SendResponse($content);
        }
    }

    if ($method == "getAllAvailableStates") {
        $states = array();
        $states[] = "Austria";
        $states[] = "Czech republic";
        $states[] = "Slovenia";
        $states[] = "Ukraine";

        // based on OWASP for security reason it has to be object
        $json_data = array();
        $json_data["states"] = $states;

        // forced json
        $output_format = "json";
        SendResponse($json_data, "json");
    }