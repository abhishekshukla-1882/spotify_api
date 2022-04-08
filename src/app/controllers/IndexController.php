<?php
// session_start();
use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
class IndexController extends Controller{
    public function indexAction()
    {
        $client_id = '1217a9d42f0f43fa96fd9c29e8134cfc'; 
        $client_secret = '46aa9e481a1640c0afe6dd7fbb565051'; 
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials'); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret)));
        
        $result=json_decode(curl_exec($ch),1);
        $this->session->set("token",$result['access_token']);
        // die($this->session->get("token"));

        $this->view->token =$result;
        // echo $result;
        // die;
    } 
    public function searchAction()
    {
        // echo "hhh";
        // $request = new Request();type
        $token = $this->request->getPost('val');
        $type = urlencode($this->request->getPost('type'));
        $song = $this->request->getPost('song');
        $song = urlencode($song);
        echo $type,"<br>";
        echo $token,"<br>",$song;
        // die;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://api.spotify.com/v1/search?query=$song&type=$type&locale=en-GB%2Cen-US%3Bq%3D0.9%2Cen%3Bq%3D0.8&offset=0&limit=20");
        // curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $token"));

        $response = json_decode(curl_exec($ch),1);
        
        // $this->view->data = $response;
        $s = "s";
        $type = $type.$s;
        $this->view->data = $response;
        $this->view->type = $type;
        $this->view->song = $song;
        // echo "<pre>";
        // print_r( $response["$type"]);
        // die;
    }
}