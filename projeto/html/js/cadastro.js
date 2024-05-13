const form = document.getElementById("productForm");
const productName = document.getElementById("productName");
const productPrice = document.getElementById("productPrice");
const productQuantity = document.getElementById("productQuantity");
const productDescription = document.getElementById("productDescription");
const productSupplier = document.getElementById("productSupplier");

form.addEventListener("submit", (event) =>{
    event.preventDefault();
    console.log("Formulário enviado");
    checkForm();
});

productName.addEventListener("blur", ()=>{
    checkInputProductName();
});

productPrice.addEventListener("blur", ()=>{
    checkInputProductPrice();
});

productQuantity.addEventListener("blur", ()=>{
    checkInputQuantity();
});

productSupplier.addEventListener("blur", ()=>{
    checkInputProductSupplier();
});

function checkInputProductName(){
    const productNameValue = productName.value;
    
    if(productNameValue === ""){
        errorInput(productName, "Favor, coloque o nome do produto");
    }else{
        const formItem = productName.parentElement;
        formItem.className = "form-content";
    }
}

function checkInputProductPrice(){
    const productPriceValue = productPrice.value;

    if(productPriceValue === ""){
        errorInput(productPrice, "Informe um preço.");
    }else{
        const formItem = productPrice.parentElement;
        formItem.className = "form-content";
    }
}

function checkInputQuantity(){
    const productQuantityValue = productQuantity.value;

    if(productQuantityValue === ""){
        errorInput(productQuantity, "Informe a quantidade.");
    }else{
        const formItem = productQuantity.parentElement;
        formItem.className = "form-content";
    }
}

function checkInputProductSupplier(){
    const productSupplierValue = productSupplier.value;

    if(productSupplierValue === ""){
        errorInput(productSupplier, "Informe o fornecedor do produto.");
    }else{
        const formItem = productSupplier.parentElement;
        formItem.className = "form-content";
    }
}

function checkForm(){
    checkInputProductName();
    checkInputProductPrice();
    checkInputQuantity();
    checkInputProductSupplier();

    const formItems = form.querySelectorAll(".form-content");

    const isValid = [...formItems].every((item)=>{
        return item.className === "form-content";
    });

    if(isValid){
        console.log("Dados do formulário:");
        console.log("Nome do Produto:", productName.value);
        console.log("Preço:", productPrice.value);
        console.log("Quantidade:", productQuantity.value);
        console.log("Descrição:", productDescription.value);
        console.log("Fornecedor:", productSupplier.value);
        clearFormFields();
        alert("PRODUTO CADASTRADO COM SUCESSO");
    }
}

function errorInput(input, message){
    const formItem = input.parentElement;
    const textMessage = formItem.querySelector("a");

    textMessage.innerText = message;

    formItem.className = "form-content error";
}

// Função para limpar os campos do formulário
function clearFormFields() {
    productName.value = "";
    productPrice.value = "";
    productQuantity.value = "";
    productDescription.value = "";
    productSupplier.value = "";
}

function redirecionar(url) {
    window.location.href = url;
}
