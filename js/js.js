"use strict";
window.onload = getProducts;
let API_URL = "api/product";

async function getProducts() {
  try {
    let id_product = form.getAttribute("data-idItem");
    let response = await fetch("api/products" + "/" + id_product);
    let products = await response.json();

    console.log(products);

    app.products = products;

    console.log(id_product);
  } catch (error) {
    console.log(error);
  }
}