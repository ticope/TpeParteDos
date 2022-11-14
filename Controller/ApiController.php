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
        if(isset($_GET['order']) && isset($_GET['sort'])){
            if(($_GET['order']== "asc") || ($_GET['order']=="ASC")){
                $products = $this->model->getProductsASC();
                return $this->view->response($products, 200);
            }
            elseif(($_GET['order']== "desc") || ($_GET['order']=="DESC")){
                $products = $this->model->getProductsDESC();
                return $this->view->response($products, 200);
            }
            
        }
            $products = $this->model->getProductsFromDB();
            if(!empty($products)){
                    return $this->view->response($products, 200);
            }
            else{
                return $this->view->response(null,404);
        }
    }
    function getProductsByCatOffer($params = null){
        $id_category = $params[":ID"];
        $products = $this->model->getProductsByCategory($id_category);
        if(!empty($products)){
            $productsOffer = [];
            foreach ($products as $product){
                if($product->oferta!=0){
                  array_push($productsOffer,$product);
                }
            }
            if(!empty($productsOffer)){
                return $this->view->response($productsOffer, 200);
            }
            else{
                return $this->view->response("No se encontraron productos en oferta en la categoria = $id_category",400);
            }
        }
        else{
            return $this->view->response("No se encontraron productos en oferta en la categoria = $id_category",400);
        }

    }
    function getProduct($params = null){
        $id = $params[":ID"];
        $product = $this->model->getProduct($id);
        if(!empty($product)){
            return $this->view->response($product, 200);  
        }
        else{
            return $this->view->response("No existe el producto con id = $id", 400);
        }

    }
    function createProduct($params = null){
            
            $body = $this->getBody();
            if(!empty($body->name) && !empty($body->price) && !empty($body->id_category)){
            $name = $body->name;
            $price = $body->price;
            $id_category = $body->id_category;
                $this->model->insertProductOnDB($price, $name,$id_category);
                $this->view->response("Producto creado con exito", 201);
            }
            else{
                $this->view->response("No se pudo crear el producto",400);
            }
    }
    function getBody(){
        $bodyString = file_get_contents("php://input");
        return json_decode($bodyString);
    }
    
    function deleteProduct($params = null){
        $idProduct = $params[":ID"];
        $product = $this->model->getProduct($idProduct);
            if(!empty($product)){
                $product = $this->model->deleteProductFromdb($idProduct);
                return $this->view->response("El Producto con el id=$idProduct fue borrado", 200); 
            }else{
            $this->view->response("El Producto con el id=$idProduct no existe", 404);
        }
    }

    function updateProduct($params = null){
        $body = $this->getBody();
        $idProduct = $params[":ID"];
        $product = $this->model->getProduct($idProduct);
        $name = $body->name;
        $price = $body->price;
        $id_category = $body->id_category;
        if(!empty($product) && !empty($body->name) && !empty($body->price) && !empty($body->id_category)){
            if(is_numeric($id_category)){
            $this->model->updateProductFromDB($name, $price, $id_category,$idProduct);
            $this->view->response("Producto editado con exito", 200);
            }
            else{
                $this->view->response("El producto no pudo editarse", 404);
            }
        }
        else{
            $this->view->response("El producto no pudo editarse", 404);
        } 
    }
}

