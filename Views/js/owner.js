

document.getElementById('btnRegister').addEventListener('click',function(){

      var last_name = document.getElementById('lastName').value;
      var first_name = document.getElementById('firstName').value;
      var cellPhone = document.getElementById('cellPhone').value;
      var birthDate = document.getElementById('birthDate').value;
      var email = document.getElementById('email').value;
      var password = document.getElementById('password').value;
      var password1 = document.getElementById('password1').value;

      if(password.localeCompare(password1)){
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Las contraseñas ingresadas no son iguales!!!!!!!!!'
          
        })
        await(2000);
      }else if(last_name == '' || first_name == '' || cellPhone == '' || birthDate == '' || email == ''){
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Debe completar todos los campos!!'
            })
          }else {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Usuario creado!',
              showConfirmButton: false,
              timer: 1500
            });
          }
});

document.getElementById('loginOwner').addEventListener('click',function(){

  var email = document.getElementById('email').value;
  var password = document.getElementById('password').value;

  /*if(password.localeCompare(password1)){
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Las contraseñas ingresadas no son iguales!!!!!!!!!'
      
    })
    await(2000);
  }else*/ if(email == '' || password == ''){
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Debe completar todos los campos!!'
        })
      }else {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'Usuario creado!',
          showConfirmButton: false,
          timer: 1500
        });
      }
});