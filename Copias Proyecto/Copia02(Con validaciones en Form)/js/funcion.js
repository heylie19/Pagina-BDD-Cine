//Para q cuando se termine d ejecutar la pagina se ejecute
//el codigo que hay dentro
$(document).ready(function(){

    $("#formRegistro").validate({

        rules:{
            pnombre:{
                required:true,
                minlength:3
            },
            snombre:{
                required:true,
                minlength:3
            },
            papellido:{
                required:true,
                minlength:3
            },
            sapellido:{
                required:true,
                minlength:3
            },
            correo:{
                required:true,
                email:true
            },
            tipoUsuario:{
                required:true,
                number:true,
                minlength:1,
                maxlength:1
            }
        },
        messages:{
            pnombre:{
                required: "Este campo es obligatorio",
                minlength: "Minimo 3 letras en el campo"
            },
            snombre:{
                //required:true,
                minlength: "Minimo 3 letras en el campo"
            },
            papellido:{
                required: "Este campo es obligatorio",
                minlength: "Minimo 3 letras en el campo"
            },
            sapellido:{
                required: "Este campo es obligatorio",
                minlength: "Minimo 3 letras en el campo"
            },
            correo:{
                required:"Este campo es obligatorio",
                email: "Se necesita al menos un @ en el campo"
            },
            tipoUsuario:{
                required:"Este campo es obligatorio",
                number:"Valido para Numeros",
                minlength: "Minimo un digito",
                maxlength:"Maximo un digito"
            }
        }


    });

});


$(document).ready(function(){

    $("#formGestion").validate({

        rules:{
            id:{
                required:true,
                minlength:5,
                maxlength:5
            },
            contra:{
                required:true,
                minlength:6,
                maxlength:20
            }
        },
        messages:{
            id:{
                required: "Este campo es obligatorio",
                minlength: "Minimo 5 caracteres en el campo",
                maxlength:"Maximo de 5 caracteres"
            },
            contra:{
                required:"Este campo es obligatorio",
                minlength: "Minimo 6 digitos",
                maxlength:"Has llegado al maximo de caracteres"
            }
        }


    });

});