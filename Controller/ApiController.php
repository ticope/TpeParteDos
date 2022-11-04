<?php

require_once "./Model/shopModel.php";
require_once "./View/ApiView.php";

class ApiController{

    private $model;
    private $view;
   

    function __construct()
    {
        $this->model = new shopModel();
        $this->view = new ApiView();
    }

    function getProducts(){
        $products = $this->model->getProductsFromDB();
        if($products){
         return $this->view->response($products, 200);
        }
        else{
            return $this->view->response(null,404);
        }
    }
    function getProductsByCat($params = null){
        $id_category = $params[":ID"];
        $products = $this->model->getProductsByCategory($id_category);
        if($products){
            $productsOffer = [];
            foreach ($products as $product){
                if($product->oferta!=0){
                  array_push($productsOffer,$product);
                }
            }
            return $this->view->response($productsOffer, 200);
        }
        else{
            return $this->view->response(null,204);
        }

    }
    function getProduct($params = null){
        $id = $params[":ID"];
        $product = $this->model->getProduct($id);
        if($product){
            return $this->view->response($product, 200);
        }
        else{
            return $this->view->response("No existe el producto con id = $id", 400);
        }

    }
    function createProduct($params = null){
            $body = $this->getBody();
            $name = $body->name;
            $price = $body->price;
            $id_category = $body->id_category;
            
            $this->model->insertProductOnDB($price, $name,$id_category);
            $this->view->response("Comentario creado con exito", 201);
            }
    function getBody(){
        $bodyString = file_get_contents("php://input");
        return json_decode($bodyString);
    }
    
    function deleteProduct($params = null){

        $idProduct = $params[":ID"];
        $product = $this->model->getProduct($idProduct);
            if($product){
                $product = $this->model->deleteProductFromdb($idProduct);
                return $this->view->response("El comentario con el id=$idProduct fue borrado", 200); 
            }else{
            $this->view->response("El comentario con el id=$idProduct no existe", 404);
        }
    }

}

