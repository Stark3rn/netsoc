<?php

include_once("../database.php");
$sessionId = check_session();
$method = $_SERVER['REQUEST_METHOD'];
$ads_dir = "http://localhost/netsoc/ads/";

function get_ads() {
    global $Database;
    $stmt = $Database->prepare("
        SELECT ad_image
        FROM ads
        WHERE active = 1
        ORDER BY RAND()
        LIMIT 1
    ");
    $stmt->execute();

    $ad = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$ad) {
        return json_encode([
            'ad_image' => null
        ]);
    }

    return json_encode([
        'ad_image' => $ad['ad_image']
    ]);
}

if ($method === 'GET') {
	if(isset($_GET["get_ads"])) {
		if($_GET["get_ads"] === "1") {
			$ad = get_ads();			
			$data = json_decode($ad, true);
			$path = $ads_dir . $data["ad_image"];
			echo json_encode(["ad_image" => $path]);
        		exit;
		}
	}
}

?>
