<?php

class Book extends Connection{

    function __construct(){
        parent::__construct();
    }
    function AddBook($title="",$author="",$publisher="",$description="",$image ="",$isbn="",$kitapYurduPrice="",$idefixPrice="",$drPrice="",$kitapYurdu="",$idefix="",$dr=""){
      try{
        $title = trim($title);
        $author = trim($author);
        $publisher = trim($publisher);
        $description = trim($description);
        $image = trim($image);
        $isbn = trim($isbn);
        $kitapYurduPrice = trim($kitapYurduPrice);
        $idefixPrice = trim($idefixPrice);
        $drPrice = trim($drPrice);
        $kitapYurdu = trim($kitapYurdu);
        $idefix = trim($idefix);
        $dr = trim($dr);
        $query = $this->pdo->prepare("INSERT INTO books(title,author,publisher,description,image,isbn,kitapyurdu_price,idefix_price,dr_price,kitapyurdu,idefix,dr   ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
        $query->bindValue(1,$title);
        $query->bindValue(2,$author);
        $query->bindValue(3,$publisher);
        $query->bindValue(4,$description);
        $query->bindValue(5,$image);
        $query->bindValue(6,$isbn);
        $query->bindValue(7,$kitapYurduPrice);
        $query->bindValue(8,$idefixPrice);
        $query->bindValue(9,$drPrice);
        $query->bindValue(10,$kitapYurdu);
        $query->bindValue(11,$idefix);
        $query->bindValue(12,$dr);
        return $query->execute(); 
    
      }catch(PDOException $e){
          return $e->getMessage();
      }
       }
    
    function AddComment($book_id,$comment,$website,$commentor){
        $query = $this->pdo->prepare("INSERT INTO comments(book_id,comment,website,commentor) VALUES(?,?,?,?)");
        $query->bindValue(1,$book_id);
        $query->bindValue(2,$comment);
        $query->bindValue(3,$website);
        $query->bindValue(4,$commentor);
        return $query->execute();
    }

}