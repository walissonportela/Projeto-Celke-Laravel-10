// Receber o seletor do campo valor
let inputValor = document.getElementById('valor');

if(inputValor){
    // Aguarda o usuário digitar valor no campo
    inputValor.addEventListener('input', function(){

        // Obter o valor atual removendo qualquer caractere que não seja número
        let valueValor = this.value.replace(/[^\d]/g, '');

        // Adicionar os separadores de milhares
        var formattedValue = (valueValor.slice(0, -2).replace(/\B(?=(\d{3})+(?!\d))/g, '.')) 
        + '' + valueValor.slice(-2);
        
        // Adicionar a vírgula e até dois digitos se houver centavos
        formattedValue = formattedValue.slice(0,-2) + ',' + formattedValue.slice(-2);

        // Atualizar o valor do campo
        this.value = formattedValue;

    });
};

// Receber o seletor apagar e percorrer a lista de registro
document.querySelectorAll('.btnDelete').forEach(function(button){

    // Aguardar o clique do usuário do botão apagar
    button.addEventListener('click', function(event){

        // Bloquear o recarregamento da página
        event.preventDefault();

        // Receber o atributo que possui o id do registro que deve ser excluído
        var deletedID = this.getAttribute('data-delete-id');

        // SweetAlert
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Você não poderá reverter isso!',
            icon: 'warning',
            showCancelButton: true,
            calcelButtonColor: '#0d6efd',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Sim, excluir!',
        }).then((result) => {

            // Carregar a página responsável em excluir se o usuário confirmar a exclusão
            if(result.isConfirmed){
                document.getElementById(`formExcluir${deletedID}`).submit();
            }

        });
    });
});

// Quando carregar a página execute o select2
$(function(){
    $('.select2').select2({
        theme: 'bootstrap-5'
    });
});



// Receber o seletor btnSendEmail e percorrer a lista de botões
document.querySelectorAll('.btnSendEmail').forEach(function(button){

    // Aguardar o clique do usuário do botão enviar email
    button.addEventListener('click', function(event){

        // Adicionar a classe 'disable' ao botão
        button.classList.add('disable');

        // Enviar o spinner para o botão
        button.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> <span role="status"> Enviando...</span>'
        

        
    });
});