<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../core/connection.php");
require_once("../core/book.php");

$kitap = new Book();

$i = 1;
$j = 1;
$continue = true;

while($continue){
    $url = "http://www.kitapyurdu.com/kitap/popi/".$i.".html";
    $html = getContent($url);
 
    //preg_match("'<div class=\"col-md-3 photos\">(.*?)</div>'si",$html,$result);
    preg_match("'<title>(.*?)</title>'si",$html,$veri);
    
    $title = explode("-",explode("|",$veri[1])[0])[0];
    $author = explode("-",explode("|",$veri[1])[0])[1];
    preg_match("'<span itemprop=\"description\">(.*?)</span>'si",$html,$veri);
    $description = $veri[1];
    preg_match_all("'<span itemprop=\"name\">(.*?)</span>'si",$html,$publisher);
    $p = $publisher[1];
    preg_match("'<span itemprop=\"isbn\">(.*?)</span>'si",$html,$isbn);
    
    $isbn = $isbn[1];
    preg_match_all("'<span class=\"TL\"></span>(.*?)</small>'si",$html,$prices);
    
    
    $price = strip_tags($prices[1][0]);
    
    preg_match("'<div class=\"image\">(.*?)</div>'si",$html,$images);
    $image = trim(str_replace('<a href="','',explode("&",$images[1])[0]));
    $image = str_replace("http://","https://",$image);
    try{
    
        $kitap->AddBook($title,$author,$p[1],$description,$image,$isbn,$price,"","",$url,"","");
        $last_id = $kitap->pdo->lastInsertId();
        while(true){
            $commentURL = "http://www.kitapyurdu.com/index.php?route=product/product/review&product_id=".$i."&page=".$j;            
            $commentHTML = getContent($commentURL);
            preg_match_all("'<div class=\"mg-b-5\">(.*?)</div>'si",$commentHTML,$commentor);
            if(count($commentor[1]) < 1){
                break;
            }
            preg_match_all("'<div itemprop=\"description\" class=\"review-text\">(.*?)</div>'si",$commentHTML,$detail);
            //burada ekliyoruz veritabanına her commenti
            //$book_id,$comment,$website,$commentor
            
            $z = 0;
            foreach($commentor[1] as $comment){
                
                $kitap->AddComment($last_id,$detail[1][$z],"kitapyurdu",strip_tags($comment[0]));
                $z++;   

            }
          
            $j++;
        }
    }catch(PDOException $e){
        echo $url;
        echo $e->getMessage();
    }
    $i++;
    sleep(1);
}


function getContent($url){
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "user-agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36" // Here we add the header
      ),
    
    ));  
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      return $response;
    }

}